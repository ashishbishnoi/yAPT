
<?php
$card = shell_exec('iw dev | awk \'$1=="Interface"{print $2}\' | sed -e \'2d\' ');
$message = "Monitor mode not  enabled";
$moncheck = shell_exec('iwconfig wlan1 | grep Monitor | sed -e "s/Mode:/ /" | awk \'{print $1}\' | cut -b 5');
$attack = shell_exec("sudo timeout 18 airodump-ng wlan1 &> file.txt 2>&1");
	if ($moncheck == 1) {
		echo "<pre>$attack</pre>";
	}
	else {
	echo "<pre>$message</pre>";
	}
?>
