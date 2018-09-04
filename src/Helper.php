<?php
/**
 * Created by PhpStorm.
 * User: jhunnelpalomares
 * Date: 9/3/18
 * Time: 2:06 PM
 */

namespace KeywordRouter;


class Helper
{
    const MSISDN_TYPE_1 = '+63';
    const MSISDN_TYPE_2 = '63';
    const MSISDN_TYPE_3 = '0';
    const MSISDN_TYPE_4 = '9';

    /**
     * Default to type 3
     *
     * @param $msisdn
     * @param $format
     * @return mixed
     */
    static function formatMSISDN($msisdn, $format = self::MSISDN_TYPE_3)
    {
        $pos = strpos($msisdn, '9');

        if ($format == self::MSISDN_TYPE_4) {
            $msisdn = $format . substr($msisdn, $pos + 1);
        } else {
            $msisdn = $format . substr($msisdn, $pos);
        }

        return $msisdn;
    }


    /**
     * @return Config
     */
    static function getConfig()
    {
        $configurations = require SITE_ROOT . '/settings.php';

        return $configurations;
    }

}