<?php
######################################################################
#	WALMART API
#	- item class
######################################################################

# WALMART-API ITEM CLASS
class WalmartItem extends WalmartAPI {

	# https://developer.walmartapis.com/v1/#get-all-items
	public function getAllItems()
    {
		// make request
		$result= $this->get_feed("https://marketplace.walmartapis.com/v2/items");
		// convert xml result to php array
		$parser = new XmlToArrayParser($result, false);
		$xml_array = $parser->array;
		// return result
		return $xml_array;
    }

	# https://developer.walmartapis.com/v1/#get-an-item
	public function getItem($SKU)
    {
		// make request
		$result= $this->get_feed('https://marketplace.walmartapis.com/v2/items/'.$SKU);
		// convert xml result to php array
		$parser = new XmlToArrayParser($result, false);
		$xml_array = $parser->array;
		// return result
		return $xml_array;
    }

    # https://developer.walmartapis.com/v1/#bulk-createupdate-items
	public function bulkCreateUpdate($xml_feed)
    {
		// make request
		$result= $this->post_feed('https://marketplace.walmartapis.com/v2/feeds?feedType=item',$xml_feed);
		// convert xml result to php array
		$parser = new XmlToArrayParser($result, false);
		$xml_array = $parser->array;
		// return result
		return $xml_array;
    }

}


?>