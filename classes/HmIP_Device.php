<?php
class HmIP_Device extends HmIP_GenericObject {
	// This class represents a generic Homematic IP device

    public $id = "";
	public $homeId = "";
	public $room = "";
	public $label = "";
	public $lastStatusUpdate = "";
	public $deviceType = "";
	public $updateState = "";
	public $firmwareVersion = "";
	public $availableFirmwareVersion = "";
	public $unreach = "";
	public $lowBat = "";
	public $routerModuleSupported = false;
	public $routerModuleEnabled = false;
	public $modelType = "";
	public $modelId = 0;
	public $oem = "";
	public $manufacturerCode = 0;
	public $serializedGlobalTradeItemNumber = "";
	public $rssiDeviceValue = 0;
	public $rssiPeerValue = 0;
	public $dutyCycle = false;
	public $configPending = false;
	
	function __construct($connection) {	
		parent::__construct($connection);
	}
	
	public function importRawData($rawdata){
		$this->id 								= $rawdata["id"];
		$this->homeId 							= $rawdata["homeId"];
		$this->room 							= $rawdata["room"];
		$this->label 							= $rawdata["label"];
		$this->lastStatusUpdate 				= $rawdata["lastStatusUpdate"];
		$this->deviceType 						= $rawdata["type"];
		$this->updateState 						= $rawdata["updateState"];
		$this->firmwareVersion 					= $rawdata["firmwareVersion"];
		$this->availableFirmwareVersion 		= $rawdata["availableFirmwareVersion"];
		$this->modelType 						= $rawdata['modelType'];
		$this->modelId 							= $rawdata['modelId'];
		$this->oem 								= $rawdata['oem'];
		$this->manufacturerCode 				= $rawdata['manufacturerCode'];
		$this->serializedGlobalTradeItemNumber 	= $rawdata['serializedGlobalTradeItemNumber'];

		foreach ($rawdata["functionalChannels"] as $functionalchannel) {
			if (strpos($functionalchannel["functionalChannelType"], "DEVICE_BASE") !== false || strpos($functionalchannel["functionalChannelType"], "DEVICE_OPERATIONLOCK") !== false) {
				$this->unreach 					= $functionalchannel["unreach"];
				$this->lowBat 					= $functionalchannel["lowBat"];
				$this->routerModuleSupported 	= $functionalchannel["routerModuleSupported"];
				$this->routerModuleEnabled 		= $functionalchannel["routerModuleEnabled"];
				$this->rssiDeviceValue 			= $functionalchannel["rssiDeviceValue"];
				$this->rssiPeerValue 			= $functionalchannel["rssiPeerValue"];
				$this->dutyCycle 				= $functionalchannel["dutyCycle"];
				$this->configPending 			= $functionalchannel["configPending"];				
			}
		}
	}
	
	public function setLabel($label){
		$data = array("deviceId" => $this->id, "label" => $label);
		return parent::doRequest("/hmip/device/setDeviceLabel", $data);
	}
	
	public function isUpdateApplicable(){
		$data = array("deviceId" => $this->id);
		$result = parent::doRequest("/hmip/device/isUpdateApplicable", $data);
		if (is_array($result)) {
			return $result["errorCode"];
		} else {
			return true;
		}
	}
	
	public function authorizeUpdate(){
		$data = array("deviceId" => $this->id);
		return parent::doRequest("/hmip/device/authorizeUpdate", $data);
	}

	public function deleteDevice(){
		$data = array("deviceId" => $this->id);
		return parent::doRequest("/hmip/device/deleteDevice", $data);
	}
	
	public function setRouterModuleEnabled($enabled = true) {
		if ($this->routerModuleSupported == false) {
			return false;
		}

		$data = array("deviceId" => $this->id, "channelIndex" => 0, "routerModuleEnabled" => $enabled);
		$result = parent::doRequest("/hmip/device/configuration/setRouterModuleEnabled", $data);
		
		if ($result == ""){
			return true;
		} else {
			return $result["errorCode"];
		}
	}	
	
    public static function getClassname($devicetype) {
        switch($devicetype) {
			case "DEVICE": return "HmIP_Device"; break;
			case "HEATING_THERMOSTAT": return "HmIP_HeatingThermostat"; break;
			case "SHUTTER_CONTACT": return "HmIP_ShutterContact"; break;
			case "SHUTTER_CONTACT_INVISIBLE": return "HmIP_ShutterContact"; break;
			case "WALL_MOUNTED_THERMOSTAT_PRO": return "HmIP_TemperatureHumiditySensorDisplay"; break;
			case "BRAND_WALL_MOUNTED_THERMOSTAT": return "HmIP_TemperatureHumiditySensorDisplay"; break;
			//case "SMOKE_DETECTOR": return "HmIP_SmokeDetector"; break;
			//case "FLOOR_TERMINAL_BLOCK_6": return "HmIP_FloorTerminalBlock6"; break;
			case "PLUGABLE_SWITCH_MEASURING": return "HmIP_PlugableSwitchMeasuring"; break;
			case "TEMPERATURE_HUMIDITY_SENSOR_DISPLAY": return "HmIP_TemperatureHumiditySensorDisplay"; break;
			//case "ROOM_CONTROL_DEVICE": return "HmIP_TemperatureHumiditySensorDisplay"; break;
			case "TEMPERATURE_HUMIDITY_SENSOR": return "HmIP_TemperatureHumiditySensorWithoutDisplay"; break;
			case "PUSH_BUTTON": return "HmIP_PushButton"; break;
			case "PUSH_BUTTON_6": return "HmIP_PushButton"; break;
			//case "ALARM_SIREN_INDOOR": return "HmIP_AlarmSirenIndoor"; break;
			//case "MOTION_DETECTOR_INDOOR": return "HmIP_MotionDetectorIndoor"; break;
			//case "KEY_REMOTE_CONTROL_ALARM": return "HmIP_KeyRemoteControlAlarm"; break;
			case "PLUGABLE_SWITCH": return "HmIP_PlugableSwitch"; break;
			case "FULL_FLUSH_SHUTTER": return "HmIP_FullFlushShutter"; break;
			case "BRAND_SHUTTER": return "HmIP_FullFlushShutter"; break;
			//case "PRECENCE_DETECTOR_INDOOR": return "HmIP_PresenceDetectorIndoor"; break;
			//case "PLUGGABLE_DIMMER": return "HmIP_PluggableDimmer"; break;
			case "BRAND_SWITCH_MEASURING": return "HmIP_BrandSwitchMeasuring"; break;
			//case "PRINTED_CIRCUIT_BOARD_SWITCH_BATTERY": return "HmIP_PrintedCircuitBoardSwitchBattery"; break;
			//case "TEMPERATURE_HUMIDITY_SENSOR_OUTDOOR": return "HmIP_TemperatureHumiditySensorOutdoor"; break;
			case "WEATHER_SENSOR_PRO": return "HmIP_WeatherSensorPro"; break;
			case "WEATHER_SENSOR_PLUS": return "HmIP_WeatherSensorPlus"; break;
			case "WEATHER_SENSOR_BASIC": return "HmIP_WeatherSensorBasic"; break;
			default: return "HmIP_Device";
		}
    }	
}

?>