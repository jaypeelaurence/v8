<?php
/**
 * Created by PhpStorm.
 * User: jhunnelpalomares
 * Date: 9/3/18
 * Time: 5:42 PM
 */

namespace KeywordRouter;


use GuzzleHttp\Client;

class SMS
{
    const MSG_TYPE_SEND = 'SEND';
    const MSG_TYPE_REPLY = 'REPLY';

    private $config;

    private $payload;

    /**
     * SMS constructor.
     */
    public function __construct($payload = '')
    {
        $this->payload = $payload;

        $this->config = Helper::getConfig()->chikka();
    }

    function send()
    {
        $body = array(
            "message_type"  => self::MSG_TYPE_SEND,
            "mobile_number" => $this->payload['mobile_number'],
            "shortcode"     => $this->config['shortcode'],
            "message_id"    => $this->payload['message_id'],
            "message"       => $this->payload['message'],
            "client_id"     => $this->config['client_id'],
            "secret_key"    => $this->config['secret_key']
        );

        echo '<pre>';
        print_r($body);
        echo '</pre>';

        exit;

        $http = new Client();

        $http->request('POST', $this->config['url'], ['form_params' => $body]);

    }

    function reply()
    {
        $body = array(
            "message_type"  => self::MSG_TYPE_REPLY,
            "mobile_number" => $this->payload['mobile_number'],
            "shortcode"     => $this->config['shortcode'],
            "request_id"    => $this->payload['request_id'],
            "message_id"    => $this->payload['message_id'],
            "message"       => $this->payload['message'],
            "request_cost"  => 'FREE',
            "client_id"     => $this->config['client_id'],
            "secret_key"    => $this->config['secret_key']
        );

        echo '<pre>';
        print_r($body);
        echo '</pre>';

        exit;

        $http = new Client();

        $http->request('POST', $this->config['url'], ['form_params' => $body]);

    }
}