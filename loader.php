<?php
define ('HOME', dirname(__FILE__));
define ('SECRET_KEY','ashdj#');

require_once('includes/db.php');
require_once('includes/functions.php');

$siteUrl = $_SERVER['SERVER_NAME'];

$errors = 0;
$errorMessages = array();




if(isset($_GET['_route']) && !empty($_GET['_route'])){
	$route = $_GET['_route'];
}else{
	$route = 'index';
}

if(strpos($route,'/')){
	$path = explode('/',$route);
}

//Prepare get
$getVariables = array();


unset($_GET['_route']);
foreach($_GET as $key => $value){
	$getVariables[$key] = strip_tags($value);
}

//Prepare post
$postVariables = array();
foreach($_POST as $key => $value){
	$postVariables[$key] = strip_tags($value);
}

if(is_dir('app/controllers/'.$route)){
	$route = $route.'/index';
}

$_currentRoute = $route;

//Start the session
session_start();

//Create
$_done = 0;
	if(file_exists('app/controllers/'.$route.'.php')){
		include('app/controllers/'.$route.'.php');
		$_done = 1;
	}
	
	if(file_exists('app/views/'.$route.'.php')){
		include('app/views/'.$route.'.php');
		$_done = 1;
	}
	
	if($_done == 0){
		die('ERROR: Cannot load controller or view :'.$route);
	}

