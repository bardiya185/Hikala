# Export plugin creation

This directory holds export plugins for phpMyAdmin. Any new plugin should
basically follow the structure presented here. Official plugins need to
have str* messages with their definition in language files, but if you build
some plugins for your use, you can directly use texts in plugin.

```php
<?php
/**
 * [Name] export plugin for phpMyAdmin
 */

declare(strict_types=1);

/**
 * Handles the export for the [Name] format
 */
class Export[Name] extends PhpMyAdmin\Plugins\ExportPlugin
{
    /**
     * optional - declare variables and descriptions
     *
     * @var VariableType
     */
    private $myOptionalVariable;

    /**
     * optional - declare global variables and descriptions
     *
     * @var VariableType
     */
    private $globalVariableName;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setProperties();
    }

    /**
     * Initialize the local variables that are used specific for export SQL
     *
     * @return void
     *
     * @global VariableType $global_variable_name
     * [..]
     */
    protected function initSpecificVariables()
    {
        global $global_variable_name;
        $this->setGlobalVariableName($global_variable_name);
    }

    /**
     * Sets the export plugin properties.
     * Called in the constructor.
     *
     * @return void
     */
    protected function setProperties()
    {
        $exportPluginProperties = new PhpMyAdmin\Properties\Plugins\ExportPluginProperties();
        $exportPluginProperties->setText('[name]');             // the name of your plug-in
        $exportPluginProperties->setExtension('[ext]');         // extension this plug-in can handle
        $exportPluginProperties->setOptionsText(__('Options'));
        $exportSpecificOptions = new PhpMyAdmin\Properties\Options\Groups\OptionsPropertyRootGroup(
            'Format Specific Options'
        );
        $generalOptions = new PhpMyAdmin\Properties\Options\Groups\OptionsPropertyMainGroup(
            'general_opts'
        );
        $leaf = new PhpMyAdmin\Properties\Options\Items\RadioPropertyItem(
            'structure_or_data'
        );
        $leaf->setValues(
            [
                'structure' => __('structure'),
                'data' => __('data'),
                'structure_and_data' => __('structure and data'),
            ]
        );
        $generalOptions->addProperty($leaf);
        $exportSpecificOptions->addProperty($generalOptions);
        $exportPluginProperties->setOptions($exportSpecificOptions);
        $this->properties = $exportPluginProperties;
    }

    /**
     * Outputs export header
     *
     * @return bool Whether it succeeded
     */
    public function exportHeader()
    {
        return true;
    }

    /**
     * Outputs export footer
     *
     * @return bool Whether it succeeded
     */
    public function exportFooter()
    {
        return true;
    }

    /**
     * Outputs database header
     *
     * @param string $db      Database name
     * @param string $dbAlias Aliases of db
     *
     * @return bool Whether it succeeded
     */
    public function exportDBHeader($db, $dbAlias = '')
    {
        return true;
    }

    /**
     * Outputs database footer
     *
     * @param string $db Database name
     *
     * @return bool Whether it succeeded
     */
    public function exportDBFooter($db)
    {
        return true;
    }

    /**
     * Outputs CREATE DATABASE statement
     *
     * @param string $db         Database name
     * @param string $exportType 'server', 'database', 'table'
     * @param string $dbAlias    Aliases of db
     *
     * @return bool Whether it succeeded
     */
    public function exportDBCreate($db, $exportType, $dbAlias = '')
    {
        return true;
    }

    /**
     * Outputs the content of a table in [Name] format
     *
     * @param string $db       database name
     * @param string $table    table name
     * @param string $crlf     the end of line sequence
     * @param string $errorUrl the url to go back in case of error
     * @param string $sqlQuery SQL query for obtaining data
     * @param array  $aliases  Aliases of db/table/columns
     *
     * @return bool             Whether it succeeded
     */
    public function exportData(
        $db,
        $table,
        $crlf,
        $errorUrl,
        $sqlQuery,
        array $aliases = []
    ) {
        return true;
    }
    

    /**
     * Getter description
     */
    private function getMyOptionalVariable(): VariableType
    {
        return $this->myOptionalVariable;
    }

    /**
     * Setter description
     */
    private function setMyOptionalVariable(VariableType $my_optional_variable): void
    {
        $this->myOptionalVariable = $my_optional_variable;
    }

    /**
     * Getter description
     */
    private function getGlobalVariableName(): VariableType
    {
        return $this->globalVariableName;
    }

    /**
     * Setter description
     */
    private function setGlobalVariableName(VariableType $global_variable_name): void
    {
        $this->globalVariableName = $global_variable_name;
    }
}
```
