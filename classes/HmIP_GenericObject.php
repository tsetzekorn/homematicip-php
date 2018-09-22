<?php

class HmIP_GenericObject {
	protected $hmip_connection;
	
	function __construct($connection) {	
		$this->hmip_connection = $connection;
	}
	
	protected function doRequest($path, $body="") {
		return $this->hmip_connection->doRequest($path,$body);
	}
	
	public function importRawData($rawdata){
		// not implemented in Generic Object
	}
}

?>