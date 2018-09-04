<?php
/**
 * Created by PhpStorm.
 * User: jhunnelpalomares
 * Date: 9/3/18
 * Time: 12:14 PM
 */

define('SITE_ROOT', __DIR__);
require 'vendor/autoload.php';

//$postData = 'message=EDN+87C82220&message_type=incoming&mobile_number=639455712578&request_id=4f2a7dd0-af1c-11e8-aadd-8bff7ed8ffb0&shortcode=29290633&timestamp=1535939675';
$postData = file_get_contents('php://input');

if ($postData) {
    file_put_contents('/tmp/v8Mo_MR_29290633-' . time(), $postData); /*for testing*/
    parse_str($postData, $messageBody);

    // if need to route to new app
    $kw = explode(' ', $messageBody)[0];
    if (strtoupper($kw) !== 'BPS') {
        $route = new \KeywordRouter\ReceiverRouter($postData);
        $route->createTransaction();
        $route->deploy();
    }

    // continue with old app

    $timestamp = $messageBody['timestamp'];
    $request_id = $messageBody['request_id'];
    $mobile_number = $messageBody['mobile_number'];
    $message = urldecode($messageBody['message']);
    $shortcode = $messageBody['shortcode'];
    $message_type = $messageBody['message_type'];

    $msgPart = preg_split("/(?:(?:\/))|(?:(?:\ ))/", $message);

    $client = new Client();
    $url = "http://gateway1.adspark.ph:8077/v8/receiver.php?";

    $post_body = array(
        "keyword" => urlencode($msgPart[0]),
        "msisdn" => $mobile_number,
        "message" => urlencode("message=" . $message . "&" . "request_id=" . $request_id . "&" . "message_type=" . $message_type),
        "time" => $timestamp,
        "shortcode_id" => $shortcode,
    );

    $post_data = '';
    foreach ($post_body as $key => $frow) {
        $post_data .= '&' . $key . '=' . $frow;
    }

    try { /*to MO App*/
        $response = $client->request('GET', $url . $post_data);

        file_put_contents('/tmp/v8Mo_URL_29290633-' . time(), $postData); /*for testing*/

        if ($response->getBody() != "Invalid Keyword") {
            echo 'Accepted'; /*Accepted Keyword*/
        } else {
            $client = new Client();

            $url = 'http://52.220.232.37/v8mo/29290633/sender.php';
            $msg_id = md5(rand(0, 99) . rand(0, 99) . rand(0, 99));
            $message = "Invalid Keyword";
            $notifyUrl = 'FALSE';

            $post_body = array(
                "message_type" => "REPLY",
                "mobile_number" => $mobile_number,
                "shortcode" => $shortcode,
                "request_id" => $request_id,
                "message_id" => $msg_id,
                "message" => $message,
                "notify_url" => urldecode($notifyUrl),
            );

            $post_data = '';
            foreach ($post_body as $key => $frow) {
                $post_data .= '&' . $key . '=' . $frow;
            }

            try { /*to Chikka App*/
                $response = $client->request('POST', $url, ['form_params' => $post_body]);

                $response->getStatusCode();

                file_put_contents('/tmp/v8Mo_RP_29290633-' . time(), $url . $post_data); /*for testing*/

                echo $message; /*Invalid Keyword*/
            } catch (RequestException $e) {
                file_put_contents('/tmp/v8Mo_ERROR_29290633-' . time(), $e->getMessage()); /*for testing*/

                echo $e->getMessage();
            }
        }
    } catch (RequestException $e) {
        file_put_contents('/tmp/v8Mo_ERROR_29290633-' . time(), $e->getMessage()); /*for testing*/

        echo $e->getMessage();
    }
} else {
    file_put_contents('/tmp/v8Mo_MR_29290633_NODATA-' . time(), $post_data); /*for testing*/
}