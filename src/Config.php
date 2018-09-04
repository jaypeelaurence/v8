<?php
/**
 * Created by PhpStorm.
 * User: jhunnelpalomares
 * Date: 9/3/18
 * Time: 1:34 PM
 */

namespace KeywordRouter;


class Config
{
    const ENV_DEVELOPMENT = 'development';
    const ENV_PRODUCTION = 'production';
    const ENV_STAGING = 'staging';

    private $env;

    /**
     * Config constructor.
     */
    public function __construct($env = '')
    {
        $this->env = $env;
    }

    /**
     * @return mixed
     */
    function database()
    {
        $settings = [
            self::ENV_DEVELOPMENT => array(
                'host' => 'localhost',
                'user' => 'root',
                'pass' => 'root',
                'name' => 'keyrouter@321',
                'port' => '3306',
            ),
            self::ENV_PRODUCTION => array(
                'host' => 'localhost',
                'user' => 'root',
                'pass' => 'jpx@DB123',
                'name' => 'keyrouter_321',
                'port' => '3306',
            ),
            self::ENV_STAGING => array(
                'host' => '',
                'user' => '',
                'pass' => '',
                'name' => '',
                'port' => '3306',
            )
        ];

        return $settings[$this->env];
    }

    /**
     * @return mixed
     */
    function endpoint()
    {
        $settings = array(
            self::ENV_DEVELOPMENT => array(

            ),
            self::ENV_PRODUCTION => array(),
            self::ENV_STAGING => array()
        );

        return $settings[$this->env];
    }

    /**
     * @return array
     */
    function chikka()
    {
        return array(
            'client_id' => '339c26404b66cc0138a4479b18afefec6d88b4a64caeae046416af89535a61ef',
            'secret_key' => '20a2051ff56239019f20ea1d29506c354601af6210692086609e6ec9e3de1243',
            'shortcode' => 29290633,
            'url' => 'https://post.chikka.com/smsapi/request'
        );
    }
}