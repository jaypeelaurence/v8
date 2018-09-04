<?php
/**
 * Created by PhpStorm.
 * User: jhunnelpalomares
 * Date: 9/3/18
 * Time: 12:14 PM
 */
define('SITE_ROOT', __DIR__);
require 'vendor/autoload.php';

//$postData = 'message_type=REPLY&mobile_number=639455712578&message=THIS%20IS%20TEST%20 REPLY MESSAGE&request_id=4f2a7dd0-af1c-11e8-aadd-8bff7ed8ffb0&keyword=edn';
//$postData = 'message_type=SEND&mobile_number=09175707189&message=THIS%20IS%20TEST%20 SEND MESSAGE&keyword=BPS';

$postData = file_get_contents('php://input');

if (!empty($postData)) {
    file_put_contents('/tmp/v8Mo_SND_29290633-' . time(), $postData);
    parse_str($postData, $messageBody);


    if (array_key_exists('keyword', $messageBody)) {

        $sender = new \KeywordRouter\MessageSender($postData);
        $sender->send();

    } else {

        $clientId = '339c26404b66cc0138a4479b18afefec6d88b4a64caeae046416af89535a61ef';
        $secretKey = '20a2051ff56239019f20ea1d29506c354601af6210692086609e6ec9e3de1243';
        $shortcode = 29290633;

        $client = new Client();
        $url = 'https://post.chikka.com/smsapi/request';

        switch ($messageType = $messageBody['message_type']) {
            case "REPLY":
                $post_body = array(
                    "message_type" => 'REPLY',
                    "mobile_number" => $messageBody['mobile_number'],
                    "shortcode" => $shortcode,
                    "request_id" => $messageBody['request_id'],
                    "message_id" => $messageBody['message_id'],
                    "message" => $messageBody['message'],
                    "request_cost" => 'FREE',
                    "client_id" => $clientId,
                    "secret_key" => $secretKey
                );
                break;
            case "SEND":
                $post_body = array(
                    "message_type" => 'SEND',
                    "mobile_number" => $messageBody['mobile_number'],
                    "shortcode" => $shortcode,
                    "message_id" => $messageBody['message_id'],
                    "message" => $messageBody['message'],
                    "client_id" => $clientId,
                    "secret_key" => $secretKey,
                );
                break;
        }
        try {
            $response = $client->request('POST', $url, ['form_params' => $post_body]);

            echo $response->getBody();

            file_put_contents('/tmp/v8Mo_SND_RP_29290633' . time(), $response->getBody() . " " . $response->getStatusCode());

            file_put_contents('/tmp/v8Mo_TRANS-' . $messageBody['message_id'], $messageBody['notify_url']);

        } catch (RequestException $e) {
            file_put_contents('/tmp/v8Mo_SND_ERROR_29290633' . time(), $e->getMessage()); /*for testing*/

            echo $e->getMessage();
        }
    }

} else {
    echo "invalid";
}