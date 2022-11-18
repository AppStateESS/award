<?php

declare(strict_types=1);
/**
 * MIT License
 * Copyright (c) 2022 Electronic Student Services @ Appalachian State University
 *
 * See LICENSE file in root directory for copyright and distribution permissions.
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace award\Factory;

use award\AbstractClass\AbstractFactory;
use award\Resource\Document;
use phpws2\Database;
use Canopy\Request;

class DocumentFactory extends AbstractFactory
{

    protected static string $table = 'award_document';
    protected static string $resourceClassName = 'award\Resource\Document';

    /**
     * This function is a copy of of a function from Stackoverflow
     * https://stackoverflow.com/questions/13076480/php-get-actual-maximum-upload-size
     * Comments claim it is from Drupal.
     */
    public static function maximumUploadSize()
    {
        static $max_size = -1;

        if ($max_size < 0) {
            $post_max_size = self::parseSize(ini_get('post_max_size'));
            if ($post_max_size > 0) {
                $max_size = $post_max_size;
            }

            $upload_max = self::parseSize(ini_get('upload_max_filesize'));
            if ($upload_max > 0 && $upload_max < $max_size) {
                $max_size = $upload_max;
            }
        }
        return $max_size;
    }

    public static function copyUploadFile($sourceFile)
    {

    }

    public function getFileDirectory()
    {
        return PHPWS_HOME_DIR . 'files/award/';
    }

    public static function nominationFileName()
    {
        return 'nomination-' . microtime() . '.pdf';
    }

    public static function referenceFileName()
    {
        return 'reference-' . microtime() . '.pdf';
    }

    /**
     * This function is a copy of of a function from Stackoverflow
     * https://stackoverflow.com/questions/13076480/php-get-actual-maximum-upload-size
     * Comments claim it is from Drupal.
     */
    private static function parseSize($size)
    {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size);
        $size = preg_replace('/[^0-9\.]/', '', $size);
        if ($unit) {
            return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        } else {
            return round($size);
        }
    }

}
