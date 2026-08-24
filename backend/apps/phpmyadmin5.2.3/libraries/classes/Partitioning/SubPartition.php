<?php
/**
 * Library for extracting information about the sub-partitions
 */

declare(strict_types=1);

namespace PhpMyAdmin\Partitioning;

/**
 * Represents a sub partition of a table
 */
class SubPartition
{
    
    protected $db;
    
    protected $table;
    
    protected $name;
    
    protected $ordinal;
    
    protected $method;
    
    protected $expression;
    
    protected $rows;
    
    protected $dataLength;
    
    protected $indexLength;
    
    protected $comment;

    /**
     * Constructs a partition
     *
     * @param array $row fetched row from information_schema.PARTITIONS
     */
    public function __construct(array $row)
    {
        $this->db = $row['TABLE_SCHEMA'];
        $this->table = $row['TABLE_NAME'];
        $this->loadData($row);
    }

    /**
     * Loads data from the fetched row from information_schema.PARTITIONS
     *
     * @param array $row fetched row
     */
    protected function loadData(array $row): void
    {
        $this->name = $row['SUBPARTITION_NAME'];
        $this->ordinal = $row['SUBPARTITION_ORDINAL_POSITION'];
        $this->method = $row['SUBPARTITION_METHOD'];
        $this->expression = $row['SUBPARTITION_EXPRESSION'];
        $this->loadCommonData($row);
    }

    /**
     * Loads some data that is common to both partitions and sub partitions
     *
     * @param array $row fetched row
     */
    protected function loadCommonData(array $row): void
    {
        $this->rows = $row['TABLE_ROWS'];
        $this->dataLength = $row['DATA_LENGTH'];
        $this->indexLength = $row['INDEX_LENGTH'];
        $this->comment = $row['PARTITION_COMMENT'];
    }

    /**
     * Return the partition name
     *
     * @return string partition name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Return the ordinal of the partition
     *
     * @return int the ordinal
     */
    public function getOrdinal()
    {
        return $this->ordinal;
    }

    /**
     * Returns the partition method
     *
     * @return string partition method
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Returns the partition expression
     *
     * @return string partition expression
     */
    public function getExpression()
    {
        return $this->expression;
    }

    /**
     * Returns the number of data rows
     *
     * @return int number of rows
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * Returns the data length
     *
     * @return int data length
     */
    public function getDataLength()
    {
        return $this->dataLength;
    }

    /**
     * Returns the index length
     *
     * @return int index length
     */
    public function getIndexLength()
    {
        return $this->indexLength;
    }

    /**
     * Returns the partition comment
     *
     * @return string partition comment
     */
    public function getComment()
    {
        return $this->comment;
    }
}
