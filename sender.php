<?php
/**
 * Created by PhpStorm.
 * User: jhunnelpalomares
 * Date: 9/3/18
 * Time: 12:14 PM
 */
define('SITE_ROOT', __DIR__);
require 'vendor/autoload.php';

$post_data = file_get_contents('php://input');

//$post_data = 'message_type=REPLY&mobile_number=09175707189&message=THIS%20IS%20TEST%20 REPLY MESSAGE&request_id=4f2a7dd0-af1c-11e8-aadd-8bff7ed8ffb0&keyword=BPS';
//$post_data = 'message_type=SEND&mobile_number=09175707189&message=THIS%20IS%20TEST%20 SEND MESSAGE&keyword=BPS';

if ($post_data) {
    $sender = new \KeywordRouter\MessageSender($post_data);
    $sender->send();
}