<?php
class HmIP_ShutterContact extends HmIP_Device {
	public $windowState = "";
	public $eventDelay = "";

	function __construct($connection) {	
		parent::__construct($connection);
	}
	
	public function importRawData($rawdata){
		parent::importRawData($rawdata);

		foreach ($rawdata["functionalChannels"] as $functionalchannel) {
			if (strpos($functionalchannel["functionalChannelType"], "SHUTTER") !== false) {
				$this->windowState 	= $functionalchannel["windowState"];
				$this->eventDelay	= $functionalchannel["eventDelay"];
			}
		}	
	}
}
?>