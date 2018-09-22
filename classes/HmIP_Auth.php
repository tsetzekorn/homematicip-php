<?php
class HmIP_Auth{
	private $pin;
	private $connection;
	public  $accesspoint;
	public  $uuid;
	public  $authtoken;
	public  $clientid;
	
	function __construct($accesspoint_id,$pin="",$uuid="") {	
		$this->connection = New HmIP_Connection($accesspoint_id);
		$this->accesspoint = $accesspoint_id;
		if ($uuid == "") {
			$this->uuid = $this->generateUuid4();
		} else {
			$this->uuid = $uuid;	
		}
		$this->pin = $pin;
	}
	
	public function connectionRequest($devicename="homematicip-php"){
		$data = array("deviceId" => $this->uuid, "deviceName" => $devicename, "sgtin" => $this->accesspoint);
		if ($this->pin <> "") {
			$this->connection->postheader[] = $this->pin;
		}
		$result = $this->connection->doRequest("/hmip/auth/connectionRequest", $data);		
        return $this->uuid;
	}
	
	public function isRequestAcknowledged(){
		$data = array("deviceId" => $this->uuid);
		$result = $this->connection->doRequest("/hmip/auth/isRequestAcknowledged", $data);		
		return $result["statusCode"] == 200;
	}
	
	public function requestAuthToken(){
		$data = array("deviceId" => $this->uuid);
		$result = $this->connection->doRequest("/hmip/auth/requestAuthToken", $data);
		$this->authtoken = $result["authToken"]; 
		return $this->authtoken;
	}
	
	public function confirmAuthToken($authtoken = ""){
		if ($authtoken == "") {
			$authtoken = $this->authtoken;
		}
		$data = array("deviceId" => $this->uuid, "authToken" => $authtoken);
		$result = $this->connection->doRequest("/hmip/auth/confirmAuthToken", $data);
		$this->clientid = $result["clientId"]; 
		return $this->clientid;
	}
	
	private function generateUuid4(){
		return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
			// 32 bits for "time_low"
			mt_rand(0, 0xffff), mt_rand(0, 0xffff),
	
			// 16 bits for "time_mid"
			mt_rand(0, 0xffff),

			// 16 bits for "time_hi_and_version",
			// four most significant bits holds version number 4
			mt_rand(0, 0x0fff) | 0x4000,

			// 16 bits, 8 bits for "clk_seq_hi_res",
			// 8 bits for "clk_seq_low",
			// two most significant bits holds zero and one for variant DCE1.1
			mt_rand(0, 0x3fff) | 0x8000,

			// 48 bits for "node"
			mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
		);
	}
}
?>