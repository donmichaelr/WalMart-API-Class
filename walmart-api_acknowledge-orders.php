<?php
######################################################################
#
#	WALMART API - April 2017
#	- acknowledge orders
#
######################################################################

// include the basic stuff
include_once("api_config.php");
include_once("api_functions.php");
//include the xml array class
include_once("classes/xmlToArrayParser.class.php");
// include walmart classes
include_once("classes/orders_class.php");

# initiate main walmart api order class
$orders = new WalmartOrder();

# Acknowledge New Orders
// get list of POs that are in created status and need to be acknowledged
$po_array= $orders->listPOsToAcknowledge();

foreach($po_array AS $po_order) {
echo 'purchaseOrderId: '.$po_order.' found... Acknowledging..<br>';
// acknowledge PO
$acknowledgeResult = $orders->acknowledgeOrder($po_order);
	if($acknowledgeResult){
		echo '..Acknowledged<br>';
	}
}

?>