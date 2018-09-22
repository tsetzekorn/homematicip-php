<?php
class HmIP_SwitchMeasuring extends HmIP_Switch {
	public $energyCounter = "";
	public $currentPowerConsumption = "";
	
	function __construct($connection) {	
		parent::__construct($connection);
	}
	
	public function importRawData($rawdata){
		parent::importRawData($rawdata);

		foreach ($rawdata["functionalChannels"] as $functionalchannel) {
			if (strpos($functionalchannel["functionalChannelType"], "SWITCH") !== false) {
				$this->energyCounter 				= $functionalchannel["energyCounter"];
				$this->currentPowerConsumption		= $functionalchannel["currentPowerConsumption"];
			}
		}
	}
}
?>