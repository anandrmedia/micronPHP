<?php
define ('HOME', dirname(__FILE__));
define ('SECRET_KEY','ashdj#');

require_once('includes/db.php');
require_once('includes/functions.php');
require_once('includes/routes.php');

error_reporting(E_ALL);
$siteUrl = $_SERVER['SERVER_NAME'];

$errors = 0;
$errorMessages = array();
$routeParams = [];





if(isset($_GET['_route']) && !empty($_GET['_route'])){
	$route = rtrim($_GET['_route'],'/');
	
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


//Sanitize the inputs
foreach($_GET as $key => $value){
	$_GETRequest[$key] = htmlentities($value); 
}

foreach($_POST as $key => $value){
	$_POSTRequest[$key] = htmlentities($value); 
}

//Create
$_done = 0;

//check in our routes table first

if($resolver = match_route($route,$_routes)){
	$route = $resolver['route'];
	$routeParams = $resolver['params'];

}

	if(file_exists('app/controllers/'.$route.'.php')){
		include('app/controllers/'.$route.'.php');
		$_done = 1;
	}
	
	if(file_exists('app/views/'.$route.'.php')){
		include('app/views/'.$route.'.php');
		$_done = 1;
	}
	
	if($_done == 0){
		//echo "resolver";
		//print_r(match_route($route,$_routes));

		die('ERROR: Cannot load controller or view :'.$route);
	}

