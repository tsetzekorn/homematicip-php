<?php
class HmIP_FullFlushShutter extends HmIP_Device {
	public $shutterLevel = "";
	public $bottomToTopReferenceTime = "";
	public $topToBottomReferenceTime = "";
	
	function __construct($connection) {	
		parent::__construct($connection);
	}
	
	public function importRawData($rawdata){
		parent::importRawData($rawdata);

		foreach ($rawdata["functionalChannels"] as $functionalchannel) {
			if (strpos($functionalchannel["functionalChannelType"], "SHUTTER") !== false) {
				$this->shutterLevel 				= $functionalchannel["shutterLevel"];
				$this->bottomToTopReferenceTime		= $functionalchannel["bottomToTopReferenceTime"];
				$this->topToBottomReferenceTime		= $functionalchannel["topToBottomReferenceTime"];
			}
		}	
	}
	
	public function setShutterLevel($level){
		$data = array("channelIndex" => 1, "deviceId" =>$this->id, "shutterLevel" => $level);
		return parent::doRequest("/hmip/device/control/setShutterLevel", $data);		
	}

	public function setShutterStop(){
		$data = array("channelIndex" => 1, "deviceId" =>$this->id);
		return parent::doRequest("/hmip/device/control/stop", $data);		
	}	
}
?>