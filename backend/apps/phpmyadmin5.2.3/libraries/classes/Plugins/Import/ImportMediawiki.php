<?php
/**
 * MediaWiki import plugin for phpMyAdmin
 */

declare(strict_types=1);

namespace PhpMyAdmin\Plugins\Import;

use PhpMyAdmin\File;
use PhpMyAdmin\Message;
use PhpMyAdmin\Plugins\ImportPlugin;
use PhpMyAdmin\Properties\Plugins\ImportPluginProperties;

use function __;
use function count;
use function explode;
use function mb_strlen;
use function mb_strpos;
use function mb_substr;
use function preg_match;
use function str_contains;
use function str_replace;
use function strcmp;
use function strlen;
use function trim;

/**
 * Handles the import for the MediaWiki format
 */
class ImportMediawiki extends ImportPlugin
{
    /**
     * Whether to analyze tables
     *
     * @var bool
     */
    private $analyze;

    /**
     * @psalm-return non-empty-lowercase-string
     */
    public function getName(): string
    {
        return 'mediawiki';
    }

    protected function setProperties(): ImportPluginProperties
    {
        $this->setAnalyze(false);
        if ($GLOBALS['plugin_param'] !== 'table') {
            $this->setAnalyze(true);
        }

        $importPluginProperties = new ImportPluginProperties();
        $importPluginProperties->setText(__('MediaWiki Table'));
        $importPluginProperties->setExtension('txt');
        $importPluginProperties->setMimeType('text/plain');
        $importPluginProperties->setOptionsText(__('Options'));

        return $importPluginProperties;
    }

    /**
     * Handles the whole import logic
     *
     * @param array $sql_data 2-element array with sql data
     */
    public function doImport(?File $importHandle = null, array &$sql_data = []): void
    {
        global $error, $timeout_passed, $finished;
        $buffer = '';
        $last_chunk_line = '';
        $inside_comment = false;
        $inside_data_comment = false;
        $inside_structure_comment = false;
        $mediawiki_new_line = "\n";
        $cur_table_name = '';

        $cur_temp_table_headers = [];
        $cur_temp_table = [];

        $in_table_header = false;

        while (! $finished && ! $error && ! $timeout_passed) {
            $data = $this->import->getNextChunk($importHandle);

            if ($data === false) {
                $GLOBALS['offset'] -= mb_strlen($buffer);
                break;
            }

            if ($data !== true) {
                $buffer = $data;
                unset($data);
                if (! str_contains($buffer, $mediawiki_new_line)) {
                    continue;
                }
            }
            $buffer = $last_chunk_line . $buffer;
            $buffer_lines = explode($mediawiki_new_line, $buffer);

            $full_buffer_lines_count = count($buffer_lines);
            if (! $finished) {
                $last_chunk_line = $buffer_lines[--$full_buffer_lines_count];
            }

            for ($line_nr = 0; $line_nr < $full_buffer_lines_count; ++$line_nr) {
                $cur_buffer_line = trim($buffer_lines[$line_nr]);
                if ($cur_buffer_line === '') {
                    continue;
                }

                $first_character = $cur_buffer_line[0];
                $matches = [];
                if (! strcmp(mb_substr($cur_buffer_line, 0, 4), '<!--')) {
                    $inside_comment = true;
                    continue;
                }

                if ($inside_comment) {
                    if (! strcmp(mb_substr($cur_buffer_line, 0, 4), '-->')) {
                        if ($inside_data_comment) {
                            $inside_data_comment = false;
                        }
                        if (! $inside_structure_comment) {
                            $inside_comment = false;
                        }
                    } else {
                        $match_table_name = [];
                        if (preg_match('/^Table data for `(.*)`$/', $cur_buffer_line, $match_table_name)) {
                            $cur_table_name = $match_table_name[1];
                            $inside_data_comment = true;

                            $inside_structure_comment = $this->mngInsideStructComm($inside_structure_comment);
                        } elseif (preg_match('/^Table structure for `(.*)`$/', $cur_buffer_line, $match_table_name)) {
                            $inside_structure_comment = true;
                        }
                    }

                    continue;
                }

                if (preg_match('/^\{\|(.*)$/', $cur_buffer_line, $matches)) {
                    $cur_temp_table = [];
                    $cur_temp_line = [];
                    $in_table_header = false;
                } elseif (
                    mb_substr($cur_buffer_line, 0, 2) === '|-'
                    || mb_substr($cur_buffer_line, 0, 2) === '|+'
                    || mb_substr($cur_buffer_line, 0, 2) === '|}'
                ) {
                    if (! empty($cur_temp_line)) {
                        if ($in_table_header) {
                            $cur_temp_table_headers = $cur_temp_line;
                        } else {
                            $cur_temp_table[] = $cur_temp_line;
                        }
                    }
                    $cur_temp_line = [];
                    if (mb_substr($cur_buffer_line, 0, 2) === '|}') {
                        $current_table = [
                            $cur_table_name,
                            $cur_temp_table_headers,
                            $cur_temp_table,
                        ];
                        $this->importDataOneTable($current_table, $sql_data);
                        $cur_table_name = '';
                    }
                } elseif (($first_character === '|') || ($first_character === '!')) {
                    if ($first_character === '!') {
                        $cur_buffer_line = str_replace('!!', '||', $cur_buffer_line);
                        $in_table_header = true;
                    } else {
                        $in_table_header = false;
                    }
                    $cells = $this->explodeMarkup($cur_buffer_line);
                    foreach ($cells as $cell) {
                        $cell = $this->getCellData($cell);
                        $cell = trim($cell);
                        $col_start_chars = [
                            '|',
                            '!',
                        ];
                        foreach ($col_start_chars as $col_start_char) {
                            $cell = $this->getCellContent($cell, $col_start_char);
                        }
                        $cur_temp_line[] = $cell;
                    }
                } else {
                    $message = Message::error(
                        __('Invalid format of mediawiki input on line: <br>%s.')
                    );
                    $message->addParam($cur_buffer_line);
                    $error = true;
                }
            }
        }
    }

