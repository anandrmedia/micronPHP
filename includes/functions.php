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
	
	if(substr($url, -1) !== '/' )
		$url.='/';
	
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
	
	
	
	return siteurl().$path.$queryString;
}

function redirectRoute($path,$params = null){
	
	if($params){
		$queryString = '?'.http_build_query($params);
	}else{
		$queryString = '';
	}
	header("Location: ".siteurl().$path.$queryString);
	die();
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


function extractString($string, $start, $end)
{
	$pos = stripos($string, $start);

	$str = substr($string, $pos);

	$str_two = substr($str, strlen($start));

	$second_pos = stripos($str_two, $end);

	$str_three = substr($str_two, 0, $second_pos);

	$unit = trim($str_three); // remove whitespaces

	return $unit;
}

function match_route($r,$_routes){
	
	//echo "request - ".$r;
	$flag = 0;
	$params = [];

	foreach($_routes as $route => $controller){
		
		$flag = 0;

		if(strtolower($r) == $route){
			return array('route' => $controller, 'params' => []);
		}

		if(substr_count($route,'/') == substr_count($r,'/')){

			
			$data = explode('/',$r);
			$count = substr_count($route,'/');

			$data2 = explode('/',$route);

			
			
			for($i=0; $i <= $count;$i++){
				if(strpos($data2[$i],'{') === false){
				
					if ($data2[$i] != $data[$i]) {
						$flag = 1;
						break;
					}

				}else{


					$key = extractString($data2[$i],'{','}');
					//echo $key.' - '.$data[$i];

					$params[$key] = $data[$i];
				}

				
			}

			if($flag == 1){
				continue;
			}

			


			/*
			$params = $data;

			//print_r($params);

			$i=0;
			while($i < $count){

				unset($params[$i]);
				$i++;
			}

			$params = array_slice($params,$i-1);
			*/



			return array('route' => $controller, 'params' => $params);
		}
	}
}

require_once('user_functions.php');