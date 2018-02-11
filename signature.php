<?php
// get walmart api auth details
include_once("api_config.php");

$method=$_GET['method'];
$url=$_GET['url'];

if ($method && $url){
	$WM_CONSUMER_ID=WM_CONSUMER_ID;
	$WM_PRIVATE_KEY=WM_PRIVATE_KEY;
	$url=urldecode($url);
	$script_path=realpath(dirname(__FILE__));
$java_cmd="/usr/bin/java -jar $script_path/auth/DigitalSignatureUtil-1.0.0.jar DgitalSignatureUtil '$url' $WM_CONSUMER_ID $WM_PRIVATE_KEY $method /tmp/s.txt";
$WM_SIGNATURE = shell_exec($java_cmd);
echo $WM_SIGNATURE;
}

die();
?>