    /**
     * Imports data from a single table
     *
     * @param array $table    containing all table info:
     *                        <code> $table[0] - string
     *                        containing table name
     *                        $table[1] - array[]   of
     *                        table headers $table[2] -
     *                        array[][] of table content
     *                        rows </code>
     * @param array $sql_data 2-element array with sql data
     *
     * @global bool $analyze whether to scan for column types
     */
    private function importDataOneTable(array $table, array &$sql_data): void
    {
        $analyze = $this->getAnalyze();
        if ($analyze) {
            $this->setTableName($table[0]);
            if ($table[2] !== []) {
                $this->setTableHeaders($table[1], $table[2][0]);
            }
            $tables = [];
            $tables[] = [
                $table[0],
                $table[1],
                $table[2],
            ];
            $analyses = [];
            $analyses[] = $this->import->analyzeTable($tables[0]);

            $this->executeImportTables($tables, $analyses, $sql_data);
        }
        $this->import->runQuery('', '', $sql_data);
    }

    /**
     * Sets the table name
     *
     * @param string $table_name reference to the name of the table
     */
    private function setTableName(&$table_name): void
    {
        global $dbi;

        if (! empty($table_name)) {
            return;
        }

        $result = $dbi->fetchResult('SHOW TABLES');
        $table_name = 'TABLE ' . (count($result) + 1);
    }

    /**
     * Set generic names for table headers, if they don't exist
     *
     * @param array $table_headers reference to the array containing the headers
     *                             of a table
     * @param array $table_row     array containing the first content row
     */
    private function setTableHeaders(array &$table_headers, array $table_row): void
    {
        if (! empty($table_headers)) {
            return;
        }
        $num_cols = count($table_row);
        for ($i = 0; $i < $num_cols; ++$i) {
            $table_headers[$i] = 'COL ' . ($i + 1);
        }
    }

