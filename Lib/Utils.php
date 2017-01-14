<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 17-01-07
 * Time: 10:22
 */

namespace AWHS\CoreBundle\Lib;


class Utils
{
    static function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    static function secondsToTime($seconds)
    {
        $dtF = new \DateTime("@0");
        $dtT = new \DateTime("@$seconds");
        return $dtF->diff($dtT)->format('%a days, %h hours, %i minutes and %s seconds');
    }

    static function cleanPackageVersion($version)
    {
        if (strpos($version, '~') !== false) {
            return substr($version, 0, strpos($version, '~'));
        } else if (strpos($version, '+') !== false) {
            return substr($version, 0, strpos($version, '+'));
        } else {
            return $version;
        }
    }

    static function isCommandLineInterface()
    {
        return (\php_sapi_name() === 'cli');
    }
}