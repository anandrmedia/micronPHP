<?php
// Database connection
$host = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "vimala_woods";

$db=new PDO("mysql:host=localhost;dbname=".$dbname, $dbuser, $dbpass);
	
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);