<?php
/**
 * Set of functions used to build dumps of tables as PHP Arrays
 */

declare(strict_types=1);

namespace PhpMyAdmin\Plugins\Export;

use PhpMyAdmin\DatabaseInterface;
use PhpMyAdmin\Plugins\ExportPlugin;
use PhpMyAdmin\Properties\Options\Groups\OptionsPropertyMainGroup;
use PhpMyAdmin\Properties\Options\Groups\OptionsPropertyRootGroup;
use PhpMyAdmin\Properties\Options\Items\HiddenPropertyItem;
use PhpMyAdmin\Properties\Plugins\ExportPluginProperties;
use PhpMyAdmin\Util;
use PhpMyAdmin\Version;

use function __;
use function preg_match;
use function preg_replace;
use function stripslashes;
use function strtr;
use function var_export;

/**
 * Handles the export for the PHP Array class
 */
class ExportPhparray extends ExportPlugin
{
    /**
     * @psalm-return non-empty-lowercase-string
     */
    public function getName(): string
    {
        return 'phparray';
    }

    protected function setProperties(): ExportPluginProperties
    {
        $exportPluginProperties = new ExportPluginProperties();
        $exportPluginProperties->setText('PHP array');
        $exportPluginProperties->setExtension('php');
        $exportPluginProperties->setMimeType('text/plain');
        $exportPluginProperties->setOptionsText(__('Options'));
        $exportSpecificOptions = new OptionsPropertyRootGroup('Format Specific Options');
        $generalOptions = new OptionsPropertyMainGroup('general_opts');
        $leaf = new HiddenPropertyItem('structure_or_data');
        $generalOptions->addProperty($leaf);
        $exportSpecificOptions->addProperty($generalOptions);
        $exportPluginProperties->setOptions($exportSpecificOptions);

        return $exportPluginProperties;
    }

    /**
     * Removes end of comment from a string
     *
     * @param string $string String to replace
     *
     * @return string
     */
    public function commentString($string)
    {
        return strtr($string, '*/', '-');
    }

    /**
     * Outputs export header
     */
    public function exportHeader(): bool
    {
        $this->export->outputHandler(
            '<?php' . $GLOBALS['crlf']
            . '/**' . $GLOBALS['crlf']
            . ' * Export to PHP Array plugin for phpMyAdmin' . $GLOBALS['crlf']
            . ' * @version ' . Version::VERSION . $GLOBALS['crlf']
            . ' */' . $GLOBALS['crlf'] . $GLOBALS['crlf']
        );

        return true;
    }

    /**
     * Outputs export footer
     */
    public function exportFooter(): bool
    {
        return true;
    }

    /**
     * Outputs database header
     *
     * @param string $db      Database name
     * @param string $dbAlias Aliases of db
     */
    public function exportDBHeader($db, $dbAlias = ''): bool
    {
        if (empty($dbAlias)) {
            $dbAlias = $db;
        }

        $this->export->outputHandler(
            '/**' . $GLOBALS['crlf']
            . ' * Database ' . $this->commentString(Util::backquote($dbAlias))
            . $GLOBALS['crlf'] . ' */' . $GLOBALS['crlf']
        );

        return true;
    }

    /**
     * Outputs database footer
     *
     * @param string $db Database name
     */
    public function exportDBFooter($db): bool
    {
        return true;
    }

    /**
     * Outputs CREATE DATABASE statement
     *
     * @param string $db         Database name
     * @param string $exportType 'server', 'database', 'table'
     * @param string $dbAlias    Aliases of db
     */
    public function exportDBCreate($db, $exportType, $dbAlias = ''): bool
    {
        return true;
    }

    /**
     * Outputs the content of a table in PHP array format
     *
     * @param string $db       database name
     * @param string $table    table name
     * @param string $crlf     the end of line sequence
     * @param string $errorUrl the url to go back in case of error
     * @param string $sqlQuery SQL query for obtaining data
     * @param array  $aliases  Aliases of db/table/columns
     */
    public function exportData(
        $db,
        $table,
        $crlf,
        $errorUrl,
        $sqlQuery,
        array $aliases = []
    ): bool {
        global $dbi;

        $db_alias = $db;
        $table_alias = $table;
        $this->initAlias($aliases, $db_alias, $table_alias);

        $result = $dbi->query($sqlQuery, DatabaseInterface::CONNECT_USER, DatabaseInterface::QUERY_UNBUFFERED);

        $columns_cnt = $result->numFields();
        $columns = [];
        foreach ($result->getFieldNames() as $i => $col_as) {
            if (! empty($aliases[$db]['tables'][$table]['columns'][$col_as])) {
                $col_as = $aliases[$db]['tables'][$table]['columns'][$col_as];
            }

            $columns[$i] = stripslashes($col_as);
        }

        $tablefixed = $table;
        if (! preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $table_alias)) {
            $tablefixed = preg_replace('/[^a-zA-Z0-9_\x7f-\xff]/', '_', $table_alias);
            if (preg_match('/^[a-zA-Z_\x7f-\xff]/', $tablefixed) === 0) {
                $tablefixed = '_' . $tablefixed;
            }
        }

        $buffer = '';
        $record_cnt = 0;
        $buffer .= $crlf . '/* '
            . $this->commentString(Util::backquote($db_alias)) . '.'
            . $this->commentString(Util::backquote($table_alias)) . ' */' . $crlf;
        $buffer .= '$' . $tablefixed . ' = array(';
        if (! $this->export->outputHandler($buffer)) {
            return false;
        }
        $buffer = '';
        while ($record = $result->fetchRow()) {
            $record_cnt++;

            if ($record_cnt == 1) {
                $buffer .= $crlf . '  array(';
            } else {
                $buffer .= ',' . $crlf . '  array(';
            }

            for ($i = 0; $i < $columns_cnt; $i++) {
                $buffer .= var_export($columns[$i], true)
                    . ' => ' . var_export($record[$i], true)
                    . ($i + 1 >= $columns_cnt ? '' : ',');
            }

            $buffer .= ')';
            if (! $this->export->outputHandler($buffer)) {
                return false;
            }
            $buffer = '';
        }

        $buffer .= $crlf . ');' . $crlf;

        return $this->export->outputHandler($buffer);
    }

    /**
     * Outputs result of raw query as PHP array
     *
     * @param string      $errorUrl the url to go back in case of error
     * @param string|null $db       the database where the query is executed
     * @param string      $sqlQuery the rawquery to output
     * @param string      $crlf     the end of line sequence
     */
    public function exportRawQuery(string $errorUrl, ?string $db, string $sqlQuery, string $crlf): bool
    {
        global $dbi;

        if ($db !== null) {
            $dbi->selectDb($db);
        }

        return $this->exportData($db ?? '', '', $crlf, $errorUrl, $sqlQuery);
    }
}
