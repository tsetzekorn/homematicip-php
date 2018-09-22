<?php
/*
This script is a working example how to authenticate with the Homematic IP Access Point. You can use it to create an auth token 
and the config.ini file needed for the Homematic IP classes. 
*/
include_once("HomematicIP.php");

// Get POST variable contents
if (isset($_POST["step"])) {
	$step = $_POST["step"];
} else {
	$step = "1";
}
if (isset($_POST["accesspoint"])) {
	$accesspoint = $_POST["accesspoint"];
}
if (isset($_POST["pin"])) {
	$pin = $_POST["pin"];
}
if (isset($_POST["uuid"])) {
	$uuid = $_POST["uuid"];
}

echo "<h1>Homematic IP Authentication Process</h1>";
$hidden = 'style="display:none;"';

switch ($step) {
	case "1": // Collect basic information for authentification
		echo('<h2>Step 1 - Preparing the Authentication Process</h2>');
		echo('<p>Enter the <strong>Access Point ID</strong> and PIN (if set) to be able to initiate the authentication request.</p>');	
		echo('<p>If done, proceed with the next step.</p>');
		$hidden = "";
		break;
	case "2": // Start authentification process
		$auth = New HmIP_Auth($accesspoint,$pin);
		echo('<h2>Step 2 - Initiating the Authentication Process</h2>');
		echo('<p>Press the <strong>blue button</strong> on the Access Point to accept the authentication request.</p>');
		echo('<p>If done, proceed with the next step.</p>');
		$uuid = $auth->connectionRequest();
		break;
	case "3": // Confirm authentification and create config.ini file
		$auth = New HmIP_Auth($accesspoint,$pin,$uuid);
		echo('<h2>Step 3 - Finish the Authentication Process</h2>');
		if ($auth->isRequestAcknowledged()) {
			echo "<p>Requesting and confirming Auth Token...</p>";
			$authcode = $auth->requestAuthToken();
			$clientid = $auth->confirmAuthToken();
		} else {
			die("<strong>ERROR</strong>: Please accept the authentication process on your Access Point and reload this page.");
		}	
		break;
}

if ($step < 3) {
	echo '
	<form method="post">
		<span '.$hidden.'>Access Point ID:<br></span>
		<span '.$hidden.'><input type="text" name="accesspoint" value="'.$accesspoint.'" required><br></span>
		<span '.$hidden.'>PIN (if set):<br></span>
		<span '.$hidden.'><input type="text" name="pin" value="'.$pin.'"><br></span>
		<input type="text" name="step" value="'.($step+1).'" style="display:none;">
		<input type="text" name="uuid" value="'.$uuid.'" style="display:none;">
		<br><input type="submit" value="Next Step">
	</form>
	';
} else {
	echo '<p>Create a file named <i>config.ini</i> in the home directory of the Homematic IP script and copy the following information:</p>';
	$config = '[api]\nauthcode = '.$authcode.'\naccesspoint = '.$accesspoint.'\n';
	echo '<pre>'.$config.'</pre>';
	echo '<p>You\'re done! :)</p>';
	
	// Write configuration to config.ini if possible
	try {
		$fp = fopen('config.ini', 'w');
		fwrite($fp, $config);
		fclose($fp);
	}
	catch(Exception $e) {
		echo 'File creation error: ' .$e->getMessage();
	}
}
?>