<?php
//config.php
//mysql
/**
 * Ram size in bytes
 */
$ram_size = 10000;
/**
 * Database Ram key
 */
$ram_key = 0xff9;
/**
 * Mysql database
 */
$mysql['database'] = "rover";
/**
 * Mysql user
 */
$mysql['user'] = "root";
/**
 * Mysql password
 */
$mysql['password'] = "";
/**
 * Mysql server
 */
$mysql['server'] = "localhost";
/**
 * Version
 * @var string
 */
$version = "0.2.1";
if(!isset($not_connect)){
	//contect to database
	mysql_connect($mysql['server'], $mysql['user'], $mysql['password']) or die(mysql_error());
	mysql_select_db($mysql['database']) or die(mysql_error());
}
//session start
session_start();
if(!isset($_SESSION['last_time_check'])){
	$_SESSION['last_time_check'] = (date("U") + microtime()) - 60;
}
//timeout
$nbc_path = "c:/wamp/www/rover/programs/";
/**
 * Time before user will be set to Offline(should not lower then $user_control_time) 
 */
$user_offline_time = 300;
/**
 * Control time
 */
$user_control_time = 300;
/**
 * Array from stream gid's
 * Max 3 streams
 *
 */
$stream_gid = array("marc_overhead","marc_rove");
/**
 * Template name
 *
 */
$template_name = "default";
//wiki setting
/**
 * The base url with / at the end
 * Example: http://wiki.xprize.frednet.org/
 *
 */
$wiki_server = "http://wiki.xprize.frednet.org/";
$wiki_prefixed = "Lego_mindstorm_webpages:";
/**
 * Wiki chache time in hours
 */
$wiki_cache_time = 0.5;
/**
 * config
 * command left
 */
$command_to_go_left = array("cmd run rover program left");//you can use {degrees} to allow use to control the amount of correction
$command_to_go_right = array("cmd run rover program right");//you can use {degrees} to allow use to control the amount of correction
$command_to_go_forward = array("cmd run rover program forward");//you can use {degrees} to allow use to control the amount of correction
$command_to_go_back = array("cmd run rover program back");//you can use {degrees} to allow use to control the amount of correction
 
include("login.php");
?>