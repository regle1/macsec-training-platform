<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ACTP - MACsec Config Maker</title>
  <link rel="stylesheet" href="css/main.css">
  <script src="js/main.js" defer></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <link href="https://css.gg/css" rel="stylesheet" />
  <link href="https://unpkg.com/css.gg/icons/icons.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/css.gg/icons/icons.css" rel="stylesheet"/>
</head>
<body>
  <div class="header">
    <h4 class="main-heading">ACTP - MACsec Scenario</h4>
    <button class="nav-button" id="viewDashboard">Dashboard</button>
    <button class="nav-button active-btn" id="viewTask">Scenario description</button>
    <button class="nav-button" id="viewNetworkLogs">Network Logs</button>
    <button class="submit-button" id="submitConfig">Start Simulation<i class="gg-arrow-long-right"></i></button>
  </div>
  <div class="default" id="dashboard">
    <div class="main-container">
      <div class="half left-container">
        <div class="window forth">
          <div class="ecu-container" id="ecu1">
            <div class="ecu-heading">
              <h4>ECU 1</h4>
              <p id="ecu-main-mac">0c:b3:d5:d5:00:00</p>
            </div>
            <div class="ecu-content">
              <div class="comm-channel collapsible" id="comm-channel1">

                <div class="ecu-container-heading collapsible-header">
                  <h5>Communication Channel</h5>
                  <button class="add-btn" id="addCommChannel">+</button>
                  <button class="minimize-btn" id="minimize">v</button>
                </div>

                <div class="collapsible-content">
                  <label for="comm-status">Channel Status:</label>
                  <select class="select" id="comm-status" name="comm-status">
                    <option value="" selected disabled>Select status</option>
                    <option value="up">up</option>
                    <option value="down">down</option>
                  </select>

                  <input type="hidden" id="channel-port" name="channel-port" value="1">

                  <label for="channel-ip">Subnet:</label>
                  <input type="text" id="channel-ip" name="channel-ip">

                  <label for="encryption-mode">Encryption Mode:</label>
                  <select class="select" id="encryption-mode" name="encryption-mode">
                    <option value="" selected disabled>Select mode</option>
                    <option value="on">on</option>
                    <option value="off">off</option>
                  </select>

                  <label for="cipher">Cipher-suite:</label>
                  <select class="select" id="cipher" name="cipher">
                    <option value="" selected disabled>Select cipher suite</option>
                    <option value="gcm-aes-128">gcm-aes-128</option>
                    <option value="gcm-aes-256">gcm-aes-256</option>
                  </select>

                  <label for="frame-validation">Frame validation mode:</label>
                  <select class="select" id="frame-validation" name="frame-validation">
                    <option value="" selected disabled>Select mode</option>
                    <option value="strict">strict</option>
                    <option value="check">check</option>
                    <option value="disabled">disabled</option>
                  </select>

                  <label for="replay-poretction">Replay protection mode:</label>
                  <select class="select" id="replay-poretction" name="replay-poretction">
                    <option value="" selected disabled>Select mode</option>
                    <option value="on">on</option>
                    <option value="off">off</option>
                  </select>

                  <label for="replay-window">Replay window:</label>
                  <input type="number" id="replay-window" name="replay-window">

                  <label for="key-name">Key name:</label>
                  <input id="key-name" name="key-name">

                  <label for="key">Key:</label>
                  <input id="key" name="key">

                  <div class="rx-channel collapsible" id="rx-channel1">
                    <div class="ecu-container-heading collapsible-header">
                      <h6>Rx channel</h6>
                      <button class="add-btn" id="addRxChannel">+</button>
                      <button class="minimize-btn" id="minimize">v</button>
                    </div> 
                    
                    <div class="collapsible-content collapsed">
                      <label for="tx-ecu1">Tx ECU:</label>
                      <select class="select" id="tx-ecu1" name="tx-ecu">
                        <option value="" disabled selected>Select an ECU</option>
                      </select>

                      <label for="tx-comm-channel1">Communication channel:</label>
                      <select class="select" id="tx-comm-channel1" name="comm-channel">
                        <option value="" selected disabled>Select an Tx ECU first</option>
                      </select>

                      <input type="hidden" id="tx-channel-port" name="tx-channel-port" value="">
                      
                      <label for="tx-mac1">Tx MAC Address:</label>
                      <input disabled id="tx-mac1" name="tx-mac">

                      <label for="tx-key-name">Tx Key name:</label>
                      <input disabled id="tx-key-name1" name="tx-key-name">

                      <label for="tx-key">Tx Key:</label>
                      <input disabled id="tx-key1" name="tx-key">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="window forth">
          <div class="ecu-container" id="ecu2">
            <div class="ecu-heading">
              <h4>ECU 2</h4>
              <p id="ecu-main-mac">0c:99:22:ee:00:00</p>
            </div>
            <div class="ecu-content">
              <div class="comm-channel collapsible" id="comm-channel1">

                <div class="ecu-container-heading collapsible-header">
                  <h5>Communication Channel</h5>
                  <button class="add-btn" id="addCommChannel">+</button>
                  <button class="minimize-btn" id="minimize">v</button>
                </div>

                <div class="collapsible-content">
                  <label for="comm-status">Channel Status:</label>
                  <select class="select" id="comm-status" name="comm-status">
                    <option value="" selected disabled>Select status</option>
                    <option value="up">up</option>
                    <option value="down">down</option>
                  </select>

                  <input type="hidden" id="channel-port" name="channel-port" value="1">

                  <label for="channel-ip">Subnet:</label>
                  <input type="text" id="channel-ip" name="channel-ip">

                  <label for="encryption-mode">Encryption Mode:</label>
                  <select class="select" id="encryption-mode" name="encryption-mode">
                    <option value="" selected disabled>Select mode</option>
                    <option value="on">on</option>
                    <option value="off">off</option>
                  </select>

                  <label for="cipher">Cipher-suite:</label>
                  <select class="select" id="cipher" name="cipher">
                    <option value="" selected disabled>Select cipher suite</option>
                    <option value="gcm-aes-128">gcm-aes-128</option>
                    <option value="gcm-aes-256">gcm-aes-256</option>
                  </select>

                  <label for="frame-validation">Frame validation mode:</label>
                  <select class="select" id="frame-validation" name="frame-validation">
                    <option value="" selected disabled>Select mode</option>
                    <option value="strict">strict</option>
                    <option value="check">check</option>
                    <option value="disabled">disabled</option>
                  </select>

                  <label for="replay-poretction">Replay protection mode:</label>
                  <select class="select" id="replay-poretction" name="replay-poretction">
                    <option value="" selected disabled>Select mode</option>
                    <option value="on">on</option>
                    <option value="off">off</option>
                  </select>

                  <label for="replay-window">Replay window:</label>
                  <input type="number" id="replay-window" name="replay-window">

                  <label for="key-name">Key name:</label>
                  <input id="key-name" name="key-name">

                  <label for="key">Key:</label>
                  <input id="key" name="key">


                  <div class="rx-channel collapsible" id="rx-channel1">
                    <div class="ecu-container-heading collapsible-header">
                      <h6>Rx channel</h6>
                      <button class="add-btn" id="addRxChannel">+</button>
                      <button class="minimize-btn" id="minimize">v</button>
                    </div> 
                    
                    <div class="collapsible-content collapsed">
                      <label for="tx-ecu1">Tx ECU:</label>
                      <select class="select" id="tx-ecu1" name="tx-ecu">
                        <option value="" disabled selected>Select an ECU</option>
                      </select>

                      <label for="tx-comm-channel1">Communication channel:</label>
                      <select class="select" id="tx-comm-channel1" name="comm-channel">
                        <option value="" selected disabled>Select an Tx ECU first</option>
                      </select>

                      <input type="hidden" id="tx-channel-port" name="tx-channel-port" value="">

                      <label for="tx-mac1">Tx MAC Address:</label>
                      <input disabled id="tx-mac1" name="tx-mac">

                      <label for="tx-key-name">Tx Key name:</label>
                      <input disabled id="tx-key-name1" name="tx-key-name">

                      <label for="tx-key">Tx Key:</label>
                      <input disabled id="tx-key1" name="tx-key">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="window forth">
          <div class="ecu-container" id="ecu3">
            <div class="ecu-heading">
              <h4>ECU 3</h4>
              <p id="ecu-main-mac">0c:69:c1:ff:00:00</p>
            </div>
            <div class="ecu-content">
              <div class="comm-channel collapsible" id="comm-channel1">

                <div class="ecu-container-heading collapsible-header">
                  <h5>Communication Channel</h5>
                  <button class="add-btn" id="addCommChannel">+</button>
                  <button class="minimize-btn" id="minimize">v</button>
                </div>

                <div class="collapsible-content">
                  <label for="comm-status">Channel Status:</label>
                  <select class="select" id="comm-status" name="comm-status">
                    <option value="" selected disabled>Select status</option>
                    <option value="up">up</option>
                    <option value="down">down</option>
                  </select>

                  <input type="hidden" id="channel-port" name="channel-port" value="1">

                  <label for="channel-ip">Subnet:</label>
                  <input type="text" id="channel-ip" name="channel-ip">

                  <label for="encryption-mode">Encryption Mode:</label>
                  <select class="select" id="encryption-mode" name="encryption-mode">
                    <option value="" selected disabled>Select mode</option>
                    <option value="on">on</option>
                    <option value="off">off</option>
                  </select>

                  <label for="cipher">Cipher-suite:</label>
                  <select class="select" id="cipher" name="cipher">
                    <option value="" selected disabled>Select cipher suite</option>
                    <option value="gcm-aes-128">gcm-aes-128</option>
                    <option value="gcm-aes-256">gcm-aes-256</option>
                  </select>

                  <label for="frame-validation">Frame validation mode:</label>
                  <select class="select" id="frame-validation" name="frame-validation">
                    <option value="" selected disabled>Select mode</option>
                    <option value="strict">strict</option>
                    <option value="check">check</option>
                    <option value="disabled">disabled</option>
                  </select>

                  <label for="replay-poretction">Replay protection mode:</label>
                  <select class="select" id="replay-poretction" name="replay-poretction">
                    <option value="" selected disabled>Select mode</option>
                    <option value="on">on</option>
                    <option value="off">off</option>
                  </select>

                  <label for="replay-window">Replay window:</label>
                  <input type="number" id="replay-window" name="replay-window">

                  <label for="key-name">Key name:</label>
                  <input id="key-name" name="key-name">

                  <label for="key">Key:</label>
                  <input id="key" name="key">


                  <div class="rx-channel collapsible" id="rx-channel1">
                    <div class="ecu-container-heading collapsible-header">
                      <h6>Rx channel</h6>
                      <button class="add-btn" id="addRxChannel">+</button>
                      <button class="minimize-btn" id="minimize">v</button>
                    </div> 
                    
                    <div class="collapsible-content collapsed">
                      <label for="tx-ecu1">Tx ECU:</label>
                      <select class="select" id="tx-ecu1" name="tx-ecu">
                        <option value="" disabled selected>Select an ECU</option>
                      </select>

                      <label for="tx-comm-channel1">Communication channel:</label>
                      <select class="select" id="tx-comm-channel1" name="comm-channel">
                        <option value="" selected disabled>Select an Tx ECU first</option>
                      </select>

                      <input type="hidden" id="tx-channel-port" name="tx-channel-port" value="">

                      <label for="tx-mac1">Tx MAC Address:</label>
                      <input disabled id="tx-mac1" name="tx-mac">

                      <label for="tx-key-name">Tx Key name:</label>
                      <input disabled id="tx-key-name1" name="tx-key-name">

                      <label for="tx-key">Tx Key:</label>
                      <input disabled id="tx-key1" name="tx-key">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="window forth">
          <div class="ecu-container" id="ecu4">
            <div class="ecu-heading">
              <h4>ECU 4</h4>
              <p id="ecu-main-mac">0c:42:28:71:00:00</p>
            </div>
            <div class="ecu-content">
              <div class="comm-channel collapsible" id="comm-channel1">

                <div class="ecu-container-heading collapsible-header">
                  <h5>Communication Channel</h5>
                  <button class="add-btn" id="addCommChannel">+</button>
                  <button class="minimize-btn" id="minimize">v</button>
                </div>

                <div class="collapsible-content">
                  <label for="comm-status">Channel Status:</label>
                  <select class="select" id="comm-status" name="comm-status">
                    <option value="" selected disabled>Select status</option>
                    <option value="up">up</option>
                    <option value="down">down</option>
                  </select>

                  <input type="hidden" id="channel-port" name="channel-port" value="1">

                  <label for="channel-ip">Subnet:</label>
                  <input type="text" id="channel-ip" name="channel-ip">

                  <label for="encryption-mode">Encryption Mode:</label>
                  <select class="select" id="encryption-mode" name="encryption-mode">
                    <option value="" selected disabled>Select mode</option>
                    <option value="on">on</option>
                    <option value="off">off</option>
                  </select>

                  <label for="cipher">Cipher-suite:</label>
                  <select class="select" id="cipher" name="cipher">
                    <option value="" selected disabled>Select cipher suite</option>
                    <option value="gcm-aes-128">gcm-aes-128</option>
                    <option value="gcm-aes-256">gcm-aes-256</option>
                  </select>

                  <label for="frame-validation">Frame validation mode:</label>
                  <select class="select" id="frame-validation" name="frame-validation">
                    <option value="" selected disabled>Select mode</option>
                    <option value="strict">strict</option>
                    <option value="check">check</option>
                    <option value="disabled">disabled</option>
                  </select>

                  <label for="replay-poretction">Replay protection mode:</label>
                  <select class="select" id="replay-poretction" name="replay-poretction">
                    <option value="" selected disabled>Select mode</option>
                    <option value="on">on</option>
                    <option value="off">off</option>
                  </select>

                  <label for="replay-window">Replay window:</label>
                  <input type="number" id="replay-window" name="replay-window">

                  <label for="key-name">Key name:</label>
                  <input id="key-name" name="key-name">

                  <label for="key">Key:</label>
                  <input id="key" name="key">


                  <div class="rx-channel collapsible" id="rx-channel1">
                    <div class="ecu-container-heading collapsible-header">
                      <h6>Rx channel</h6>
                      <button class="add-btn" id="addRxChannel">+</button>
                      <button class="minimize-btn" id="minimize">v</button>
                    </div> 
                    
                    <div class="collapsible-content collapsed">
                      <label for="tx-ecu1">Tx ECU:</label>
                      <select class="select" id="tx-ecu1" name="tx-ecu">
                        <option value="" disabled selected>Select an ECU</option>
                      </select>

                      <label for="tx-comm-channel1">Communication channel:</label>
                      <select class="select" id="tx-comm-channel1" name="comm-channel">
                        <option value="" selected disabled>Select an Tx ECU first</option>
                      </select>

                      <input type="hidden" id="tx-channel-port" name="tx-channel-port" value="">

                      <label for="tx-mac1">Tx MAC Address:</label>
                      <input disabled id="tx-mac1" name="tx-mac">

                      <label for="tx-key-name">Tx Key name:</label>
                      <input disabled id="tx-key-name1" name="tx-key-name">

                      <label for="tx-key">Tx Key:</label>
                      <input disabled id="tx-key1" name="tx-key">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        
      </div>

      <div class="half right-container">
        <div class="top topology-container">
          <img src="media/topology.png" alt="" class="topology-image">
        </div>
        <div class="bottom console-container">
          <div class="console-content" id="console-content">
            <p>System: Welcome to ACPT MACsec Scenario</p>
          </div>
        </div>
      </div>



    </div>
  
  </div>

  <div class="default" id="network-logs">
    <div class="main-container"> 

      <div class="half left-container logs-coneatiner">
        <div class="info-box">
          <h4>ECU 1 traffic log</h4>
          <div class="inner-logs">
            <div class="sent-logs logs">
              <h5>Sent messages</h5>
              <div id="sent-ecu1">
              </div>
            </div>
            <div class="received-logs logs">
              <h5>Received messages</h5>
              <div id="received-ecu1">
              </div>
            </div>
          </div>
        </div>

        <div class="info-box">
          <h4>ECU 3 traffic log</h4>
          <div class="inner-logs">
            <div class="sent-logs logs">
              <h5>Sent messages</h5>
              <div id="sent-ecu3">
              </div>
            </div>
            <div class="received-logs logs">
              <h5>Received messages</h5>
              <div id="received-ecu3">
              </div>
            </div>
          </div>
        </div>

        <div class="info-box">
            <h4>Malicious device 1</h4>
            <div class="inner-logs">
              <div class="sent-logs logs">
                <h5>Sent messages</h5>
                <div id="sent-mim1">
                </div>
              </div>
            </div>
          </div>
      </div>
      
      <div class="half right-container logs-coneatiner">
        <div class="info-box">
          <h4>ECU 2 traffic log</h4>
          <div class="inner-logs">
            <div class="sent-logs logs">
              <h5>Sent messages</h5>
              <div id="sent-ecu2">
              </div>
            </div>
            <div class="received-logs logs">
              <h5>Received messages</h5>
              <div id="received-ecu2">
              </div>
            </div>
          </div>
        </div>

        <div class="info-box">
          <h4>ECU 4 traffic log</h4>
          <div class="inner-logs">
            <div class="sent-logs logs">
              <h5>Sent messages</h5>
              <div id="sent-ecu4">
              </div>
            </div>
            <div class="received-logs logs">
              <h5>Received messages</h5>
              <div id="received-ecu4">
              </div>
            </div>
          </div>
        </div>

        <div class="info-box">
          <h4>Malicious device 2</h4>
          <div class="inner-logs">
            <div class="sent-logs logs">
              <h5>Sent messages</h5>
              <div id="sent-mim2">
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

  <div class="default active" id="task">
    <div class="main-container"> 
      <div class="half left-container">
        <div class="info-box">
          <h3>Introduction</h3>

            <h4>Why Learn About MACsec?</h4>
          <p>Due to the increasaing demand for Ethernet netowors in vehicles, it is neccessary to learn how to use tools 
            to preopperly protect the network from attacks and MACsec is one of them. </p>

        </div>

        <div class="info-box">
          <h3>Scenario description</h4>
          <p>You are a cybersecurity specialist and you have been tasked with securing a in-vehicle Ethernet network using MACsec. The network consists of 4 ECUs that you will be able to config</p>
          <p>Task details...</p>
        </div>
        
        
      </div>
      <div class="half right-container">

      </div>
    </div>  
  </div>

  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
