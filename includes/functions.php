<?php

function siteurl(){
	$path = substr( __FILE__, strlen( $_SERVER[ 'DOCUMENT_ROOT' ] ) );
	$path = explode(DIRECTORY_SEPARATOR,$path);
	$filename = array_pop($path);
	array_pop($path);
	
	$path = implode('/',$path);
	$siteUrl = $_SERVER['SERVER_NAME'];
	
	$path = ltrim($path,DIRECTORY_SEPARATOR);
	$url = "http://$siteUrl/{$path}";
	
	//$url = rtrim($url,'/');
	return $url;
}


function assets($path){
	return siteurl().'/public/assets/'.$path;
}

function route($path,$params=null,$signValue=null){
	
	if($params){
		if($signValue){
			$signature = '&_sign='.md5($signValue.SECRET_KEY);
		}else{
			$signature = '';
		}
	
		$queryString = '?'.http_build_query($params).$signature;
	}else{
		$queryString = '';
	}
	
	
	
	return siteurl().'/'.$path.$queryString;
}

function redirectRoute($path,$params = null){
	
	if($params){
		$queryString = '?'.http_build_query($params);
	}else{
		$queryString = '';
	}
	
	header("Location: ".siteurl().'/'.$path.$queryString);
}

function verifySignature($value){
		
		$sign = $_REQUEST['_sign'];
		
		if(md5($value.SECRET_KEY) == $sign)
			return true;
		else
			return false;
}

function loadView($route,$data = array()){
	extract($data);
	$_done = 0;
	global $_currentRoute;
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
}


function validateRequired($user,$required){
	

	$error = 0;
	$errorFields = array();
	
	$fields = $user;
	
	
	
	foreach($user as $key => $value){
		
		if(in_array($key,$required)){
			
			if(empty(trim($value))){
				
				$error++;
				
				//echo ' - error';
			}else{
				//echo "$key is not empty ".strlen($value);
			}
		}
	}
	
	
	if($error == 0){
		return true;
	}else{
		return false;
	}
}


function magicInsert($tablename,$data){
	
		global $db;
		
		$keys='';
		$qm = '';
		foreach($data as $key => $value){
			$keys.=$key.',';
			$qm .= '?,';
		}
		$keys=rtrim($keys,',');
		$qm=rtrim($qm,',');
		
		
		
		$sql = "INSERT INTO $tablename ( $keys ) VALUES ( $qm)";
		$sth = $db->prepare($sql);
		if($sth->execute(array_values($data)))
			return true;
		else
			return false; 
}
