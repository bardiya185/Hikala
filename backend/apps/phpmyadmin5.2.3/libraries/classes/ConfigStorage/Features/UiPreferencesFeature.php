<?php

declare(strict_types=1);

namespace PhpMyAdmin\ConfigStorage\Features;

use PhpMyAdmin\Dbal\DatabaseName;
use PhpMyAdmin\Dbal\TableName;

/**
 * @psalm-immutable
 */
final class UiPreferencesFeature
{
    
    public $database;

    
    public $tableUiPrefs;

    public function __construct(DatabaseName $database, TableName $tableUiPrefs)
    {
        $this->database = $database;
        $this->tableUiPrefs = $tableUiPrefs;
    }
}
