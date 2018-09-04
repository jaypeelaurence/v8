<?php
/**
 * Created by PhpStorm.
 * User: jhunnelpalomares
 * Date: 9/3/18
 * Time: 12:14 PM
 */

namespace KeywordRouter;

use GuzzleHttp\Client;

abstract class Router
{
    /**
     * @var string
     */
    protected $payload_str;

    /**
     * @var array
     */
    protected $payload;

    /**
     * Router constructor.
     * @param $payload
     */
    public function __construct($payload)
    {
        $this->payload_str = $payload;

        parse_str($payload, $this->payload);
    }

    abstract function createTransaction();

    abstract function deploy();


}