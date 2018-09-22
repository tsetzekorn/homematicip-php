<?php
class HmIP_PushButton extends HmIP_Device {
	public $buttonLabel = array();
	
	function __construct($connection) {	
		parent::__construct($connection);
	}
	
	public function importRawData($rawdata){
		parent::importRawData($rawdata);

		foreach ($rawdata["functionalChannels"] as $functionalchannel) {
			if (strpos($functionalchannel["functionalChannelType"], "SINGLE_KEY_CHANNEL") !== false) {
				$this->buttonLabel[$functionalchannel["index"]] = $functionalchannel["label"];
			}
		}
	}
}
?>