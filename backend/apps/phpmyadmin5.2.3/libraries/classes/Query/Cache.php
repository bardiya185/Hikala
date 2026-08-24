<?php

declare(strict_types=1);

namespace PhpMyAdmin\Query;

use PhpMyAdmin\Util;

use function array_shift;
use function count;
use function is_array;

/**
 * Handles caching results
 */
class Cache
{
    
    private $tableCache = [];

    /**
     * Caches table data so Table does not require to issue
     * SHOW TABLE STATUS again
     *
     * @param mixed[][] $tables information for tables of some databases
     */
    public function cacheTableData(string $database, array $tables): void
    {

        if (isset($this->tableCache[$database])) {
            $this->tableCache[$database] = $tables + $this->tableCache[$database];
        } else {
            $this->tableCache[$database] = $tables;
        }
    }

    /**
     * Set an item in table cache using dot notation.
     *
     * @param array|null $contentPath Array with the target path
     * @param mixed      $value       Target value
     */
    public function cacheTableContent(?array $contentPath, $value): void
    {
        $loc = &$this->tableCache;

        if (! isset($contentPath)) {
            $loc = $value;

            return;
        }

        while (count($contentPath) > 1) {
            $key = array_shift($contentPath);
            if (! isset($loc[$key]) || ! is_array($loc[$key])) {
                $loc[$key] = [];
            }

            $loc = &$loc[$key];
        }

        $loc[array_shift($contentPath)] = $value;
    }

    /**
     * Get a cached value from table cache.
     *
     * @param array $contentPath Array of the name of the target value
     * @param mixed $default     Return value on cache miss
     *
     * @return mixed cached value or default
     */
    public function getCachedTableContent(array $contentPath, $default = null)
    {
        return Util::getValueByKey($this->tableCache, $contentPath, $default);
    }

    public function getCache(): array
    {
        return $this->tableCache;
    }

    public function clearTableCache(): void
    {
        $this->tableCache = [];
    }
}
