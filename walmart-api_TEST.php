<?php

######################################################################
#
#	WALMART API - April 2017
#	- api test file
#
######################################################################
//exit();
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

// include the basic stuff
include_once("api_config.php");
include_once("api_functions.php");
//include the xml array class
include_once("classes/xmlToArrayParser.class.php");
// include walmart classes
include_once("classes/orders_class.php");
include_once("classes/inventory_class.php");
include_once("classes/item_class.php");
include_once("classes/price_class.php");



# initiate main walmart api order class
$orders = new WalmartOrder();




$purchaseOrderId='25787857572979333';
// make request
//$feed_result= $orders->GetOrder($purchaseOrderId);


// display results
echo '<br><pre>';
//print_r($feed_result);
echo '</pre>';



?>