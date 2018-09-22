<?
class HmIP_WeatherSensorPro extends HmIP_WeatherSensorPlus{
	public $weathervaneAlignmentNeeded = false;
	public $windDirection = 0;
	public $windDirectionVariation = 0;

	function __construct($connection) {	
		parent::__construct($connection);
	}
	
	public function importRawData($rawdata){
		parent::importRawData($rawdata);

		foreach ($rawdata["functionalChannels"] as $functionalchannel) {
			if (strpos($functionalchannel["functionalChannelType"], "WEATHER_SENSOR") !== false) {
				$this->weathervaneAlignmentNeeded 	= $functionalchannel["weathervaneAlignmentNeeded"];
				$this->windDirection				= $functionalchannel["windDirection"];
				$this->windDirectionVariation		= $functionalchannel["windDirectionVariation"];
			}
		}	
	}
}
?>