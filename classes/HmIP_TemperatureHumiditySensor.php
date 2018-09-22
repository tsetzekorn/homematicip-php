<?php
class HmIP_TemperatureHumiditySensor extends HmIP_Device{
	public $temperatureOffset = "";
	public $actualTemperature = "";
	public $humidity = "";

	function __construct($connection) {	
		parent::__construct($connection);
	}
	
	public function importRawData($rawdata){
		parent::importRawData($rawdata);

		foreach ($rawdata["functionalChannels"] as $functionalchannel) {
			if (strpos($functionalchannel["functionalChannelType"], "THERMOSTAT") !== false) {
				$this->temperatureOffset 	= $functionalchannel["temperatureOffset"];
				$this->actualTemperature	= $functionalchannel["actualTemperature"];
				$this->humidity				= $functionalchannel["humidity"];
			}
		}	
	}
}
?>