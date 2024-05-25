import socket
import time
import random
import requests

ECU_ID = "ecu1"
TIMER = 10
PORT = 1111
FILE_NAME = "/home/ftp_client/acpt/sent-" + ECU_ID + ".txt"
URL = "https://acpt.egle.cloud/php/upload-received.php"

def sendFile():
    with open(FILE_NAME, "rb") as file:
        files = {"file": (FILE_NAME, file)}
        data = {"ecuId": ECU_ID}
        response = requests.post(URL, files=files, data=data)
        print(response.text)

def getRecipients(filename):
    recipients = []
    with open(filename, 'r') as file:
        for line in file:
            recipients.append(line.strip())
    return recipients

def formatTimestamp(startTime, currentTime):
    elapsedTime = currentTime - startTime
    seconds = int(elapsedTime)
    microseconds = int((elapsedTime - seconds) * 1_000_000)
    return f"[{seconds}.{microseconds:07d}]"

def generateMmessage():
    speed = random.randint(10, 60)  
    return f"Vehicle speed = {speed} | {ECU_ID}"

def main():
    cs = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
    recipients = getRecipients("/home/ftp_client/acpt/ecu-recipients-" + ECU_ID + ".txt")

    with open(FILE_NAME, "w") as file:
        try:
            startTime = time.time()
            while time.time() - startTime < TIMER:
                payload = generateMmessage()
                timestamp = formatTimestamp(startTime, time.time())
                message = f"{timestamp} {payload}"
                file.write(f"{message}\n")
                for ip in recipients:
                    try:
                        cs.sendto(message.encode('utf-8'), (ip, PORT))
                        print(f"{ip} -> {message} ")
                    except Exception as e:
                        print(f"Failed to send message to {ip}: {e}")
                time.sleep(1)
        finally:
            cs.close()

    sendFile()

if __name__ == "__main__":
    main()
