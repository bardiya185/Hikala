<?php

declare(strict_types=1);

namespace PhpMyAdmin\Controllers\Export;

use PhpMyAdmin\Controllers\AbstractController;
use PhpMyAdmin\Controllers\Database\ExportController as DatabaseExportController;
use PhpMyAdmin\Core;
use PhpMyAdmin\Encoding;
use PhpMyAdmin\Exceptions\ExportException;
use PhpMyAdmin\Export;
use PhpMyAdmin\Http\ServerRequest;
use PhpMyAdmin\Message;
use PhpMyAdmin\Plugins;
use PhpMyAdmin\Plugins\ExportPlugin;
use PhpMyAdmin\ResponseRenderer;
use PhpMyAdmin\Sanitize;
use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Statements\SelectStatement;
use PhpMyAdmin\SqlParser\Utils\Misc;
use PhpMyAdmin\Template;
use PhpMyAdmin\Url;
use PhpMyAdmin\Util;

use function __;
use function count;
use function function_exists;
use function in_array;
use function ini_set;
use function is_array;
use function ob_end_clean;
use function ob_get_length;
use function ob_get_level;
use function register_shutdown_function;
use function strlen;
use function time;

use const PHP_EOL;

final class ExportController extends AbstractController
{
    
    private $export;

    public function __construct(ResponseRenderer $response, Template $template, Export $export)
    {
        parent::__construct($response, $template);
        $this->export = $export;
    }

