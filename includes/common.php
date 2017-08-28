<?php 

function get_script_name(){
	$script_dir = ($_SERVER["REQUEST_URI"]);
	$script_name =  explode("/",$script_dir);
	$script_name = (end($script_name));
	return $script_name;
}

function is_empty($var){
	$judge = array_filter($var);
	if(empty($judge)){
	return true;
	}else{
	return false;
	}
}


ini_set('display_errors',3000);
error_reporting(E_ALL);
$config_file ="config.ini";
$ini_array = parse_ini_file($config_file);

?>

