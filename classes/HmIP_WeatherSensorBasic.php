<?
class HmIP_WeatherSensorBasic extends HmIP_Device{
	public $actualTemperature = 0;
	public $humidity = 0;
	public $illumination = 0;
	public $illuminationThresholdSunshine = 0;
	public $storm = false;
	public $sunshine = false;
	public $todaySunshineDuration = 0;
	public $yesterdaySunshineDuration = 0;
	public $totalSunshineDuration = 0;
	public $windSpeed = 0;
	public $windValueType = "AVERAGE_VALUE";
	
	function __construct($connection) {	
		parent::__construct($connection);
	}
	
	public function importRawData($rawdata){
		parent::importRawData($rawdata);

		foreach ($rawdata["functionalChannels"] as $functionalchannel) {
			if (strpos($functionalchannel["functionalChannelType"], "WEATHER_SENSOR") !== false) {
				$this->actualTemperature				= $functionalchannel["actualTemperature"];
				$this->humidity 						= $functionalchannel["humidity"];
				$this->illumination						= $functionalchannel["illumination"];
				$this->illuminationThresholdSunshine	= $functionalchannel["illuminationThresholdSunshine"];
				$this->storm							= $functionalchannel["storm"];
				$this->sunshine							= $functionalchannel["sunshine"];
				$this->todaySunshineDuration			= $functionalchannel["todaySunshineDuration"];
				$this->yesterdaySunshineDuration		= $functionalchannel["yesterdaySunshineDuration"];
				$this->totalSunshineDuration			= $functionalchannel["totalSunshineDuration"];
				$this->windSpeed						= $functionalchannel["windSpeed"];
				$this->windValueType					= $functionalchannel["windValueType"];
			}
		}	
	}
}
?>