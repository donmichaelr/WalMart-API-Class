<?php
######################################################################
#	WALMART API
#	- price class
######################################################################

# WALMART-API PRICE CLASS
class WalmartPrice extends WalmartAPI {


    # https://developer.walmartapis.com/#update-bulk-prices
	public function bulkPriceUpdate($xml_feed)
    {
		// make request
		$result= $this->post_feed('https://marketplace.walmartapis.com/v2/feeds?feedType=price',$xml_feed);
		// convert xml result to php array
		$parser = new XmlToArrayParser($result, false);
		$xml_array = $parser->array;
		// return result
		return $xml_array;
    }

}


?>