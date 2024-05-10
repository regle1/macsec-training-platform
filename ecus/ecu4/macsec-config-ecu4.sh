#! /bin/bash
execute_silently() {
"$@" > /dev/null 2>&1
if [ $? -ne 0 ]; then
echo "Failed to setup MACsec | error"
exit 1
fi
}
# Communication Channel 1:

# Create the MACsec device on top of the physical one
execute_silently sudo ip link add link ens3 macsec1 type macsec port 1 cipher gcm-aes-128 encrypt off replay on window 3 validate strict

# Configure the Transmit SA and keys
execute_silently sudo ip macsec add macsec1 tx sa 0 pn 1 on key 44 44444444444444444444444444444444

# Configure the Receive Channel:
execute_silently sudo ip macsec add macsec1 rx address 0c:69:c1:ff:00:00 port 1
execute_silently sudo ip macsec add macsec1 rx address 0c:69:c1:ff:00:00 port 1 sa 0 pn 1 on key 33 33333333333333333333333333333333

# Set the IP and bring the interface UP 
execute_silently sudo ifconfig macsec1 10.0.2.2/16
execute_silently sudo ip link set dev macsec1 up

echo "MACsec setup completed successfully | success"