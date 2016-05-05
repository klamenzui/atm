<?php defined('droot') OR die('No direct script access.');
//preg_match_all("/\/([A-z]+[0-9]*)\/?/i",$_SERVER['REQUEST_URI'],$p);
/*$u=($_SERVER['REQUEST_URI'][0]=='/')?substr($_SERVER['REQUEST_URI'],1):$_SERVER['REQUEST_URI'];
$g=explode("?",$u);
if(isset($g[0]))
$u=$g[0];
//echo '^'.$u.'^';
$u = str_replace($set['site']['url']['base'],'',$u);
//echo '^'.$u.'^';
if($u!='')
$u=($u[strlen($u)-1]=='/')?substr($u,0,-1):$u;
$request_uri=explode('/',$u);
unset($u,$g);*/
$request_uri = array();
if(!empty($_GET['p'])){
	$request_uri = explode('/', $_GET['p']);
}