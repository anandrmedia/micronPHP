<?php
// Database connection

define('ENABLE_DB',false);

$host = "localhost";
$dbuser = "";
$dbpass = '';
$dbname = "";

if(ENABLE_DB){
	$db=new PDO("mysql:host=$host;dbname=".$dbname, $dbuser, $dbpass);
	
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
}

//Define Key

define('SITE_KEY','dj72ncb#4b2i');