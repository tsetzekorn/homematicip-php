<?
class HmIP_WeatherSensorPlus extends HmIP_WeatherSensorBasic{
	public $raining = false;
	public $todayRainCounter = 0;
	public $yesterdayRainCounter = 0;
	public $totalRainCounter = 0;
	
	function __construct($connection) {	
		parent::__construct($connection);
	}
	
	public function importRawData($rawdata){
		parent::importRawData($rawdata);

		foreach ($rawdata["functionalChannels"] as $functionalchannel) {
			if (strpos($functionalchannel["functionalChannelType"], "WEATHER_SENSOR") !== false) {
				$this->raining 				= $functionalchannel["raining"];
				$this->todayRainCounter		= $functionalchannel["todayRainCounter"];
				$this->yesterdayRainCounter	= $functionalchannel["yesterdayRainCounter"];
				$this->totalRainCounter		= $functionalchannel["totalRainCounter"];
			}
		}	
	}
}
?>