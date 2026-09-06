<?php
/**
 * ESRI Shape file import plugin for phpMyAdmin
 */

declare(strict_types=1);

namespace PhpMyAdmin\Plugins\Import;

use PhpMyAdmin\File;
use PhpMyAdmin\Gis\GisFactory;
use PhpMyAdmin\Gis\GisMultiLineString;
use PhpMyAdmin\Gis\GisMultiPoint;
use PhpMyAdmin\Gis\GisPoint;
use PhpMyAdmin\Gis\GisPolygon;
use PhpMyAdmin\Import;
use PhpMyAdmin\Message;
use PhpMyAdmin\Plugins\ImportPlugin;
use PhpMyAdmin\Properties\Plugins\ImportPluginProperties;
use PhpMyAdmin\Sanitize;
use PhpMyAdmin\ZipExtension;
use ZipArchive;

use function __;
use function count;
use function extension_loaded;
use function file_exists;
use function file_put_contents;
use function mb_substr;
use function method_exists;
use function pathinfo;
use function strcmp;
use function strlen;
use function substr;
use function trim;
use function unlink;

use const LOCK_EX;

/**
 * Handles the import for ESRI Shape files
 */
class ImportShp extends ImportPlugin
{
    
    private $zipExtension = null;

    protected function init(): void
    {
        if (! extension_loaded('zip')) {
            return;
        }

        $this->zipExtension = new ZipExtension(new ZipArchive());
    }

    /**
     * @psalm-return non-empty-lowercase-string
     */
    public function getName(): string
    {
        return 'shp';
    }

    protected function setProperties(): ImportPluginProperties
    {
        $importPluginProperties = new ImportPluginProperties();
        $importPluginProperties->setText(__('ESRI Shape File'));
        $importPluginProperties->setExtension('shp');
        $importPluginProperties->setOptionsText(__('Options'));

        return $importPluginProperties;
    }

