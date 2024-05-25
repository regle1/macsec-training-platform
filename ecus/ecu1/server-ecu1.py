import socket
import time
import requests

ECU_ID = "ecu1"
PORT = 1111
TIMER = 15
FILE_NAME = "/home/ftp_client//acpt/received-" + ECU_ID + ".txt"
URL = "https://acpt.egle.cloud/php/upload-received.php"

def sendFile():
    with open(FILE_NAME, "rb") as file:
        files = {"file": (FILE_NAME, file)}
        data = {"ecuId": ECU_ID}
        response = requests.post(URL, files=files, data=data)
        print(response.text)

def main():
    s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
    s.bind(("", PORT))
    print(f"Listening on port {PORT}")
    s.settimeout(1)

    startTime = None

    try:
        with open(FILE_NAME, "w") as f:
            while True:
                try:
                    data, addr = s.recvfrom(1024)
                    message = data.decode("utf-8")
                    
                    print(f"{message}")
                    f.write(message + "\n")
                    f.flush()

                    if startTime is None:
                        startTime = time.time()
                except:
                    pass

                if startTime and (time.time() - startTime >= TIMER):
                    break
    finally:
        s.close()
        sendFile()

if __name__ == "__main__":
    main()
