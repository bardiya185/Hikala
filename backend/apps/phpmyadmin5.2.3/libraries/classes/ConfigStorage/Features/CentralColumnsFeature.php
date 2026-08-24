<?php

declare(strict_types=1);

namespace PhpMyAdmin\ConfigStorage\Features;

use PhpMyAdmin\Dbal\DatabaseName;
use PhpMyAdmin\Dbal\TableName;

/**
 * @psalm-immutable
 */
final class CentralColumnsFeature
{
    
    public $database;

    
    public $centralColumns;

    public function __construct(DatabaseName $database, TableName $centralColumns)
    {
        $this->database = $database;
        $this->centralColumns = $centralColumns;
    }
}