    /**
     * Handles the whole import logic
     *
     * @param array $sql_data 2-element array with sql data
     */
    public function doImport(?File $importHandle = null, array &$sql_data = []): void
    {
        global $db, $error, $finished, $import_file, $local_import_file, $message, $dbi;

        $GLOBALS['finished'] = false;

        if ($importHandle === null || $this->zipExtension === null) {
            return;
        }

        
        $GLOBALS['importHandle'] = $importHandle;

        $compression = $importHandle->getCompression();

        $shp = new ShapeFileImport(1);
        if ($compression === 'application/zip' && $this->zipExtension->getNumberOfFiles($import_file) > 1) {
            if ($importHandle->openZip('/^.*\.shp$/i') === false) {
                $message = Message::error(
                    __('There was an error importing the ESRI shape file: "%s".')
                );
                $message->addParam($importHandle->getError());

                return;
            }
        }

        $temp_dbf_file = false;
        if (extension_loaded('dbase')) {
            $temp = $GLOBALS['config']->getTempDir('shp');
            if ($compression === 'application/zip' && $temp !== null) {
                $dbf_file_name = $this->zipExtension->findFile($import_file, '/^.*\.dbf$/i');
                if ($dbf_file_name) {
                    $extracted = $this->zipExtension->extract($import_file, $dbf_file_name);
                    if ($extracted !== false) {
                        $path_parts = pathinfo($dbf_file_name);
                        $dbf_file_name = $path_parts['dirname'] . '/' . $path_parts['filename'];
                        $dbf_file_name = Sanitize::sanitizeFilename($dbf_file_name, true);
                        $dbf_file_path = $temp . '/' . $dbf_file_name . '.dbf';

                        if (file_put_contents($dbf_file_path, $extracted, LOCK_EX) !== false) {
                            $temp_dbf_file = true;
                            $shp->fileName = substr($dbf_file_path, 0, -4) . '.*';
                        }
                    }
                }
            } elseif (! empty($local_import_file) && ! empty($GLOBALS['cfg']['UploadDir']) && $compression === 'none') {
                $shp->fileName = mb_substr($import_file, 0, -4) . '.*';
            }
        }
        $shp->loadFromFile('');
        if ($temp_dbf_file && isset($dbf_file_path) && @file_exists($dbf_file_path)) {
            unlink($dbf_file_path);
        }

        if ($shp->lastError != '') {
            $error = true;
            $message = Message::error(
                __('There was an error importing the ESRI shape file: "%s".')
            );
            $message->addParam($shp->lastError);

            return;
        }

        switch ($shp->shapeType) {
            case 0:
                break;
            case 1:
                $gis_type = 'point';
                break;
            case 3:
                $gis_type = 'multilinestring';
                break;
            case 5:
                $gis_type = 'multipolygon';
                break;
            case 8:
                $gis_type = 'multipoint';
                break;
            default:
                $error = true;
                $message = Message::error(
                    __('MySQL Spatial Extension does not support ESRI type "%s".')
                );
                $message->addParam($shp->getShapeName());

                return;
        }

        if (isset($gis_type)) {
            
            $gis_obj = GisFactory::factory($gis_type);
        } else {
            $gis_obj = null;
        }

        $num_rows = count($shp->records);
        $num_data_cols = $shp->getDBFHeader() !== null ? count($shp->getDBFHeader()) : 0;

        $rows = [];
        $col_names = [];
        if ($num_rows != 0) {
            foreach ($shp->records as $record) {
                $tempRow = [];
                if ($gis_obj == null || ! method_exists($gis_obj, 'getShape')) {
                    $tempRow[] = null;
                } else {
                    $tempRow[] = "GeomFromText('"
                        . $gis_obj->getShape($record->shpData) . "')";
                }

                if ($shp->getDBFHeader() !== null) {
                    foreach ($shp->getDBFHeader() as $c) {
                        $cell = trim((string) $record->dbfData[$c[0]]);

                        if (! strcmp($cell, '')) {
                            $cell = 'NULL';
                        }

                        $tempRow[] = $cell;
                    }
                }

                $rows[] = $tempRow;
            }
        }

        if (count($rows) === 0) {
            $error = true;
            $message = Message::error(
                __('The imported file does not contain any data!')
            );

            return;
        }
        $col_names[] = 'SPATIAL';
        $dbfHeader = $shp->getDBFHeader();
        for ($n = 0; $n < $num_data_cols; $n++) {
            if ($dbfHeader === null) {
                continue;
            }

            $col_names[] = $dbfHeader[$n][0];
        }
        if (strlen((string) $db) > 0) {
            $result = $dbi->fetchResult('SHOW TABLES');
            $table_name = 'TABLE ' . (count($result) + 1);
        } else {
            $table_name = 'TBL_NAME';
        }

        $tables = [
            [
                $table_name,
                $col_names,
                $rows,
            ],
        ];
        $analyses = [];
        $analyses[] = $this->import->analyzeTable($tables[0]);

        $table_no = 0;
        $spatial_col = 0;
        $analyses[$table_no][Import::TYPES][$spatial_col] = Import::GEOMETRY;
        $analyses[$table_no][Import::FORMATTEDSQL][$spatial_col] = true;
        if (strlen((string) $db) > 0) {
            $db_name = $db;
            $options = ['create_db' => false];
        } else {
            $db_name = 'SHP_DB';
            $options = null;
        }
        $null_param = null;
        $this->import->buildSql($db_name, $tables, $analyses, $null_param, $options, $sql_data);

        unset($tables, $analyses);

        $finished = true;
        $error = false;
        $this->import->runQuery('', '', $sql_data);
    }

    /**
     * Returns specified number of bytes from the buffer.
     * Buffer automatically fetches next chunk of data when the buffer
     * falls short.
     * Sets $eof when $GLOBALS['finished'] is set and the buffer falls short.
     *
     * @param int $length number of bytes
     *
     * @return string
     */
    public static function readFromBuffer($length)
    {
        global $buffer, $eof, $importHandle;

        $import = new Import();

        if (strlen((string) $buffer) < $length) {
            if ($GLOBALS['finished']) {
                $eof = true;
            } else {
                $buffer .= $import->getNextChunk($importHandle);
            }
        }

        $result = substr($buffer, 0, $length);
        $buffer = substr($buffer, $length);

        return $result;
    }
}
