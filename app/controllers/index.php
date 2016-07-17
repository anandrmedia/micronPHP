<?php

if(isset($routeParams['param'])){
	$name = $routeParams['param'];
}else{
	$name = 'Guest';
}