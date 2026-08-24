<?php
/**
 * CSV import plugin for phpMyAdmin
 *
 * @todo       add an option for handling NULL values
 */

declare(strict_types=1);

namespace PhpMyAdmin\Plugins\Import;

use PhpMyAdmin\File;
use PhpMyAdmin\Html\Generator;
use PhpMyAdmin\Message;
use PhpMyAdmin\Properties\Options\Groups\OptionsPropertyRootGroup;
use PhpMyAdmin\Properties\Options\Items\BoolPropertyItem;
use PhpMyAdmin\Properties\Options\Items\NumberPropertyItem;
use PhpMyAdmin\Properties\Options\Items\TextPropertyItem;
use PhpMyAdmin\Properties\Plugins\ImportPluginProperties;
use PhpMyAdmin\Util;

use function __;
use function array_shift;
use function array_splice;
use function basename;
use function count;
use function mb_strlen;
use function mb_strtolower;
use function mb_substr;
use function preg_grep;
use function preg_replace;
use function preg_split;
use function rtrim;
use function str_contains;
use function strlen;
use function strtr;
use function trim;

/**
 * Handles the import for the CSV format
 */
class ImportCsv extends AbstractImportCsv
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
        return 'csv';
    }

    protected function setProperties(): ImportPluginProperties
    {
        $this->setAnalyze(false);

        if ($GLOBALS['plugin_param'] !== 'table') {
            $this->setAnalyze(true);
        }

        $importPluginProperties = new ImportPluginProperties();
        $importPluginProperties->setText('CSV');
        $importPluginProperties->setExtension('csv');
        $importPluginProperties->setOptionsText(__('Options'));
        $importSpecificOptions = new OptionsPropertyRootGroup('Format Specific Options');

        $generalOptions = $this->getGeneralOptions();

        if ($GLOBALS['plugin_param'] !== 'table') {
            $leaf = new TextPropertyItem(
                'new_tbl_name',
                __(
                    'Name of the new table (optional):'
                )
            );
            $generalOptions->addProperty($leaf);

            if ($GLOBALS['plugin_param'] === 'server') {
                $leaf = new TextPropertyItem(
                    'new_db_name',
                    __(
                        'Name of the new database (optional):'
                    )
                );
                $generalOptions->addProperty($leaf);
            }

            $leaf = new NumberPropertyItem(
                'partial_import',
                __(
                    'Import these many number of rows (optional):'
                )
            );
            $generalOptions->addProperty($leaf);

            $leaf = new BoolPropertyItem(
                'col_names',
                __(
                    'The first line of the file contains the table column names'
                    . ' <i>(if this is unchecked, the first line will become part'
                    . ' of the data)</i>'
                )
            );
            $generalOptions->addProperty($leaf);
        } else {
            $leaf = new NumberPropertyItem(
                'partial_import',
                __(
                    'Import these many number of rows (optional):'
                )
            );
            $generalOptions->addProperty($leaf);

            $hint = new Message(
                __(
                    'If the data in each row of the file is not'
                    . ' in the same order as in the database, list the corresponding'
                    . ' column names here. Column names must be separated by commas'
                    . ' and not enclosed in quotations.'
                )
            );
            $leaf = new TextPropertyItem(
                'columns',
                __('Column names:') . ' ' . Generator::showHint($hint->getMessage())
            );
            $generalOptions->addProperty($leaf);
        }

        $leaf = new BoolPropertyItem(
            'ignore',
            __('Do not abort on INSERT error')
        );
        $generalOptions->addProperty($leaf);
        $importSpecificOptions->addProperty($generalOptions);
        $importPluginProperties->setOptions($importSpecificOptions);

        return $importPluginProperties;
    }

    /**
     * Handles the whole import logic
     *
     * @param array $sql_data 2-element array with sql data
     */
    public function doImport(?File $importHandle = null, array &$sql_data = []): void
    {
        global $error, $message, $dbi;
        global $db, $table, $csv_terminated, $csv_enclosed, $csv_escaped,
               $csv_new_line, $csv_columns, $errorUrl;
        global $timeout_passed, $finished;

        $replacements = [
            '\\n' => "\n",
            '\\t' => "\t",
            '\\r' => "\r",
        ];
        $csv_terminated = strtr($csv_terminated, $replacements);
        $csv_enclosed = strtr($csv_enclosed, $replacements);
        $csv_escaped = strtr($csv_escaped, $replacements);
        $csv_new_line = strtr($csv_new_line, $replacements);

        [$error, $message] = $this->buildErrorsForParams(
            $csv_terminated,
            $csv_enclosed,
            $csv_escaped,
            $csv_new_line,
            (string) $errorUrl
        );

        [$sql_template, $required_fields, $fields] = $this->getSqlTemplateAndRequiredFields($db, $table, $csv_columns);
        $i = 0;
        $len = 0;
        $lastlen = null;
        $line = 1;
        $lasti = -1;
        $values = [];
        $csv_finish = false;
        $max_lines = 0; // defaults to 0 (get all the lines)

        /**
         * If we get a negative value, probably someone changed min value
         * attribute in DOM or there is an integer overflow, whatever be
         * the case, get all the lines.
         */
        if (isset($_REQUEST['csv_partial_import']) && $_REQUEST['csv_partial_import'] > 0) {
            $max_lines = $_REQUEST['csv_partial_import'];
        }

        $max_lines_constraint = $max_lines + 1;
        if (isset($_REQUEST['csv_col_names'])) {
            $max_lines_constraint++;
        }

        $tempRow = [];
        $rows = [];
        $col_names = [];
        $tables = [];

        $buffer = '';
        $col_count = 0;
        $max_cols = 0;
        $csv_terminated_len = mb_strlen($csv_terminated);
        while (! ($finished && $i >= $len) && ! $error && ! $timeout_passed) {
            $data = $this->import->getNextChunk($importHandle);
            if ($data === false) {
                $GLOBALS['offset'] -= strlen($buffer);
                break;
            }

            if ($data !== true) {
                $buffer .= $data;
                unset($data);
                if ($finished && $buffer) {
                    $finalch = mb_substr($buffer, -1);
                    if ($csv_new_line === 'auto' && $finalch != "\r" && $finalch != "\n") {
                        $buffer .= "\n";
                    } elseif ($csv_new_line !== 'auto' && $finalch != $csv_new_line) {
                        $buffer .= $csv_new_line;
                    }
                }
                if (
                    ($csv_new_line === 'auto'
                    && ! str_contains($buffer, "\r")
                    && ! str_contains($buffer, "\n"))
                    || ($csv_new_line !== 'auto'
                    && ! str_contains($buffer, $csv_new_line))
                ) {
                    continue;
                }
            }
            $len = mb_strlen($buffer);

            $ch = mb_substr($buffer, $i, 1);
            if ($csv_terminated_len > 1 && $ch == $csv_terminated[0]) {
                $ch = $this->readCsvTerminatedString($buffer, $ch, $i, $csv_terminated_len);
                $i += $csv_terminated_len - 1;
            }

            while ($i < $len) {
                if ($lasti == $i && $lastlen == $len) {
                    $message = Message::error(
                        __('Invalid format of CSV input on line %d.')
                    );
                    $message->addParam($line);
                    $error = true;
                    break;
                }

                $lasti = $i;
                $lastlen = $len;
                if (! $csv_finish) {
                    if ($ch == $csv_terminated) {
                        if ($i == $len - 1) {
                            break;
                        }

                        $values[] = '';
                        $i++;
                        $ch = mb_substr($buffer, $i, 1);
                        if ($csv_terminated_len > 1 && $ch == $csv_terminated[0]) {
                            $ch = $this->readCsvTerminatedString($buffer, $ch, $i, $csv_terminated_len);
                            $i += $csv_terminated_len - 1;
                        }

                        continue;
                    }
                    $fallbacki = $i;
                    if ($ch == $csv_enclosed) {
                        if ($i == $len - 1) {
                            break;
                        }

                        $need_end = true;
                        $i++;
                        $ch = mb_substr($buffer, $i, 1);
                        if ($csv_terminated_len > 1 && $ch == $csv_terminated[0]) {
                            $ch = $this->readCsvTerminatedString($buffer, $ch, $i, $csv_terminated_len);
                            $i += $csv_terminated_len - 1;
                        }
                    } else {
                        $need_end = false;
                    }

                    $fail = false;
                    $value = '';
                    while (
                        ($need_end
                            && ($ch != $csv_enclosed
                                || $csv_enclosed == $csv_escaped))
                        || (! $need_end
                            && ! ($ch == $csv_terminated
                                || $ch == $csv_new_line
                                || ($csv_new_line === 'auto'
                                    && ($ch == "\r" || $ch == "\n"))))
                    ) {
                        if ($ch == $csv_escaped) {
                            if ($i == $len - 1) {
                                $fail = true;
                                break;
                            }

                            $i++;
                            $ch = mb_substr($buffer, $i, 1);
                            if ($csv_terminated_len > 1 && $ch == $csv_terminated[0]) {
                                $ch = $this->readCsvTerminatedString($buffer, $ch, $i, $csv_terminated_len);
                                $i += $csv_terminated_len - 1;
                            }

                            if (
                                $csv_enclosed == $csv_escaped
                                && ($ch == $csv_terminated
                                || $ch == $csv_new_line
                                || ($csv_new_line === 'auto'
                                && ($ch == "\r" || $ch == "\n")))
                            ) {
                                break;
                            }
                        }

                        $value .= $ch;
                        if ($i == $len - 1) {
                            if (! $finished) {
                                $fail = true;
                            }

                            break;
                        }

                        $i++;
                        $ch = mb_substr($buffer, $i, 1);
                        if ($csv_terminated_len <= 1 || $ch != $csv_terminated[0]) {
                            continue;
                        }

                        $ch = $this->readCsvTerminatedString($buffer, $ch, $i, $csv_terminated_len);
                        $i += $csv_terminated_len - 1;
                    }
                    if ($need_end === false && $value === 'NULL') {
                        $value = null;
                    }

                    if ($fail) {
                        $i = $fallbacki;
                        $ch = mb_substr($buffer, $i, 1);
                        if ($csv_terminated_len > 1 && $ch == $csv_terminated[0]) {
                            $i += $csv_terminated_len - 1;
                        }

                        break;
                    }
                    if ($need_end && $ch == $csv_enclosed) {
                        if ($finished && $i == $len - 1) {
                            $ch = null;
                        } elseif ($i == $len - 1) {
                            $i = $fallbacki;
                            $ch = mb_substr($buffer, $i, 1);
                            if ($csv_terminated_len > 1 && $ch == $csv_terminated[0]) {
                                $i += $csv_terminated_len - 1;
                            }

                            break;
                        } else {
                            $i++;
                            $ch = mb_substr($buffer, $i, 1);
                            if ($csv_terminated_len > 1 && $ch == $csv_terminated[0]) {
                                $ch = $this->readCsvTerminatedString($buffer, $ch, $i, $csv_terminated_len);
                                $i += $csv_terminated_len - 1;
                            }
                        }
                    }
                    if (
                        $ch == $csv_new_line
                        || ($csv_new_line === 'auto' && ($ch == "\r" || $ch == "\n"))
                        || ($finished && $i == $len - 1)
                    ) {
                        $csv_finish = true;
                    }
                    if ($ch == $csv_terminated) {
                        if ($i == $len - 1) {
                            $i = $fallbacki;
                            $ch = mb_substr($buffer, $i, 1);
                            if ($csv_terminated_len > 1 && $ch == $csv_terminated[0]) {
                                $i += $csv_terminated_len - 1;
                            }

                            break;
                        }

                        $i++;
                        $ch = mb_substr($buffer, $i, 1);
                        if ($csv_terminated_len > 1 && $ch == $csv_terminated[0]) {
                            $ch = $this->readCsvTerminatedString($buffer, $ch, $i, $csv_terminated_len);
                            $i += $csv_terminated_len - 1;
                        }
                    }
                    $values[] = $value;
                }
                if (
                    ! $csv_finish
                    && $ch != $csv_new_line
                    && ($csv_new_line !== 'auto' || ($ch != "\r" && $ch != "\n"))
                ) {
                    continue;
                }

                if ($csv_new_line === 'auto' && $ch == "\r") { // Handle "\r\n"
                    if ($i >= ($len - 2) && ! $finished) {
                        break; // We need more data to decide new line
                    }

                    if (mb_substr($buffer, $i + 1, 1) == "\n") {
                        $i++;
                    }
                }
                if (! $csv_finish) {
                    $values[] = '';
                }

                if ($this->getAnalyze()) {
                    foreach ($values as $val) {
                        $tempRow[] = $val;
                        ++$col_count;
                    }

                    if ($col_count > $max_cols) {
                        $max_cols = $col_count;
                    }

                    $col_count = 0;

                    $rows[] = $tempRow;
                    $tempRow = [];
                } else {
                    if (count($values) != $required_fields) {
                        if ($values[count($values) - 1] !== ';') {
                            $message = Message::error(
                                __(
                                    'Invalid column count in CSV input on line %d.'
                                )
                            );
                            $message->addParam($line);
                            $error = true;
                            break;
                        }

                        unset($values[count($values) - 1]);
                    }

                    $first = true;
                    $sql = $sql_template;
                    foreach ($values as $val) {
                        if (! $first) {
                            $sql .= ', ';
                        }

                        if ($val === null) {
                            $sql .= 'NULL';
                        } else {
                            $sql .= '\''
                                . $dbi->escapeString($val)
                                . '\'';
                        }

                        $first = false;
                    }

                    $sql .= ')';
                    if (isset($_POST['csv_replace'])) {
                        $sql .= ' ON DUPLICATE KEY UPDATE ';
                        foreach ($fields as $field) {
                            $fieldName = Util::backquote($field['Field']);
                            $sql .= $fieldName . ' = VALUES(' . $fieldName
                                . '), ';
                        }

                        $sql = rtrim($sql, ', ');
                    }

                    /**
                     * @todo maybe we could add original line to verbose
                     * SQL in comment
                     */
                    $this->import->runQuery($sql, $sql, $sql_data);
                }

                $line++;
                $csv_finish = false;
                $values = [];
                $buffer = mb_substr($buffer, $i + 1);
                $len = mb_strlen($buffer);
                $i = 0;
                $lasti = -1;
                $ch = mb_substr($buffer, 0, 1);
                if ($max_lines > 0 && $line == $max_lines_constraint) {
                    $finished = 1;
                    break;
                }
            }

            if ($max_lines > 0 && $line == $max_lines_constraint) {
                $finished = 1;
                break;
            }
        }

        if ($this->getAnalyze()) {
            
            $num_rows = count($rows);
            for ($i = 0; $i < $num_rows; ++$i) {
                for ($j = count($rows[$i]); $j < $max_cols; ++$j) {
                    $rows[$i][] = 'NULL';
                }
            }

            $col_names = $this->getColumnNames($col_names, $max_cols, $rows);

            
            if (isset($_REQUEST['csv_col_names'])) {
                array_shift($rows);
            }

            $tbl_name = $this->getTableNameFromImport((string) $db);

            $tables[] = [
                $tbl_name,
                $col_names,
                $rows,
            ];

            
            $analyses = [];
            $analyses[] = $this->import->analyzeTable($tables[0]);

            /**
             * string $db_name (no backquotes)
             *
             * array $table = array(table_name, array() column_names, array()() rows)
             * array $tables = array of "$table"s
             *
             * array $analysis = array(array() column_types, array() column_sizes)
             * array $analyses = array of "$analysis"s
             *
             * array $create = array of SQL strings
             *
             * array $options = an associative array of options
             */

            /* Set database name to the currently selected one, if applicable,
             * Otherwise, check if user provided the database name in the request,
             * if not, set the default name
             */
            if (isset($_REQUEST['csv_new_db_name']) && strlen($_REQUEST['csv_new_db_name']) > 0) {
                $newDb = $_REQUEST['csv_new_db_name'];
            } else {
                $result = $dbi->fetchResult('SHOW DATABASES');

                $newDb = 'CSV_DB ' . (count($result) + 1);
            }

            [$db_name, $options] = $this->getDbnameAndOptions($db, $newDb);

            
            $create = null;

            
            $this->import->buildSql($db_name, $tables, $analyses, $create, $options, $sql_data);

            unset($tables, $analyses);
        }
        $this->import->runQuery('', '', $sql_data);

        if (count($values) == 0 || $error !== false) {
            return;
        }

        $message = Message::error(
            __('Invalid format of CSV input on line %d.')
        );
        $message->addParam($line);
        $error = true;
    }

    private function buildErrorsForParams(
        string $csvTerminated,
        string $csvEnclosed,
        string $csvEscaped,
        string $csvNewLine,
        string $errUrl
    ): array {
        global $error, $message;

        $param_error = false;
        if (strlen($csvTerminated) === 0) {
            $message = Message::error(
                __('Invalid parameter for CSV import: %s')
            );
            $message->addParam(__('Columns terminated with'));
            $error = true;
            $param_error = true;
        } elseif (mb_strlen($csvEnclosed) > 1) {
            $message = Message::error(
                __('Invalid parameter for CSV import: %s')
            );
            $message->addParam(__('Columns enclosed with'));
            $error = true;
            $param_error = true;
        } elseif (mb_strlen($csvEscaped) > 1) {
            $message = Message::error(
                __('Invalid parameter for CSV import: %s')
            );
            $message->addParam(__('Columns escaped with'));
            $error = true;
            $param_error = true;
        } elseif (mb_strlen($csvNewLine) != 1 && $csvNewLine !== 'auto') {
            $message = Message::error(
                __('Invalid parameter for CSV import: %s')
            );
            $message->addParam(__('Lines terminated with'));
            $error = true;
            $param_error = true;
        }
        if ($param_error) {
            Generator::mysqlDie(
                $message->getMessage(),
                '',
                false,
                $errUrl
            );
        }

        return [$error, $message];
    }

    private function getTableNameFromImport(string $databaseName): string
    {
        global $import_file_name, $dbi;

        $importFileName = basename($import_file_name, '.csv');
        $importFileName = mb_strtolower($importFileName);
        $importFileName = (string) preg_replace('/[^a-zA-Z0-9_]/', '_', $importFileName);
        if (isset($_REQUEST['csv_new_tbl_name']) && strlen($_REQUEST['csv_new_tbl_name']) > 0) {
            return $_REQUEST['csv_new_tbl_name'];
        }

        if (mb_strlen($databaseName)) {
            $result = $dbi->fetchResult('SHOW TABLES');
            if (count($result) === 0) {
                return $importFileName;
            }
            $nameArray = preg_grep('/^' . $importFileName . '$/isU', $result);
            if ($nameArray === false || count($nameArray) === 0) {
                return $importFileName;
            }
            $nameArray = preg_grep('/^' . $importFileName . '_$/isU', $result);
            if ($nameArray === false) {
                return $importFileName;
            }
            $nameArray = preg_grep('/^' . $importFileName . '_/isU', $result);

            return $importFileName . '_' . (count($nameArray) + 1);
        }

        return $importFileName;
    }

    private function getColumnNames(array $columnNames, int $maxCols, array $rows): array
    {
        if (isset($_REQUEST['csv_col_names'])) {
            $columnNames = array_splice($rows, 0, 1);
            $columnNames = $columnNames[0];
            foreach ($columnNames as $key => $col_name) {
                $columnNames[$key] = rtrim($col_name);
            }
        }

        if ((isset($columnNames) && count($columnNames) != $maxCols) || ! isset($columnNames)) {
            for ($i = 0; $i < $maxCols; ++$i) {
                $columnNames[] = 'COL ' . ($i + 1);
            }
        }

        return $columnNames;
    }

    private function getSqlTemplateAndRequiredFields(
        ?string $db,
        ?string $table,
        ?string $csvColumns
    ): array {
        global $dbi, $error, $message;

        $requiredFields = 0;
        $sqlTemplate = '';
        $fields = [];
        if (! $this->getAnalyze() && $db !== null && $table !== null) {
            $sqlTemplate = 'INSERT';
            if (isset($_POST['csv_ignore'])) {
                $sqlTemplate .= ' IGNORE';
            }

            $sqlTemplate .= ' INTO ' . Util::backquote($table);

            $tmp_fields = $dbi->getColumns($db, $table);

            if (empty($csvColumns)) {
                $fields = $tmp_fields;
            } else {
                $sqlTemplate .= ' (';
                $fields = [];
                $tmp = preg_split('/,( ?)/', $csvColumns);
                if ($tmp === false) {
                    $tmp = [];
                }

                foreach ($tmp as $val) {
                    if (count($fields) > 0) {
                        $sqlTemplate .= ', ';
                    }

                    
                    $val = trim($val, " \t\r\n\0\x0B`");
                    $found = false;
                    foreach ($tmp_fields as $field) {
                        if ($field['Field'] == $val) {
                            $found = true;
                            break;
                        }
                    }

                    if (! $found) {
                        $message = Message::error(
                            __(
                                'Invalid column (%s) specified! Ensure that columns'
                                . ' names are spelled correctly, separated by commas'
                                . ', and not enclosed in quotes.'
                            )
                        );
                        $message->addParam($val);
                        $error = true;
                        break;
                    }

                    if (isset($field)) {
                        $fields[] = $field;
                    }

                    $sqlTemplate .= Util::backquote($val);
                }

                $sqlTemplate .= ') ';
            }

            $requiredFields = count($fields);

            $sqlTemplate .= ' VALUES (';
        }

        return [$sqlTemplate, $requiredFields, $fields];
    }

    /**
     * Read the expected column_separated_with String of length
     * $csv_terminated_len from the $buffer
     * into variable $ch and return the read string $ch
     *
     * @param string $buffer             The original string buffer read from
     *                                   csv file
     * @param string $ch                 Partially read "column Separated with"
     *                                   string, also used to return after
     *                                   reading length equal $csv_terminated_len
     * @param int    $i                  Current read counter of buffer string
     * @param int    $csv_terminated_len The length of "column separated with"
     *                                   String
     *
     * @return string
     */
    public function readCsvTerminatedString($buffer, $ch, $i, $csv_terminated_len)
    {
        for ($j = 0; $j < $csv_terminated_len - 1; $j++) {
            $i++;
            $ch .= mb_substr($buffer, $i, 1);
        }

        return $ch;
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
}
