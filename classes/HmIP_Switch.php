<?php
class HmIP_Switch extends HmIP_Device {
	public $on = "";
	public $profileMode = "";
	public $userDesiredProfileMode = "";
	
	function __construct($connection) {	
		parent::__construct($connection);
	}
	
	public function importRawData($rawdata){
		parent::importRawData($rawdata);

		foreach ($rawdata["functionalChannels"] as $functionalchannel) {
			if (strpos($functionalchannel["functionalChannelType"], "SWITCH") !== false) {
				$this->on 						= $functionalchannel["on"];
				$this->profileMode				= $functionalchannel["profileMode"];
				$this->userDesiredProfileMode 	= $functionalchannel["userDesiredProfileMode"];
			}
		}
	}

	public function setSwitchState($on=true) {
		$data = array("channelIndex" => 1, "deviceId" =>$this->id, "on" => $on);
		return parent::doRequest("/hmip/device/control/setSwitchState", $data);
	}
	
	public function turnOn() {
		return $this->setSwitchState(true);
	}

	public function turnOff() {
		return $this->setSwitchState(false);
	}
}
?>