<?php
include "HomematicIP.php";

$hmip = new HmIP();
$devices = $hmip->getAllDevices();

echo "<h1>List of all Homematic IP Devices</h1>";

foreach($devices as $device) {
	$id_formated = '';
	for ($i = 0; $i < ceil(strlen($device->id) / 4); $i ++) $id_formated .= substr($device->id, $i * 4, 4).'-';
	$id_formated = substr($id_formated, 0, strlen($id_formated)-5) . "<b>" . substr($id_formated, strlen($id_formated)-5, 4) . "</b>";
	
	echo "<div style='width:160px;height:325px;border-style:solid;border-width:1px;margin:5px;padding:5px;float:left;flex-wrap:wrap;'>";
	echo "<center><b>" . $device->room ."</b></center>";
	echo "<img src='https://chart.googleapis.com/chart?cht=qr&chs=150x150&chl=".$device->id."'><br>";
	echo $device->modelType . "<br><br>" . $id_formated . "<br><br>" . $device->label;
	echo "</div>";
}
?>