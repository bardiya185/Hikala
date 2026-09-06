<?php

declare(strict_types=1);

namespace PhpMyAdmin\Controllers\Import;

use PhpMyAdmin\Core;
use PhpMyAdmin\Import\Ajax;
use PhpMyAdmin\Message;
use PhpMyAdmin\Template;

use function __;
use function header;
use function ini_get;
use function session_start;
use function session_write_close;
use function time;
use function usleep;

/**
 * Import progress bar backend
 */
class StatusController
{
    
    private $template;

    public function __construct(Template $template)
    {
        $this->template = $template;
    }

    public function __invoke(): void
    {
        global $SESSION_KEY, $upload_id, $plugins, $timestamp;

        [
            $SESSION_KEY,
            $upload_id,
            $plugins,
        ] = Ajax::uploadProgressSetup();
        if (isset($_GET['message']) && $_GET['message']) {
            Core::noCacheHeader();

            header('Content-type: text/html');
            usleep(300000);

            $maximumTime = ini_get('max_execution_time');
            $timestamp = time();
            while (($_SESSION['Import_message']['message'] ?? null) == null) {
                session_write_close();
                usleep(250000); // 0.25 sec
                session_start();

                if (time() - $timestamp > $maximumTime) {
                    $_SESSION['Import_message']['message'] = Message::error(
                        __('Could not load the progress of the import.')
                    )->getDisplay();
                    break;
                }
            }

            echo $_SESSION['Import_message']['message'] ?? '';

            if (isset($_SESSION['Import_message']['go_back_url'])) {
                echo $this->template->render('import_status', [
                    'go_back_url' => $_SESSION['Import_message']['go_back_url'],
                ]);
            }
        } else {
            Ajax::status($_GET['id']);
        }
    }
}
