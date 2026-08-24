<?php

declare(strict_types=1);

namespace PhpMyAdmin\Table;

use PhpMyAdmin\DatabaseInterface;
use PhpMyAdmin\Util;
use PhpMyAdmin\Utils\Gis;

use function count;
use function explode;
use function implode;
use function in_array;
use function is_array;
use function mb_strpos;
use function preg_match;
use function str_contains;
use function str_replace;
use function strlen;
use function strncasecmp;
use function trim;

final class Search
{
    
    private $dbi;

    public function __construct(DatabaseInterface $dbi)
    {
        $this->dbi = $dbi;
    }

    /**
     * Builds the sql search query from the post parameters
     *
     * @return string the generated SQL query
     */
    public function buildSqlQuery(): string
    {
        $sql_query = 'SELECT ';
        $is_distinct = isset($_POST['distinct']) ? 'true' : 'false';
        if ($is_distinct === 'true') {
            $sql_query .= 'DISTINCT ';
        }
        if (isset($_POST['zoom_submit']) || ! empty($_POST['displayAllColumns'])) {
            $sql_query .= '*';
        } else {
            $columnsToDisplay = $_POST['columnsToDisplay'];
            $quotedColumns = [];
            foreach ($columnsToDisplay as $column) {
                $quotedColumns[] = Util::backquote($column);
            }

            $sql_query .= implode(', ', $quotedColumns);
        }

        $sql_query .= ' FROM '
            . Util::backquote($_POST['table']);
        $whereClause = $this->generateWhereClause();
        $sql_query .= $whereClause;
        if (isset($_POST['orderByColumn']) && $_POST['orderByColumn'] !== '--nil--') {
            $sql_query .= ' ORDER BY '
                . Util::backquote($_POST['orderByColumn'])
                . ' ' . $_POST['order'];
        }

        return $sql_query;
    }

    /**
     * Generates the where clause for the SQL search query to be executed
     *
     * @return string the generated where clause
     */
    private function generateWhereClause(): string
    {
        if (isset($_POST['customWhereClause']) && trim($_POST['customWhereClause']) != '') {
            return ' WHERE ' . $_POST['customWhereClause'];
        }
        if (
            ! isset($_POST['criteriaColumnOperators'])
            || (
                ! isset($_POST['criteriaValues'])
                && ! isset($_POST['criteriaColumnOperators'])
                && ! isset($_POST['geom_func'])
            )
        ) {
            return '';
        }
        $fullWhereClause = [];
        foreach ($_POST['criteriaColumnOperators'] as $column_index => $operator) {
            $unaryFlag = $this->dbi->types->isUnaryOperator($operator);
            $tmp_geom_func = $_POST['geom_func'][$column_index] ?? null;

            $whereClause = $this->getWhereClause(
                $_POST['criteriaValues'][$column_index],
                $_POST['criteriaColumnNames'][$column_index],
                $_POST['criteriaColumnTypes'][$column_index],
                $operator,
                $unaryFlag,
                $tmp_geom_func
            );

            if (! $whereClause) {
                continue;
            }

            $fullWhereClause[] = $whereClause;
        }

        if (! empty($fullWhereClause)) {
            return ' WHERE ' . implode(' AND ', $fullWhereClause);
        }

        return '';
    }

