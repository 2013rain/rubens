<?php
define("APP_PATH",  realpath(dirname(__FILE__) . '/../')); /* 指向public的上一级 */

require_once ("/rain_data/source/lib/config/constants.php");
define('ART_PATH',   WFY_DATA_PATH . 'art/');

$app  = new Yaf_Application(APP_PATH . "/conf/application.ini");
$app->bootstrap()->run();
