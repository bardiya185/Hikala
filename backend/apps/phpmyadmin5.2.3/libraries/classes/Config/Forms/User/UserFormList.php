<?php
/**
 * User preferences form
 */

declare(strict_types=1);

namespace PhpMyAdmin\Config\Forms\User;

use PhpMyAdmin\Config\Forms\BaseFormList;

class UserFormList extends BaseFormList
{
    
    protected static $all = [
        'Features',
        'Sql',
        'Navi',
        'Main',
        'Export',
        'Import',
    ];
    
    protected static $ns = 'PhpMyAdmin\\Config\\Forms\\User\\';
}
