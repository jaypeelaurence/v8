<?php
/**
 * Created by PhpStorm.
 * User: jhunnelpalomares
 * Date: 9/3/18
 * Time: 12:14 PM
 */
define('SITE_ROOT', __DIR__);
require 'vendor/autoload.php';

//$postData = 'credits_cost=0.500000&message_id=66b25f0c8807a8b390665d7dd5c2c297&message_type=outgoing&shortcode=29290633&status=SENT&timestamp=1535939684';
$postData = file_get_contents('php://input');

if (!empty($postData)) {
    $route = new \KeywordRouter\NotificationRouter($postData);
    $route->createTransaction();
    $route->deploy();
}