    /**
     * Return the where clause for query generation based on the inputs provided.
     *
     * @param mixed       $criteriaValues Search criteria input
     * @param string      $names          Name of the column on which search is submitted
     * @param string      $types          Type of the field
     * @param string      $func_type      Search function/operator
     * @param bool        $unaryFlag      Whether operator unary or not
     * @param string|null $geom_func      Whether geometry functions should be applied
     *
     * @return string generated where clause.
     */
    private function getWhereClause(
        $criteriaValues,
        $names,
        $types,
        $func_type,
        $unaryFlag,
        $geom_func = null
    ): string {
        if (! empty($geom_func)) {
            return $this->getGeomWhereClause($criteriaValues, $names, $func_type, $types, $geom_func);
        }

        $backquoted_name = Util::backquote($names);
        $where = '';
        if ($unaryFlag) {
            $where = $backquoted_name . ' ' . $func_type;
        } elseif (strncasecmp($types, 'enum', 4) == 0 && ! empty($criteriaValues)) {
            $where = $backquoted_name;
            $where .= $this->getEnumWhereClause($criteriaValues, $func_type);
        } elseif ($criteriaValues != '') {
            if (
                preg_match('@char|binary|blob|text|set|date|time|year|uuid@i', $types)
                || mb_strpos(' ' . $func_type, 'LIKE')
            ) {
                $quot = '\'';
            } else {
                $quot = '';
            }
            if ($func_type === 'LIKE %...%') {
                $func_type = 'LIKE';
                $criteriaValues = '%' . $criteriaValues . '%';
            }

            if ($func_type === 'NOT LIKE %...%') {
                $func_type = 'NOT LIKE';
                $criteriaValues = '%' . $criteriaValues . '%';
            }

            if ($func_type === 'REGEXP ^...$') {
                $func_type = 'REGEXP';
                $criteriaValues = '^' . $criteriaValues . '$';
            }

            if (
                $func_type !== 'IN (...)'
                && $func_type !== 'NOT IN (...)'
                && $func_type !== 'BETWEEN'
                && $func_type !== 'NOT BETWEEN'
            ) {
                return $backquoted_name . ' ' . $func_type . ' ' . $quot
                    . $this->dbi->escapeString($criteriaValues) . $quot;
            }

            $func_type = str_replace(' (...)', '', $func_type);
            if (is_array($criteriaValues)) {
                $values = $criteriaValues;
            } else {
                $values = explode(',', $criteriaValues);
            }
            $emptyKey = false;
            foreach ($values as $key => &$value) {
                if ($value === '') {
                    $emptyKey = $key;
                    $value = 'NULL';
                    continue;
                }

                $value = $quot . $this->dbi->escapeString(trim($value))
                    . $quot;
            }

            if ($func_type === 'BETWEEN' || $func_type === 'NOT BETWEEN') {
                $where = $backquoted_name . ' ' . $func_type . ' '
                    . ($values[0] ?? '')
                    . ' AND ' . ($values[1] ?? '');
            } else { //[NOT] IN
                if ($emptyKey !== false) {
                    unset($values[$emptyKey]);
                }

                $wheres = [];
                if (! empty($values)) {
                    $wheres[] = $backquoted_name . ' ' . $func_type
                        . ' (' . implode(',', $values) . ')';
                }

                if ($emptyKey !== false) {
                    $wheres[] = $backquoted_name . ' IS NULL';
                }

                $where = implode(' OR ', $wheres);
                if (1 < count($wheres)) {
                    $where = '(' . $where . ')';
                }
            }
        }

        return $where;
    }

    /**
     * Return the where clause for a geometrical column.
     *
     * @param mixed       $criteriaValues Search criteria input
     * @param string      $names          Name of the column on which search is submitted
     * @param string      $func_type      Search function/operator
     * @param string      $types          Type of the field
     * @param string|null $geom_func      Whether geometry functions should be applied
     *
     * @return string part of where clause.
     */
    private function getGeomWhereClause(
        $criteriaValues,
        $names,
        $func_type,
        $types,
        $geom_func = null
    ): string {
        $geom_unary_functions = [
            'IsEmpty' => 1,
            'IsSimple' => 1,
            'IsRing' => 1,
            'IsClosed' => 1,
        ];
        $where = '';
        $geom_funcs = Gis::getFunctions($types, true, false);
        if (str_contains($func_type, 'IS NULL') || str_contains($func_type, 'IS NOT NULL')) {
            return Util::backquote($names) . ' ' . $func_type;
        }

        if ($geom_funcs[$geom_func]['params'] > 1) {
            $gis_data = Gis::createData($criteriaValues, $this->dbi->getVersion());

            return $geom_func . '(' . Util::backquote($names)
                . ', ' . $gis_data . ')';
        }
        $type = $geom_funcs[$geom_func]['type'];
        $geom_function_applied = $geom_func
            . '(' . Util::backquote($names) . ')';
        if (isset($geom_unary_functions[$geom_func]) && trim($criteriaValues) == '') {
            $where = $geom_function_applied;
        } elseif (in_array($type, Gis::getDataTypes()) && ! empty($criteriaValues)) {
            $gis_data = Gis::createData($criteriaValues, $this->dbi->getVersion());
            $where = $geom_function_applied . ' ' . $func_type . ' ' . $gis_data;
        } elseif (strlen($criteriaValues) > 0) {
            $where = $geom_function_applied . ' '
                . $func_type . " '" . $criteriaValues . "'";
        }

        return $where;
    }

    /**
     * Return the where clause in case column's type is ENUM.
     *
     * @param mixed  $criteriaValues Search criteria input
     * @param string $func_type      Search function/operator
     *
     * @return string part of where clause.
     */
    private function getEnumWhereClause($criteriaValues, $func_type): string
    {
        if (! is_array($criteriaValues)) {
            $criteriaValues = explode(',', $criteriaValues);
        }

        $enum_selected_count = count($criteriaValues);
        if ($func_type === '=' && $enum_selected_count > 1) {
            $func_type = 'IN';
            $parens_open = '(';
            $parens_close = ')';
        } elseif ($func_type === '!=' && $enum_selected_count > 1) {
            $func_type = 'NOT IN';
            $parens_open = '(';
            $parens_close = ')';
        } else {
            $parens_open = '';
            $parens_close = '';
        }

        $enum_where = '\''
            . $this->dbi->escapeString($criteriaValues[0]) . '\'';
        for ($e = 1; $e < $enum_selected_count; $e++) {
            $enum_where .= ', \''
                . $this->dbi->escapeString($criteriaValues[$e]) . '\'';
        }

        return ' ' . $func_type . ' ' . $parens_open
            . $enum_where . $parens_close;
    }
}
