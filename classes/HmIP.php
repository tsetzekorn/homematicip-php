<?php
class HmIP {
	private $connection;
	private $home;
	private $rooms;
	
	function __construct($accesspoint_id="",$authtoken="") {
		if ($accesspoint_id == "" && $authtoken == "") {
			// Get Configuration via file
			$config = parse_ini_file(substr(dirname(__FILE__),0,strrpos(dirname(__FILE__),"/"))."/config.ini");
			$accesspoint_id 	= $config["accesspoint"];
			$authtoken 			= $config["authcode"];
		}	
		$this->connection 		= New HmIP_Connection($accesspoint_id,$authtoken);
	}
	
	public function getCurrentState($forceUpdate=false) {
		if (!is_array($this->home) || $forceUpdate) {
			$this->home = $this->connection->doRequest("/hmip/home/getCurrentState");
		}
		
		// Parse Room Information
		foreach ($this->home["home"]["metaGroups"] as $key => $roomid) {
			$this->rooms[$roomid]["id"] = $roomid;
			
			foreach ($this->home["groups"] as $id => $group) {
				if ($id == $roomid) {
					$this->rooms[$roomid]["label"] = $group["label"];
					foreach ($group["groups"] as $subgroup) {
						$this->rooms[$subgroup]["id"] = $subgroup;
						$this->rooms[$subgroup]["label"] = $group["label"];
					}
					break;					
				}
			}
		}

		// Assign Room Label to Devices
		foreach ($this->home["devices"] as $key => $device) {
			$this->home["devices"][$key]["room"] = $this->rooms[$device["functionalChannels"][0]["groups"][0]]["label"];
		}
		
        return $this->home;		
	}
	
	public function getDevice($deviceid, $forceUpdate=false){
		if (!is_array($this->home) || $forceUpdate) {
			$this->getCurrentState(true);
		}
		
		$result = false;
		foreach ($this->home["devices"] as $key => $device) {
			if ($key == $deviceid) {
				$class = HmIP_Device::getClassname($device["type"]);
				$result = New $class($this->connection);
				$result->ImportRawData($device);
				break;
			}
		}
		
		return $result;
	}
	
	public function getAllDevices($forceUpdate=false){
		if (!is_array($this->home) || $forceUpdate) {
			$this->getCurrentState(true);
		}
		
		$result = false;
		foreach ($this->home["devices"] as $key => $device) {
			$result[] = $this->getDevice($key);
		}
		
		return $result;
	}
	
	public function getAllDevicesByModelType($modelType,$forceUpdate=false){
		$devices = $this->getAllDevices($forceUpdate);
		
		$result = false;
		foreach ($devices as $device) {
			if (strtoupper($device->modelType) == strtoupper($modelType)) {
				$result[] = $device;
			}
		}
		return $result;
	}
	
	public function getAllDevicesByDeviceType($deviceType,$forceUpdate=false){
		$devices = $this->getAllDevices($forceUpdate);
		
		$result = false;
		foreach ($devices as $device) {
			if (strtoupper($device->deviceType) == strtoupper($deviceType)) {
				$result[] = $device;
			}
		}
		return $result;
	}
	
	public function getRooms($forceUpdate=false) {
		if (!is_array($this->home) || $forceUpdate) {
			$this->getCurrentState(true);
		}

		return $this->rooms;
	}
}
?>