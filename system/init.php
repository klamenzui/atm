<?php defined('droot') OR die('No direct script access.');
date_default_timezone_set('Europe/Kiev'); 

if ( ! defined('START_TIME'))
{
	define('START_TIME', microtime(TRUE));
}
if ( ! defined('START_MEMORY'))
{
	define('START_MEMORY', memory_get_usage());
}
require_once(droot."set".ext);
require_once(dsystem."loader".ext);
//model
require_once (dmodel."db".ext);
require_once (dmodel."sql".ext);
require_once (dmodel."out".ext);
//controller
require_once (dsystem."template".ext);

global $db;
$db = new db();
$db->connect($set['db']);
require_once(dmodel."sess".ext);
require_once(dsystem."uri".ext);
//print_r($request_uri);
if(!empty($request_uri[0])){
	//if(loader::get($request_uri,$set['route']['dirs']))exit;
}else
	$request_uri[0]=$set['route']['default']['file'];
if(empty($request_uri[1]))
	$request_uri[1]=$set['route']['default']['action'];
if(is_file(dcontroller.$request_uri[0].ext)){
	require_once (dcontroller.$request_uri[0].ext);
	//$request_uri[1]('kk');
	if(class_exists($request_uri[0], FALSE)){
		$o=new $request_uri[0]($set);
		if(method_exists($o,$request_uri[1])){
			$o->{$request_uri[1]}();
		}else die("action <b>{$request_uri[1]}</b> not found");
		unset($o);
	}else die("class <b>{$request_uri[0]}</b> not found");
}else die("file <b>{$request_uri[0]}</b> not found");