<?php

declare(strict_types=1);

namespace PhpMyAdmin\Controllers;

use PhpMyAdmin\BrowseForeigners;
use PhpMyAdmin\ConfigStorage\Relation;
use PhpMyAdmin\Http\ServerRequest;
use PhpMyAdmin\ResponseRenderer;
use PhpMyAdmin\Template;

/**
 * Display selection for relational field values
 */
class BrowseForeignersController extends AbstractController
{
    
    private $browseForeigners;

    
    private $relation;

    public function __construct(
        ResponseRenderer $response,
        Template $template,
        BrowseForeigners $browseForeigners,
        Relation $relation
    ) {
        parent::__construct($response, $template);
        $this->browseForeigners = $browseForeigners;
        $this->relation = $relation;
    }

    public function __invoke(ServerRequest $request): void
    {
        
        $database = $request->getParsedBodyParam('db');
        
        $table = $request->getParsedBodyParam('table');
        
        $field = $request->getParsedBodyParam('field');
        
        $fieldKey = $request->getParsedBodyParam('fieldkey', '');
        
        $data = $request->getParsedBodyParam('data', '');
        
        $foreignShowAll = $request->getParsedBodyParam('foreign_showAll');
        
        $foreignFilter = $request->getParsedBodyParam('foreign_filter', '');

        if (! isset($database, $table, $field)) {
            return;
        }

        $this->response->getFooter()->setMinimal();
        $header = $this->response->getHeader();
        $header->disableMenuAndConsole();
        $header->setBodyId('body_browse_foreigners');

        $foreigners = $this->relation->getForeigners($database, $table);
        $foreignLimit = $this->browseForeigners->getForeignLimit($foreignShowAll);
        $foreignData = $this->relation->getForeignData(
            $foreigners,
            $field,
            true,
            $foreignFilter,
            $foreignLimit ?? '',
            true
        );

        $this->response->addHTML($this->browseForeigners->getHtmlForRelationalFieldSelection(
            $database,
            $table,
            $field,
            $foreignData,
            $fieldKey,
            $data
        ));
    }
}
