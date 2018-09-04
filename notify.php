<?php
/**
 * Created by PhpStorm.
 * User: jhunnelpalomares
 * Date: 9/3/18
 * Time: 12:14 PM
 */
define('SITE_ROOT', __DIR__);
require 'vendor/autoload.php';

//$result = file_get_contents('php://input');
$result = 'credits_cost=0.500000&message_id=780098cfa448fe7f1f8e12b65daa9d2b&message_type=outgoing&shortcode=29290633&status=SENT&timestamp=1535939684';

if ($result) {
    $route = new \KeywordRouter\NotificationRouter($result);
    $route->createTransaction();
    $route->deploy();
}