<?php
######################################################################
#	WALMART API
#	- inventory class
######################################################################

# WALMART-API INVENTORY CLASS
class WalmartInventory extends WalmartAPI {

	# https://developer.walmartapis.com/v1/#get-inventory-for-an-item
	public function getInventory($SKU)
    {
		// make request
		$result= $this->get_feed('https://marketplace.walmartapis.com/v2/inventory?sku='.$SKU);
		// convert xml result to php array
		$parser = new XmlToArrayParser($result, false);
		$xml_array = $parser->array;
		// return result
		return $xml_array;
    }

    # https://developer.walmartapis.com/v1/#bulk-update-inventory
	public function bulkUpdate($xml_feed)
    {
		// make request
		$result= $this->post_feed('https://marketplace.walmartapis.com/v2/feeds?feedType=inventory',$xml_feed);
		// convert xml result to php array
		$parser = new XmlToArrayParser($result, false);
		$xml_array = $parser->array;
		// return result
		return $xml_array;
    }

}


?>