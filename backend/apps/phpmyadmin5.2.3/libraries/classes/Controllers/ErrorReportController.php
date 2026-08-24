<?php
/**
 * Handle error report submission
 */

declare(strict_types=1);

namespace PhpMyAdmin\Controllers;

use PhpMyAdmin\ErrorHandler;
use PhpMyAdmin\ErrorReport;
use PhpMyAdmin\Http\ServerRequest;
use PhpMyAdmin\Message;
use PhpMyAdmin\ResponseRenderer;
use PhpMyAdmin\Template;
use PhpMyAdmin\UserPreferences;

use function __;
use function count;
use function in_array;
use function is_string;
use function json_decode;
use function time;

/**
 * Handle error report submission
 */
class ErrorReportController extends AbstractController
{
    
    private $errorReport;

    
    private $errorHandler;

    public function __construct(
        ResponseRenderer $response,
        Template $template,
        ErrorReport $errorReport,
        ErrorHandler $errorHandler
    ) {
        parent::__construct($response, $template);
        $this->errorReport = $errorReport;
        $this->errorHandler = $errorHandler;
    }

    public function __invoke(ServerRequest $request): void
    {
        global $cfg;

        
        $exceptionType = $request->getParsedBodyParam('exception_type', '');
        
        $sendErrorReport = $request->getParsedBodyParam('send_error_report');
        
        $automatic = $request->getParsedBodyParam('automatic');
        
        $alwaysSend = $request->getParsedBodyParam('always_send');
        
        $getSettings = $request->getParsedBodyParam('get_settings');

        if (! in_array($exceptionType, ['js', 'php'])) {
            return;
        }

        if ($sendErrorReport) {
            if ($exceptionType === 'php') {
                /**
                 * Prevent infinite error submission.
                 * Happens in case error submissions fails.
                 * If reporting is done in some time interval,
                 *  just clear them & clear json data too.
                 */
                if (
                    isset($_SESSION['prev_error_subm_time'], $_SESSION['error_subm_count'])
                    && $_SESSION['error_subm_count'] >= 3
                    && ($_SESSION['prev_error_subm_time'] - time()) <= 3000
                ) {
                    $_SESSION['error_subm_count'] = 0;
                    $_SESSION['prev_errors'] = '';
                    $this->response->addJSON('stopErrorReportLoop', '1');
                } else {
                    $_SESSION['prev_error_subm_time'] = time();
                    $_SESSION['error_subm_count'] = isset($_SESSION['error_subm_count'])
                        ? $_SESSION['error_subm_count'] + 1
                        : 0;
                }
            }

            $reportData = $this->errorReport->getData($exceptionType);
            if (count($reportData) > 0) {
                $server_response = $this->errorReport->send($reportData);
                if (! is_string($server_response)) {
                    $success = false;
                } else {
                    $decoded_response = json_decode($server_response, true);
                    $success = ! empty($decoded_response) ?
                        $decoded_response['success'] : false;
                }

                
                if ($success) {
                    if ($automatic === 'true' || $cfg['SendErrorReports'] === 'always') {
                        $msg = __(
                            'An error has been detected and an error report has been '
                            . 'automatically submitted based on your settings.'
                        );
                    } else {
                        $msg = __('Thank you for submitting this report.');
                    }
                } else {
                    $msg = __(
                        'An error has been detected and an error report has been generated but failed to be sent.'
                    );
                    $msg .= ' ';
                    $msg .= __('If you experience any problems please submit a bug report manually.');
                }

                $msg .= ' ' . __('You may want to refresh the page.');

                
                if ($success) {
                    $msg = Message::notice($msg);
                } else {
                    $msg = Message::error($msg);
                }

                
                if ($this->response->isAjax()) {
                    if ($exceptionType === 'js') {
                        $this->response->addJSON('message', $msg);
                    } else {
                        $this->response->addJSON('errSubmitMsg', $msg);
                    }
                } elseif ($exceptionType === 'php') {
                    $jsCode = 'Functions.ajaxShowMessage(\'<div class="alert alert-danger" role="alert">'
                        . $msg
                        . '</div>\', false);';
                    $this->response->getFooter()->getScripts()->addCode($jsCode);
                }

                if ($exceptionType === 'php') {
                    $this->errorHandler->savePreviousErrors();
                }

                
                if ($alwaysSend === 'true') {
                    $userPreferences = new UserPreferences();
                    $userPreferences->persistOption('SendErrorReports', 'always', 'ask');
                }
            }
        } elseif ($getSettings) {
            $this->response->addJSON('report_setting', $cfg['SendErrorReports']);
        } elseif ($exceptionType === 'js') {
            $this->response->addJSON('report_modal', $this->errorReport->getEmptyModal());
            $this->response->addHTML($this->errorReport->getForm());
        } else {
            $this->errorHandler->savePreviousErrors();
        }
    }
}