    /**
     * Sets the database name and additional options and calls Import::buildSql()
     * Used in PMA_importDataAllTables() and $this->importDataOneTable()
     *
     * @param array $tables   structure:
     *                        array(
     *                        array(table_name, array() column_names, array()()
     *                        rows)
     *                        )
     * @param array $analyses structure:
     *                        $analyses = array(
     *                        array(array() column_types, array() column_sizes)
     *                        )
     * @param array $sql_data 2-element array with sql data
     *
     * @global string $db      name of the database to import in
     */
    private function executeImportTables(array &$tables, array &$analyses, array &$sql_data): void
    {
        global $db;
        [$db_name, $options] = $this->getDbnameAndOptions($db, 'mediawiki_DB');
        $create = null;
        $this->import->buildSql($db_name, $tables, $analyses, $create, $options, $sql_data);
    }

    /**
     * Replaces all instances of the '||' separator between delimiters
     * in a given string
     *
     * @param string $replace the string to be replaced with
     * @param string $subject the text to be replaced
     *
     * @return string with replacements
     */
    private function delimiterReplace($replace, $subject)
    {
        $cleaned = '';
        $inside_tag = false;
        $inside_attribute = false;
        $start_attribute_character = false;
        $partial_separator = false;
        for ($i = 0, $iMax = strlen($subject); $i < $iMax; $i++) {
            $cur_char = $subject[$i];
            if ($cur_char === '|') {
                if (! $inside_attribute) {
                    $cleaned .= $cur_char;
                    if ($partial_separator) {
                        $inside_tag = false;
                        $inside_attribute = false;
                    }
                } elseif ($partial_separator) {
                    $cleaned .= $replace;
                }
                $partial_separator = ! $partial_separator;
            } else {
                if ($partial_separator && $inside_attribute) {
                    $cleaned .= '|';
                }
                $partial_separator = false;
                $cleaned .= $cur_char;

                if ($cur_char === '<' && ! $inside_attribute) {
                    $inside_tag = true;
                } elseif ($cur_char === '>' && ! $inside_attribute) {
                    $inside_tag = false;
                } elseif (($cur_char === '"' || $cur_char == "'") && $inside_tag) {
                    if (! $inside_attribute) {
                        $inside_attribute = true;
                        $start_attribute_character = $cur_char;
                    } else {
                        if ($cur_char == $start_attribute_character) {
                            $inside_attribute = false;
                            $start_attribute_character = false;
                        }
                    }
                }
            }
        }

        return $cleaned;
    }

    /**
     * Separates a string into items, similarly to explode
     * Uses the '||' separator (which is standard in the mediawiki format)
     * and ignores any instances of it inside markup tags
     * Used in parsing buffer lines containing data cells
     *
     * @param string $text text to be split
     *
     * @return array
     */
    private function explodeMarkup($text)
    {
        $separator = '||';
        $placeholder = "\x00";
        $text = str_replace($placeholder, '', $text);
        $cleaned = $this->delimiterReplace($placeholder, $text);
        $items = explode($separator, $cleaned);
        foreach ($items as $i => $str) {
            $items[$i] = str_replace($placeholder, $separator, $str);
        }

        return $items;
    }

    

    /**
     * Returns true if the table should be analyzed, false otherwise
     */
    private function getAnalyze(): bool
    {
        return $this->analyze;
    }

    /**
     * Sets to true if the table should be analyzed, false otherwise
     *
     * @param bool $analyze status
     */
    private function setAnalyze($analyze): void
    {
        $this->analyze = $analyze;
    }

    /**
     * Get cell
     *
     * @param string $cell Cell
     *
     * @return mixed
     */
    private function getCellData($cell)
    {
        $cell_data = explode('|', $cell, 2);
        if (! str_contains($cell_data[0], '[[')) {
            return $cell;
        }

        if (count($cell_data) === 1) {
            return $cell_data[0];
        }

        return $cell_data[1];
    }

    /**
     * Manage $inside_structure_comment
     *
     * @param bool $inside_structure_comment Value to test
     */
    private function mngInsideStructComm($inside_structure_comment): bool
    {
        if ($inside_structure_comment) {
            $inside_structure_comment = false;
        }

        return $inside_structure_comment;
    }

    /**
     * Get cell content
     *
     * @param string $cell           Cell
     * @param string $col_start_char Start char
     *
     * @return string
     */
    private function getCellContent($cell, $col_start_char)
    {
        if (mb_strpos($cell, $col_start_char) === 0) {
            $cell = trim(mb_substr($cell, 1));
        }

        return $cell;
    }
}