    public function __invoke(ServerRequest $request): void
    {
        global $containerBuilder, $db, $export_type, $filename_template, $sql_query, $errorUrl, $message;
        global $compression, $crlf, $asfile, $buffer_needed, $save_on_server, $file_handle, $separate_files;
        global $output_charset_conversion, $output_kanji_conversion, $table, $what, $export_plugin, $single_table;
        global $compression_methods, $onserver, $back_button, $refreshButton, $save_filename, $filename;
        global $quick_export, $cfg, $tables, $table_select, $aliases;
        global $time_start, $charset, $remember_template, $mime_type, $num_tables;
        global $active_page, $do_relation, $do_comments, $do_mime, $do_dates, $whatStrucOrData, $db_select;
        global $table_structure, $table_data, $lock_tables, $allrows, $limit_to, $limit_from;

        
        $postParams = $request->getParsedBody();

        
        $whatParam = $request->getParsedBodyParam('what', '');
        
        $quickOrCustom = $request->getParsedBodyParam('quick_or_custom');
        
        $outputFormat = $request->getParsedBodyParam('output_format');
        
        $compressionParam = $request->getParsedBodyParam('compression', '');
        
        $asSeparateFiles = $request->getParsedBodyParam('as_separate_files');
        
        $quickExportOnServer = $request->getParsedBodyParam('quick_export_onserver');
        
        $onServerParam = $request->getParsedBodyParam('onserver');
        
        $aliasesParam = $request->getParsedBodyParam('aliases');
        
        $structureOrDataForced = $request->getParsedBodyParam('structure_or_data_forced');

        $this->addScriptFiles(['export_output.js']);

        /**
         * Sets globals from $_POST
         *
         * - Please keep the parameters in order of their appearance in the form
         * - Some of these parameters are not used, as the code below directly
         *   verifies from the superglobal $_POST or $_REQUEST
         * TODO: this should be removed to avoid passing user input to GLOBALS
         * without checking
         */
        $allowedPostParams = [
            'db',
            'table',
            'what',
            'single_table',
            'export_type',
            'export_method',
            'quick_or_custom',
            'db_select',
            'table_select',
            'table_structure',
            'table_data',
            'limit_to',
            'limit_from',
            'allrows',
            'lock_tables',
            'output_format',
            'filename_template',
            'maxsize',
            'remember_template',
            'charset',
            'compression',
            'as_separate_files',
            'knjenc',
            'xkana',
            'htmlword_structure_or_data',
            'htmlword_null',
            'htmlword_columns',
            'mediawiki_headers',
            'mediawiki_structure_or_data',
            'mediawiki_caption',
            'pdf_structure_or_data',
            'odt_structure_or_data',
            'odt_relation',
            'odt_comments',
            'odt_mime',
            'odt_columns',
            'odt_null',
            'codegen_structure_or_data',
            'codegen_format',
            'excel_null',
            'excel_removeCRLF',
            'excel_columns',
            'excel_edition',
            'excel_structure_or_data',
            'yaml_structure_or_data',
            'ods_null',
            'ods_structure_or_data',
            'ods_columns',
            'json_structure_or_data',
            'json_pretty_print',
            'json_unicode',
            'xml_structure_or_data',
            'xml_export_events',
            'xml_export_functions',
            'xml_export_procedures',
            'xml_export_tables',
            'xml_export_triggers',
            'xml_export_views',
            'xml_export_contents',
            'texytext_structure_or_data',
            'texytext_columns',
            'texytext_null',
            'phparray_structure_or_data',
            'sql_include_comments',
            'sql_header_comment',
            'sql_dates',
            'sql_relation',
            'sql_mime',
            'sql_use_transaction',
            'sql_disable_fk',
            'sql_compatibility',
            'sql_structure_or_data',
            'sql_create_database',
            'sql_drop_table',
            'sql_procedure_function',
            'sql_create_table',
            'sql_create_view',
            'sql_create_trigger',
            'sql_view_current_user',
            'sql_simple_view_export',
            'sql_if_not_exists',
            'sql_or_replace_view',
            'sql_auto_increment',
            'sql_backquotes',
            'sql_truncate',
            'sql_delayed',
            'sql_ignore',
            'sql_type',
            'sql_insert_syntax',
            'sql_max_query_size',
            'sql_hex_for_binary',
            'sql_utc_time',
            'sql_drop_database',
            'sql_views_as_tables',
            'sql_metadata',
            'csv_separator',
            'csv_enclosed',
            'csv_escaped',
            'csv_terminated',
            'csv_null',
            'csv_removeCRLF',
            'csv_columns',
            'csv_structure_or_data',
            'latex_caption',
            'latex_structure_or_data',
            'latex_structure_caption',
            'latex_structure_continued_caption',
            'latex_structure_label',
            'latex_relation',
            'latex_comments',
            'latex_mime',
            'latex_columns',
            'latex_data_caption',
            'latex_data_continued_caption',
            'latex_data_label',
            'latex_null',
            'aliases',
        ];

        foreach ($allowedPostParams as $param) {
            if (! isset($postParams[$param])) {
                continue;
            }

            $GLOBALS[$param] = $postParams[$param];
        }

        Util::checkParameters(['what', 'export_type']);
        $what = Core::securePath($whatParam);
        
        $export_plugin = Plugins::getPlugin('export', $what, [
            'export_type' => (string) $export_type,
            'single_table' => isset($single_table),
        ]);
        if (empty($export_plugin)) {
            Core::fatalError(__('Bad type!'));
        }

        /**
         * valid compression methods
         */
        $compression_methods = [];
        if ($GLOBALS['cfg']['ZipDump'] && function_exists('gzcompress')) {
            $compression_methods[] = 'zip';
        }

        if ($GLOBALS['cfg']['GZipDump'] && function_exists('gzencode')) {
            $compression_methods[] = 'gzip';
        }

        /**
         * init and variable checking
         */
        $compression = '';
        $onserver = false;
        $save_on_server = false;
        $buffer_needed = false;
        $back_button = '';
        $refreshButton = '';
        $save_filename = '';
        $file_handle = '';
        $errorUrl = '';
        $filename = '';
        $separate_files = '';
        if ($quickOrCustom === 'quick') {
            $quick_export = true;
        } else {
            $quick_export = false;
        }

        if ($outputFormat === 'astext') {
            $asfile = false;
        } else {
            $asfile = true;
            if ($asSeparateFiles && $compressionParam === 'zip') {
                $separate_files = $asSeparateFiles;
            }

            if (in_array($compressionParam, $compression_methods)) {
                $compression = $compressionParam;
                $buffer_needed = true;
            }

            if (($quick_export && $quickExportOnServer) || (! $quick_export && $onServerParam)) {
                if ($quick_export) {
                    $onserver = $quickExportOnServer;
                } else {
                    $onserver = $onServerParam;
                }
                $save_on_server = ! empty($cfg['SaveDir']);
            }
        }

        /**
         * If we are sending the export file (as opposed to just displaying it
         * as text), we have to bypass the usual PhpMyAdmin\Response mechanism
         */
        if ($outputFormat === 'sendit' && ! $save_on_server) {
            $this->response->disable();
            do {
                if (ob_get_length() > 0 || ob_get_level() > 0) {
                    $hasBuffer = ob_end_clean();
                } else {
                    $hasBuffer = false;
                }
            } while ($hasBuffer);
        }

        $tables = [];
        if ($export_type === 'server') {
            $errorUrl = Url::getFromRoute('/server/export');
        } elseif ($export_type === 'database' && strlen($db) > 0) {
            $errorUrl = Url::getFromRoute('/database/export', ['db' => $db]);
            $tables = $table_select ?? [];
        } elseif ($export_type === 'table' && strlen($db) > 0 && strlen($table) > 0) {
            $errorUrl = Url::getFromRoute('/table/export', [
                'db' => $db,
                'table' => $table,
            ]);
        } elseif ($export_type === 'raw') {
            $errorUrl = Url::getFromRoute('/server/export', ['sql_query' => $sql_query]);
        } else {
            Core::fatalError(__('Bad parameters!'));
        }
        $parser = new Parser($sql_query);
        $aliases = [];
        if (! empty($parser->statements[0]) && ($parser->statements[0] instanceof SelectStatement)) {
            $aliases = Misc::getAliases($parser->statements[0], $db);
        }

        if (! empty($aliasesParam)) {
            $aliases = $this->export->mergeAliases($aliases, $aliasesParam);
            $_SESSION['tmpval']['aliases'] = $aliasesParam;
        }

        /**
         * Increase time limit for script execution and initializes some variables
         */
        Util::setTimeLimit();
        if (! empty($cfg['MemoryLimit'])) {
            ini_set('memory_limit', $cfg['MemoryLimit']);
        }

        register_shutdown_function([$this->export, 'shutdown']);
        $this->export->dumpBuffer = '';
        $this->export->dumpBufferLength = 0;
        $this->export->dumpBufferObjects = [];
        $time_start = time();
        if ($what === 'sql') {
            $crlf = "\n";
        } else {
            $crlf = PHP_EOL;
        }

        $output_kanji_conversion = Encoding::canConvertKanji();
        $output_charset_conversion = $asfile
            && Encoding::isSupported()
            && isset($charset) && $charset !== 'utf-8'
            && in_array($charset, Encoding::listEncodings(), true);
        $GLOBALS['onfly_compression'] = $GLOBALS['cfg']['CompressOnFly']
            && $compression === 'gzip';
        if ($GLOBALS['onfly_compression']) {
            $GLOBALS['memory_limit'] = $this->export->getMemoryLimit();
        }
        if ($asfile) {
            if (empty($remember_template)) {
                $remember_template = '';
            }

            [$filename, $mime_type] = $this->export->getFilenameAndMimetype(
                $export_type,
                $remember_template,
                $export_plugin,
                $compression,
                $filename_template
            );
        } else {
            $mime_type = '';
        }
        if ($export_type === 'raw') {
            [$filename] = $this->export->getFinalFilenameAndMimetypeForFilename($export_plugin, $compression, 'export');
        }
        if ($save_on_server) {
            [$save_filename, $message, $file_handle] = $this->export->openFile($filename, $quick_export);
            if (! empty($message)) {
                $this->export->showPage($export_type);

                return;
            }
        } else {
            /**
             * Send headers depending on whether the user chose to download a dump file
             * or not
             */
            if ($asfile) {
                ini_set('url_rewriter.tags', '');
                $filename = Sanitize::sanitizeFilename($filename);

                Core::downloadHeader($filename, $mime_type);
            } else {
                if ($export_type === 'database') {
                    $num_tables = count($tables);
                    if ($num_tables === 0) {
                        $message = Message::error(
                            __('No tables found in database.')
                        );
                        $active_page = Url::getFromRoute('/database/export');
                        
                        $controller = $containerBuilder->get(DatabaseExportController::class);
                        $controller();
                        exit;
                    }
                }

                [$html, $back_button, $refreshButton] = $this->export->getHtmlForDisplayedExportHeader(
                    $export_type,
                    $db,
                    $table
                );
                echo $html;
                unset($html);
            }
        }

        try {
            $this->export->dumpBuffer = '';
            $this->export->dumpBufferLength = 0;
            if (! $export_plugin->exportHeader()) {
                throw new ExportException('Failure during header export.');
            }
            $do_relation = isset($GLOBALS[$what . '_relation']);
            $do_comments = isset($GLOBALS[$what . '_include_comments'])
                || isset($GLOBALS[$what . '_comments']);
            $do_mime = isset($GLOBALS[$what . '_mime']);
            $do_dates = isset($GLOBALS[$what . '_dates']);

            $whatStrucOrData = $GLOBALS[$what . '_structure_or_data'] ?? null;
            if (! in_array($whatStrucOrData, ['structure', 'data', 'structure_and_data'], true)) {
                $whatStrucOrData = 'data';
                
                $whatStrucOrDataDefaultValue = $cfg['Export'][$what . '_structure_or_data'] ?? null;
                if (in_array($whatStrucOrDataDefaultValue, ['structure', 'data', 'structure_and_data'], true)) {
                    $whatStrucOrData = $whatStrucOrDataDefaultValue;
                }

                $GLOBALS[$what . '_structure_or_data'] = $whatStrucOrData;
            }

            if ($export_type === 'raw') {
                $whatStrucOrData = 'raw';
            }

            /**
             * Builds the dump
             */
            if ($export_type === 'server') {
                if (! isset($db_select)) {
                    $db_select = '';
                }

                $this->export->exportServer(
                    $db_select,
                    $whatStrucOrData,
                    $export_plugin,
                    $crlf,
                    $errorUrl,
                    $export_type,
                    $do_relation,
                    $do_comments,
                    $do_mime,
                    $do_dates,
                    $aliases,
                    $separate_files
                );
            } elseif ($export_type === 'database') {
                if (! isset($table_structure) || ! is_array($table_structure)) {
                    $table_structure = [];
                }

                if (! isset($table_data) || ! is_array($table_data)) {
                    $table_data = [];
                }

                if ($structureOrDataForced) {
                    $table_structure = $tables;
                    $table_data = $tables;
                }

                if (isset($lock_tables)) {
                    $this->export->lockTables($db, $tables, 'READ');
                    try {
                        $this->export->exportDatabase(
                            $db,
                            $tables,
                            $whatStrucOrData,
                            $table_structure,
                            $table_data,
                            $export_plugin,
                            $crlf,
                            $errorUrl,
                            $export_type,
                            $do_relation,
                            $do_comments,
                            $do_mime,
                            $do_dates,
                            $aliases,
                            $separate_files
                        );
                    } finally {
                        $this->export->unlockTables();
                    }
                } else {
                    $this->export->exportDatabase(
                        $db,
                        $tables,
                        $whatStrucOrData,
                        $table_structure,
                        $table_data,
                        $export_plugin,
                        $crlf,
                        $errorUrl,
                        $export_type,
                        $do_relation,
                        $do_comments,
                        $do_mime,
                        $do_dates,
                        $aliases,
                        $separate_files
                    );
                }
            } elseif ($export_type === 'raw') {
                Export::exportRaw($whatStrucOrData, $export_plugin, $crlf, $errorUrl, $db, $sql_query, $export_type);
            } else {
                if (! isset($allrows)) {
                    $allrows = '';
                }

                if (! isset($limit_to)) {
                    $limit_to = '0';
                }

                if (! isset($limit_from)) {
                    $limit_from = '0';
                }

                if (isset($lock_tables)) {
                    try {
                        $this->export->lockTables($db, [$table], 'READ');
                        $this->export->exportTable(
                            $db,
                            $table,
                            $whatStrucOrData,
                            $export_plugin,
                            $crlf,
                            $errorUrl,
                            $export_type,
                            $do_relation,
                            $do_comments,
                            $do_mime,
                            $do_dates,
                            $allrows,
                            $limit_to,
                            $limit_from,
                            $sql_query,
                            $aliases
                        );
                    } finally {
                        $this->export->unlockTables();
                    }
                } else {
                    $this->export->exportTable(
                        $db,
                        $table,
                        $whatStrucOrData,
                        $export_plugin,
                        $crlf,
                        $errorUrl,
                        $export_type,
                        $do_relation,
                        $do_comments,
                        $do_mime,
                        $do_dates,
                        $allrows,
                        $limit_to,
                        $limit_from,
                        $sql_query,
                        $aliases
                    );
                }
            }

            if (! $export_plugin->exportFooter()) {
                throw new ExportException('Failure during footer export.');
            }
        } catch (ExportException $e) {
        }

        if ($save_on_server && ! empty($message)) {
            $this->export->showPage($export_type);

            return;
        }

        /**
         * Send the dump as a file...
         */
        if (empty($asfile)) {
            echo $this->export->getHtmlForDisplayedExportFooter($back_button, $refreshButton);

            return;
        }
        if ($output_charset_conversion) {
            $this->export->dumpBuffer = Encoding::convertString(
                'utf-8',
                $GLOBALS['charset'],
                $this->export->dumpBuffer
            );
        }
        if ($compression) {
            if (! empty($separate_files)) {
                $this->export->dumpBuffer = $this->export->compress(
                    $this->export->dumpBufferObjects,
                    $compression,
                    $filename
                );
            } else {
                $this->export->dumpBuffer = $this->export->compress($this->export->dumpBuffer, $compression, $filename);
            }
        }

        
        if ($save_on_server) {
            $message = $this->export->closeFile($file_handle, $this->export->dumpBuffer, $save_filename);
            $this->export->showPage($export_type);

            return;
        }

        echo $this->export->dumpBuffer;
    }
}
