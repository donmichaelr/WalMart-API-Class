<?php
######################################################################
#	WALMART API
#	- order class
######################################################################

# WALMART-API ORDER CLASS
class WalmartOrder extends WalmartAPI {

    # Acknowledge an order
	public function acknowledgeOrder($purchaseOrderId)
    {
        if(!is_numeric($purchaseOrderId)){
        	$errormsg="purchaseOrderId must be numeric";
            //$rezultat = array("error" => $errormsg);
			$xmlstr = "<?xml version='1.0' standalone='yes'?><errors><error>".$errormsg."</error></errors>";
            return $xmlstr;
        }

		// make request
		$result= $this->post('https://marketplace.walmartapis.com/v3/orders/'.$purchaseOrderId.'/acknowledge');

        // convert xml result to php array
        $parser = new XmlToArrayParser($result, false);
        // parse then replace ns: in array keys
        $xml_array = $this->replaceKeyNS($parser->array);
        // return result
        return $xml_array;

    }

    # get orders that are in "Created" status and need to be acknowledged
	public function ListReleasedOrders()
    {
		// get created start date - 30 days back should do
		$createdStartDate = date('Y-m-d',strtotime("-30 days",strtotime(date('Y-m-d'))));
		// make request
		$result= $this->get_feed('https://marketplace.walmartapis.com/v3/orders/released?limit=160&createdStartDate='.$createdStartDate);

        // convert xml result to php array
        $parser = new XmlToArrayParser($result, false);
        // parse then replace ns: in array keys
        $xml_array = $this->replaceKeyNS($parser->array);
        // return result
        return $xml_array;

    }

	public function ListOrders($params)
    {
		// get created start date - 30 days back should do
		$createdStartDate = date('Y-m-d',strtotime("-30 days",strtotime(date('Y-m-d'))));
		// make request
        $rurl="https://marketplace.walmartapis.com/v3/orders?limit=160&createdStartDate=".$createdStartDate;
        if($params){ $rurl.=$params; }
		$result= $this->get_feed($rurl);

        // convert xml result to php array
        $parser = new XmlToArrayParser($result, false);
        // parse then replace ns: in array keys
        $xml_array = $this->replaceKeyNS($parser->array);
        // return result
        return $xml_array;
    }

    public function GetOrder($purchaseOrderId)
    {
        if(!is_numeric($purchaseOrderId)){
        	$errormsg="purchaseOrderId must be numeric";
            //$rezultat = array("error" => $errormsg);
			$xmlstr = "<?xml version='1.0' standalone='yes'?><errors><error>".$errormsg."</error></errors>";
            return $xmlstr;
        }

		// make request
		$result= $this->get_feed('https://marketplace.walmartapis.com/v3/orders/'.$purchaseOrderId);

        // convert xml result to php array
        $parser = new XmlToArrayParser($result, false);
        // parse then replace ns: in array keys
        $xml_array = $this->replaceKeyNS($parser->array);
        // return result
        return $xml_array;
    }



	public function shipOrder($purchaseOrderId, $xml_feed)
    {
        if(!is_numeric($purchaseOrderId)){
        	$errormsg="purchaseOrderId must be numeric";
            //$rezultat = array("error" => $errormsg);
			$xmlstr = "<?xml version='1.0' standalone='yes'?><errors><error>".$errormsg."</error></errors>";
            return $xmlstr;
        }

		// make request
		$result= $this->post_feed_data('https://marketplace.walmartapis.com/v3/orders/'.$purchaseOrderId.'/shipping',$xml_feed);

        // convert xml result to php array
        $parser = new XmlToArrayParser($result, false);
        // parse then replace ns: in array keys
        $xml_array = $this->replaceKeyNS($parser->array);
        // return result
        return $xml_array;

    }


	public function cancelOrder($purchaseOrderId)
    {
        if(!is_numeric($purchaseOrderId)){
        	$errormsg="purchaseOrderId must be numeric";
            //$rezultat = array("error" => $errormsg);
			$xmlstr = "<?xml version='1.0' standalone='yes'?><errors><error>".$errormsg."</error></errors>";
            return $xmlstr;
        }

		$xml_feed='';
		// make request
		$result= $this->post_feed('https://marketplace.walmartapis.com/v3/orders/'.$purchaseOrderId.'/cancel',$xml_feed);

        // convert xml result to php array
        $parser = new XmlToArrayParser($result, false);
        // parse then replace ns: in array keys
        $xml_array = $this->replaceKeyNS($parser->array);
        // return result
        return $xml_array;

    }



    # creates an array of POs that need to be acknowledged
    public function listPOsToAcknowledge()
    {

        $orders_result= $this->ListReleasedOrders();
        $po_array = array();
        // get total number of results
        $total_reults = $orders_result['list']['meta']['totalCount'];

            if($total_reults){
            // start at root of order array tree
            $orders_result = $orders_result['list']['elements'];

                if($total_reults <'2'){
                    $po_array[] = $orders_result['order']['purchaseOrderId'];
                }else{
                    foreach($orders_result['order'] AS $order) {
                    $po_array[] = $order['purchaseOrderId'];
                    }
                }
            }
        // return result
        return $po_array;

    }



    # creates an array of POs that need to be shipped ()
    public function listPOsToShip()
    {

        $orders_result= $this->ListOrders('&status=Acknowledged');
        //print_r($orders_result);

        $po_array = array();
        // get total number of results
        $total_reults = $orders_result['list']['meta']['totalCount'];

            if($total_reults){
            // start at root of order array tree
            $orders_result = $orders_result['list']['elements'];

                if($total_reults <'2'){
                    $po_array[] = $orders_result['order']['purchaseOrderId'];
                }else{
                    foreach($orders_result['order'] AS $order) {
                    $po_array[] = $order['purchaseOrderId'];
                    }
                }
            }
        // return result
        return $po_array;

    }


    # creates an array of order line numbers in acknowledged orders ()
    public function listOrderLineNumbers($purchaseOrderId)
    {
        $po_array = array();
        if($purchaseOrderId){
        $orders_result= $this->ListOrders('&status=Acknowledged&purchaseOrderId='.$purchaseOrderId);
        $po_array = array();

            // start at root of order array tree
            $orders_result = $orders_result['list']['elements'];

            // get order item lines
            $depth_test = $orders_result['order']['orderLines']['orderLine'][0]['charges']['charge'][0]['chargeType'];
                if($depth_test){
                    $orderLines = $orders_result['order']['orderLines']['orderLine'];
                        foreach($orderLines AS $orderLine) {
                            $po_array[] = $orderLine['lineNumber'];
                        }
                }else{
                        $orderLines = $orders_result['order']['orderLines'];
                            $po_array[] = $orderLines['orderLine']['lineNumber'];
                }

        }
        // return result
        return $po_array;
    }





}

?>