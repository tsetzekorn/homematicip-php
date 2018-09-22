<?php
class HmIP_HeatingThermostat extends HmIP_Device {
	public $temperatureOffset = 0;
	public $valvePosition = 0.0;
	public $valveState = "";
	public $setPointTemperature = 0.0;
	
	function __construct($connection) {	
		parent::__construct($connection);
	}
	
	public function importRawData($rawdata){
		parent::importRawData($rawdata);

		foreach ($rawdata["functionalChannels"] as $functionalchannel) {
			if (strpos($functionalchannel["functionalChannelType"], "HEATING") !== false) {
				$this->temperatureOffset 	= $functionalchannel["temperatureOffset"];
				$this->valvePosition		= $functionalchannel["valvePosition"];
				$this->valveState			= $functionalchannel["valveState"];
				$this->setPointTemperature	= $functionalchannel["setPointTemperature"];
			}
		}	
	}
}
?>