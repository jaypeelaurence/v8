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

$str = 'message=EDN+87C82220&message_type=incoming&mobile_number=639455712578&request_id=4f2a7dd0-af1c-11e8-aadd-8bff7ed8ffb0&shortcode=29290633&timestamp=1535939675';

$route = new \KeywordRouter\ReceiverRouter($str);
$route->createTransaction();
//$route->deploy();