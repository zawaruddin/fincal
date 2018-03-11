<?php

function e_debug($var, $debug = false){
	echo '<pre>';
	if($debug){
		var_dump($var);
	} else {
		print_r($var, false);
	}
	echo '</pre>';
}