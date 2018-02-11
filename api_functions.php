<?php

######################################################################
#
#	api functions.php
#
######################################################################


# WALMART API CLASS
class WalmartAPI {

private $WM_CONSUMER_ID=WM_CONSUMER_ID;
private $WM_PRIVATE_KEY=WM_PRIVATE_KEY;
private $WM_CONSUMER_CHANNEL_TYPE=WM_CONSUMER_CHANNEL_TYPE;

  public function auth($url, $method)
  {
    
    # generate signature for authentication
    // file get contents method
    $U='http://xxxxxxxx.com/walmart-api/signature.php?method='.$method.'&url='.urlencode($url);
    $WM_SIGNATURE=file_get_contents($U);

    $response = explode(PHP_EOL,$WM_SIGNATURE);
    $signature = explode('WM_SEC.AUTH_SIGNATURE:',$response[0]); $signature=$signature[1];
    $timestamp = explode('WM_SEC.TIMESTAMP:',$response[1]); $timestamp=$timestamp[1];
    $headers[0] = 'WM_SEC.TIMESTAMP: '.$timestamp;
    $headers[1] = 'WM_SEC.AUTH_SIGNATURE: '.$signature;
    $headers[2] = 'WM_CONSUMER.ID: '.$this->WM_CONSUMER_ID;
    $headers[3] = 'WM_CONSUMER.CHANNEL.TYPE: '.$this->WM_CONSUMER_CHANNEL_TYPE;
    return $headers;
  }

  public function get_feed($url, $assoc = false) {
    $ch = curl_init();
    $headers = $this->auth($url, 'GET');
    $qos = uniqid();
    $options = array (
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 60,
    CURLOPT_HEADER => false,
    CURLOPT_HTTPHEADER => array(
    "WM_SVC.NAME: Walmart Marketplace",
    $headers[0],
    $headers[1],
    $headers[2],
    $headers[3],
    "WM_QOS.CORRELATION_ID: ".$qos,
    "Accept: application/xml"
    ),
    CURLOPT_HTTPGET => true
    );
    //echo '<pre>';
    //print_r($options);
    //echo '</pre>';
    //echo '<br>URL '.$url.'<br>';
    curl_setopt_array($ch, $options);
    $response = curl_exec ($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    //echo '<br>Response Code: '.$code.'<br>';
    curl_close ($ch);
    return $response;
  }

  public function post_feed($url, $file) {
    $ch = curl_init();
    $headers = $this->auth($url, 'POST');
    $qos = uniqid();
    $post_data = $file;
    //$post_data = file_get_contents($file);
    $options = array (
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 60,
    CURLOPT_HEADER => false,
    CURLOPT_POST => 1,
    CURLOPT_POSTFIELDS => array('file' => $post_data),
    CURLOPT_HTTPHEADER => array(
    "WM_SVC.NAME: Walmart Marketplace",
    $headers[0],
    $headers[1],
    $headers[2],
    $headers[3],
    "WM_QOS.CORRELATION_ID: ".$qos,
    "Accept: application/xml",
    "Content-Type: multipart/form-data"
    ),
    );
    curl_setopt_array($ch, $options);
    $response = curl_exec ($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    //echo '<br>Response Code: '.$code.'<br>';
    curl_close ($ch);
    return $response;
  }

    public function post_feed_data($url, $data) {
    $ch = curl_init();
    $headers = $this->auth($url, 'POST');
    $qos = uniqid();
    $options = array (
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 60,
    CURLOPT_HEADER => false,
    CURLOPT_POST => 1,
    CURLOPT_POSTFIELDS => $data,
    CURLOPT_HTTPHEADER => array(
    "WM_SVC.NAME: Walmart Marketplace",
    $headers[0],
    $headers[1],
    $headers[2],
    $headers[3],
    "WM_QOS.CORRELATION_ID: ".$qos,
    "Accept: application/xml",
    "Content-Type: application/xml"
    ),
    );
    curl_setopt_array($ch, $options);
    $response = curl_exec ($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    //echo '<br>Response Code: '.$code.'<br>';
    curl_close ($ch);
    return $response;
  }

    public function post($url) {
    $ch = curl_init($url);
    $headers = $this->auth($url, 'POST');
    $qos = uniqid();
    $options = array (
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 60,
    CURLOPT_HEADER => false,
    CURLOPT_POST => 1,
    CURLOPT_HTTPHEADER => array(
    "WM_SVC.NAME: Walmart Marketplace",
    $headers[0],
    $headers[1],
    $headers[2],
    $headers[3],
    "WM_QOS.CORRELATION_ID: ".$qos,
    "Content-Length: 0",
    "Accept: application/xml",
    "Content-Type: application/xml"
    ),
    );
    curl_setopt_array($ch, $options);
    $response = curl_exec ($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    //echo '<br>Response Code: '.$code.'<br>';
    curl_close ($ch);
    return $response;
  }

              // replace array keys (removes ns: from keys)
              public function replaceKeyNS($subject) {
                // if the value is not an array, then you have reached the deepest 
                // point of the branch, so return the value
                if (!is_array($subject)) return $subject;

                $newArray = array(); // empty array to hold copy of subject
                foreach ($subject as $key => $value) {

                if(strpos($key, ':')){
                   unset ($subject[$key]);
                   $pieces = explode(":", $key);
                   $new_key =  $pieces[1];
                }else{
                   $new_key =  $key;
                }

                  $newArray[$new_key] = $value;

                    // add the value with the recursive call
                    $newArray[$new_key] = $this->replaceKeyNS($value);

                }
                return $newArray;
              }

}



# libxml error reporting for validating xml to xsd
function libxml_display_error($error) 
{ 
$return = "<br/>\n"; 
switch ($error->level) { 
case LIBXML_ERR_WARNING: 
$return .= "<b>Warning $error->code</b>: "; 
break; 
case LIBXML_ERR_ERROR: 
$return .= "<b>Error $error->code</b>: "; 
break; 
case LIBXML_ERR_FATAL: 
$return .= "<b>Fatal Error $error->code</b>: "; 
break; 
} 
$return .= trim($error->message); 
if ($error->file) { 
$return .= " in <b>$error->file</b>"; 
} 
$return .= " on line <b>$error->line</b>\n"; 
 
return $return; 
} 
 
function libxml_display_errors() { 
$errors = libxml_get_errors(); 
foreach ($errors as $error) { 
print libxml_display_error($error); 
} 
libxml_clear_errors(); 
} 

// Enable libxml user error handling 
libxml_use_internal_errors(true); 



?>