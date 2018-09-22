<?php
class HmIP_Connection{
    private $authtoken;
	private $accesspoint_id;
	private $clientauth;

	private $hmiphost;
	public  $postheader;
	private $poststring;
	
	function __construct($accesspoint_id,$authtoken="") {
		$this->accesspoint_id 	= strtoupper(preg_replace("/[^a-fA-F0-9 ]/","",$accesspoint_id));
		$this->authtoken 		= $authtoken;
		$this->clientauth 		= strtoupper(hash("sha512",utf8_encode($this->accesspoint_id."jiLpVitHvWnIGD1yo7MA")));
		$this->poststring 		= '{
									"id": "'.$this->accesspoint_id.'", 
									"clientCharacteristics": {
										"deviceManufacturer": "none", 
										"osVersion": "3.2.40", 
										"applicationVersion": "1.0", 
										"language": "en_US", 
										"apiVersion": "10", 
										"applicationIdentifier": "homematicip-python", 
										"osType": "Linux", 
										"deviceType": "Computer"
									}
								   }';
		$this->postheader 		= array(                                                                          
									'content-type: application/json',                                                                                
									'accept: application/json', 
									'VERSION: 12',									
									'CLIENTAUTH: ' . $this->clientauth
								  );
		if ($this->authtoken <> "") {
			$this->postheader[] = 'AUTHTOKEN: ' . $this->authtoken; 
		}
		$this->hmiphost			= $this->lookupServer();
	}

    public function doRequest($path,$body="") {
		$ch = curl_init();
		if (strpos($path,"http://") === false && strpos($path,"http://") === false) {
			$path = $this->hmiphost . $path;
		}
		if ($body <> "") {
			$poststring = json_encode($body);
		} else {
			$poststring = $this->poststring;
		}
		curl_setopt($ch, CURLOPT_URL, $path);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
		curl_setopt($ch, CURLOPT_POSTFIELDS, $poststring);                                                                  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->postheader);

		$result = json_decode(curl_exec($ch),true);
		$result["statusCode"] = curl_getinfo($ch,CURLINFO_HTTP_CODE);

		curl_close($ch);
 
		return $result;
    }
	
	private function lookupServer() {
		$result = $this->doRequest("https://lookup.homematic.com:48335/getHost");
        return $result["urlREST"];
	}	
}

?>