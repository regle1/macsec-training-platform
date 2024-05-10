// Call the function to update the select field with available ECUs
updateTxEcuSelect();

updateEventListeners();

document.addEventListener("DOMContentLoaded", function() {
  const navButtons = document.querySelectorAll('.nav-button');
  navButtons.forEach(navButton => {
    navButton.addEventListener('click', function() {
      let viewId = ""
      switch (this.id) {
        case "viewDashboard":
          viewId = "dashboard"
          break;
        case "viewNetworkLogs":
          viewId = "network-logs"
          break;
        case "viewTask":
          viewId = "task"
          break;
      }

      const activeButton = document.querySelector('.active-btn')
      const newActiveButton = document.getElementById(this.id)
      const activeView = document.querySelector('.active')
      const newActiveView = document.getElementById(viewId)

      activeButton.classList.remove('active-btn');
      newActiveButton.classList.add('active-btn');
      activeView.classList.remove('active');
      newActiveView.classList.add('active');



    })
  })

})

// Function to remove Rx channel
function removeRxChannel(rxChannel) {
  rxChannel.parentNode.removeChild(rxChannel);
}

// Function to remove communication channel
function removeCommChannel(commChannel) {
  commChannel.parentNode.removeChild(commChannel);
}

// Add event listener for the remove button
document.addEventListener('click', function(event) {
  if (event.target.classList.contains('remove-btn')) {
    var channel = event.target.closest('.rx-channel, .comm-channel');
    if (channel) {
      if (channel.classList.contains('rx-channel')) {
        removeRxChannel(channel);
      } else if (channel.classList.contains('comm-channel')) {
        removeCommChannel(channel); 
      }
    }
  } else if (event.target.classList.contains('minimize-btn')) {
    const content = event.target.parentElement.nextElementSibling;
    content.classList.toggle('collapsed');
  }
});


document.addEventListener('click', function(event) {
  if (event.target.id === 'addRxChannel') {
    var commChannel = event.target.closest('.collapsible-content');
    if (commChannel) {
      var originalRxChannel = commChannel.querySelector('.rx-channel');

      var newRxChannel = originalRxChannel.cloneNode(true);

      // Find the count of Rx channels within the current communication channel
      var rxChannels = commChannel.querySelectorAll('.rx-channel');
      var lastRxChannelNumber = rxChannels.length;

      // Update ID for new Rx channel
      var newRxChannelId = 'rx-channel' + (lastRxChannelNumber + 1);
      newRxChannel.id = newRxChannelId;

      // Add dynamic class
      newRxChannel.classList.add('dynamic');

      // Remove any existing remove and add button
      var existingRemoveBtn = newRxChannel.querySelector('.remove-btn');
      if (existingRemoveBtn) {
          existingRemoveBtn.parentNode.removeChild(existingRemoveBtn);
      }

      var existingMinimizeBtn = newRxChannel.querySelector('.minimize-btn');
      if (existingMinimizeBtn) {
        existingMinimizeBtn.parentNode.removeChild(existingMinimizeBtn);
      }
      
      var existingAddBtn = newRxChannel.querySelector('.add-btn');
      if (existingAddBtn) {
        existingAddBtn.parentNode.removeChild(existingAddBtn);
      }

      // Reset fields
      var existingTxKeyName = newRxChannel.querySelector('input[name="tx-key-name"]');
      var existingTxKey = newRxChannel.querySelector('input[name="tx-key"]');
      var existingTxMac = newRxChannel.querySelector('input[name="tx-mac"]');
      var existingCommChannel = newRxChannel.querySelector('select[name="comm-channel"]');
      

      existingTxKeyName.value = "";
      existingTxKey.value = "";
      existingCommChannel.innerHTML = "";
      existingTxMac.value = "";

      // Create a default option
      var defaultOption = document.createElement('option');
      defaultOption.value = "";
      defaultOption.text = 'Select an Tx ECU first';
      defaultOption.disabled = true;
      defaultOption.selected = true;
      existingCommChannel.appendChild(defaultOption);


      // Add remove button
      var removeBtn = document.createElement('button');
      removeBtn.textContent = '-';
      removeBtn.className = 'remove-btn';

      newRxChannel.querySelector('.ecu-container-heading').appendChild(removeBtn);

      // Add minimize button
      var minimizeBtn = document.createElement('button');
      minimizeBtn.textContent = 'v';
      minimizeBtn.className = 'minimize-btn';
      minimizeBtn.id = 'minimize';

      newRxChannel.querySelector('.ecu-container-heading').appendChild(minimizeBtn);

      commChannel.appendChild(newRxChannel);

      // Update event listeners
      updateEventListeners();
    }
  }
});

document.addEventListener('click', function(event) {
  if (event.target.id === 'addCommChannel') {
    // Find the closest .ecu-container element to the clicked button
    var ecuContainer = event.target.closest('.ecu-content');
    if (ecuContainer) {
      var originalCommChannel = ecuContainer.querySelector('.comm-channel');

      var newCommChannel = originalCommChannel.cloneNode(true);

      // Find the count of communication channels within the ecuContainer
      var commChannels = ecuContainer.querySelectorAll('.comm-channel');
      var lastCommChannelNumber = commChannels.length;
      var portNumber = commChannels.length + 1; // This will be the new port number for the cloned channel

      // Update ID for new communication channel nad set the port
      var newCommChannelId = 'comm-channel' + (lastCommChannelNumber + 1);
      newCommChannel.id = newCommChannelId;
      newCommChannel.querySelector('input[name="channel-port"]').value = portNumber; // Set the new port number

      // Remove dynamically created Rx channelss
      newCommChannel.querySelectorAll('.dynamic').forEach(function(rxChannel) {
        rxChannel.parentNode.removeChild(rxChannel);
      });

      // Remove any existing remove button
      var existingRemoveBtn = newCommChannel.querySelector('.remove-btn');
      if (existingRemoveBtn) {
        existingRemoveBtn.parentNode.removeChild(existingRemoveBtn);
      }

      var existingMinimizeBtn = newCommChannel.querySelector('.minimize-btn');
      if (existingMinimizeBtn) {
        existingMinimizeBtn.parentNode.removeChild(existingMinimizeBtn);
      }

      var existingAddBtn = newCommChannel.querySelector('.add-btn');
      if (existingAddBtn) {
        existingAddBtn.parentNode.removeChild(existingAddBtn);
      }

      var existingSubnet = newCommChannel.querySelector('input[name="channel-ip"]');
      var existingKeyName = newCommChannel.querySelector('input[name="key-name"]');
      var existingKey = newCommChannel.querySelector('input[name="key"]');
      var existingReplayWindow = newCommChannel.querySelector('input[name="replay-window"]');

      var existingTxKeyName = newCommChannel.querySelector('input[name="tx-key-name"]');
      var existingTxKey = newCommChannel.querySelector('input[name="tx-key"]');
      var existingTxMac = newCommChannel.querySelector('input[name="tx-mac"]');

      existingSubnet.value = "";
      existingKeyName.value = "";
      existingKey.value = "";
      existingReplayWindow.value = "";

      existingTxKeyName.value = "";
      existingTxKey.value = "";
      existingTxMac.value = "";

      // Add remove button
      var removeBtn = document.createElement('button');
      removeBtn.textContent = '-';
      removeBtn.className = 'remove-btn';
      newCommChannel.querySelector('.ecu-container-heading').appendChild(removeBtn);

      // Add minimize button
      var minimizeBtn = document.createElement('button');
      minimizeBtn.textContent = 'v';
      minimizeBtn.className = 'minimize-btn';
      minimizeBtn.id = 'minimize';

      newCommChannel.querySelector('.ecu-container-heading').appendChild(minimizeBtn);

      ecuContainer.appendChild(newCommChannel);

      

      // Update event listeners
      updateEventListeners();
    }
  }
});

function updateEventListeners() {

  // Add event listener for change event on Rx ECU select boxes
  document.querySelectorAll('select[name="tx-ecu"]').forEach(function(select) {
    select.addEventListener('change', function() {
      var selectedEcuId = this.value;
      var currentCommChannelSelect = this.closest('.rx-channel').querySelector('select[name="comm-channel"]');
      updateCommChannelOptions(selectedEcuId, currentCommChannelSelect);
    });
  });

  // Add event listener for change event on Comm Channel select boxes
  document.querySelectorAll('select[name="comm-channel"]').forEach(function(select) {
    select.addEventListener('change', function() {
  
      var selectedCommChannelId = this.value;
      
      // Get the Tx ECU select element within the closest .rx-channel
      var txEcuSelect = this.closest('.rx-channel').querySelector('select[name="tx-ecu"]');
      var txKeyName = this.closest('.rx-channel').querySelector('input[name="tx-key-name"]');
      var txKey = this.closest('.rx-channel').querySelector('input[name="tx-key"]');
      var txMac = this.closest('.rx-channel').querySelector('input[name="tx-mac"]');
      var txPort = this.closest('.rx-channel').querySelector('input[name="tx-channel-port"]');

      // Get the selected ECU ID
      var selectedEcuId = txEcuSelect.value;
  
      // Call the function to set the values for the fields
      setCommChannelKeyInfo(selectedEcuId, selectedCommChannelId, txKeyName, txKey, txMac, txPort);
  
    });
  });

}

function setCommChannelKeyInfo(ecuId, commChannelId, txKeyName, txKey, txMac, txPort) {
  var commChannel = document.querySelector(`#${ecuId} .comm-channel#${commChannelId}`);
  if (commChannel) {
    var keyName = commChannel.querySelector('input[name="key-name"]').value;
    var key = commChannel.querySelector('input[name="key"]').value;
    var port = commChannel.querySelector('input[name="channel-port"]').value;
    var ecuMac = commChannel.closest(`#${ecuId}`).querySelector('#ecu-main-mac').textContent;

    if (keyName.length !== 0) {
      txKeyName.value = keyName;
    } else {
      txKeyName.value = "-- No key name found --";
    }

    if (key.length !== 0) {
      txKey.value = key;
    } else {
      txKey.value = "-- No key found --";
    }

    if (port.length !== 0) {
      txPort.value = port;
    } else {
      txPort.value = "-- No port found --";
    }
    
    txMac.value = ecuMac;


  } else {
    console.log("Error: Communication Channel not found!")
  
  }
}

function updateTxEcuSelect() {
  // Select the dropdown element for Tx ECU
  var txEcuSelects = document.querySelectorAll('select[name="tx-ecu"]');
  
  // Loop through each dropdown element
  txEcuSelects.forEach(function(select) {
      // Get the ID of the current ECU container
      var currentEcuId = select.closest('.ecu-container').id;
      
      // Remove existing options
      select.innerHTML = '';
      
      // Create a default option
      var defaultOption = document.createElement('option');
      defaultOption.value = '';
      defaultOption.text = 'Select an ECU';
      defaultOption.disabled = false;
      defaultOption.selected = true;
      select.appendChild(defaultOption);
      
      // Get all available ECU IDs except the current one
      var ecuContainers = document.querySelectorAll('.ecu-container');
      ecuContainers.forEach(function(ecuContainer) {
          var ecuId = ecuContainer.id;
          if (ecuId !== currentEcuId) {
              // Create an option for each available ECU ID
              var option = document.createElement('option');
              option.value = ecuId;
              option.text = ecuId.replace('ecu', 'ECU ');
              select.appendChild(option);
          }
      });
  });
}

function getAvailableCommChannelIds(ecuId) {
  // Select the ECU container corresponding to the provided ECU ID
  var ecuContainer = document.getElementById(ecuId);
  
  // Initialize an empty array to store the IDs of available communication channels
  var commChannelIds = [];
  
  // If the ECU container is found
  if (ecuContainer) {
      // Find all communication channels within the ECU container
      var commChannels = ecuContainer.querySelectorAll('.comm-channel');
      
      // Iterate over each communication channel
      commChannels.forEach(function(commChannel) {
          // Get the ID of the communication channel and push it to the array
          var commChannelId = commChannel.id;
          commChannelIds.push(commChannelId);
      });
  }
  
  // Return the array of communication channel IDs
  return commChannelIds;
}

function updateCommChannelOptions(selectedEcuId, currentCommChannelSelect) {
  var closestRxChannel = currentCommChannelSelect.closest('.rx-channel');
  var txCommChannelSelect = closestRxChannel.querySelector('select[name="comm-channel"]');
  var availableCommChannels = getAvailableCommChannelIds(selectedEcuId);
  
  // Clear existing options
  txCommChannelSelect.innerHTML = '';

  // Create a default option
  var defaultOption = document.createElement('option');
  defaultOption.value = '';
  defaultOption.text = 'Select an channel';
  defaultOption.disabled = false;
  defaultOption.selected = true;
  txCommChannelSelect.appendChild(defaultOption);

  // Add new options
  availableCommChannels.forEach(function(commChannelId) {
      var option = document.createElement('option');
      option.value = commChannelId;
      option.textContent = commChannelId;
      txCommChannelSelect.appendChild(option);
  });
}

// Generate JSON config File
document.getElementById('submitConfig').addEventListener('click', function() {
  
  clearConsole();

  logToConsole("System: Creating configuration files")

  var ecuContainers = document.querySelectorAll('.ecu-container');
  var configuration = [];

  ecuContainers.forEach(function(ecuContainer, index) {
      var ecu = {
          id: 'ecu' + (index + 1),
          mac: ecuContainer.querySelector('p#ecu-main-mac').textContent.trim(),
          commChannels: []
      };

      var commChannels = ecuContainer.querySelectorAll('.comm-channel');

      commChannels.forEach(function(commChannel, channelIndex) {
          var commChannelObj = {
              id: 'commChannel' + (channelIndex + 1),
              settings: {}
          };

          // Select only input fields directly under the commChannel

          var selects = commChannel.querySelectorAll(':scope > .collapsible-content select:not([name="comm-channel"]):not([name="tx-ecu"]):not([name="tx-mac"])');
          selects.forEach(function(select) {
              var name = select.getAttribute('name');
              var value = select.value;
              commChannelObj.settings[name] = value;
          });

          var inputs = commChannel.querySelectorAll(':scope > .collapsible-content input:not([name="tx-key-name"]):not([name="tx-key"]):not([name="tx-mac"]):not([name="tx-channel-port"])');
          inputs.forEach(function(input) {
              var name = input.getAttribute('name');
              var value = input.value;
              commChannelObj.settings[name] = value;
          });

          var rxChannels = commChannel.querySelectorAll('.rx-channel');
          var rxChannelsArray = [];

          rxChannels.forEach(function(rxChannel, rxIndex) {
              var rxChannelObj = {
                  id: 'rxChannel' + (rxIndex + 1),
                  settings: {}
              };

              var rxSelects = rxChannel.querySelectorAll('select:not([name="comm-channel"]):not([name="tx-ecu"])');
              rxSelects.forEach(function(select) {
                  var name = select.getAttribute('name');
                  var value = select.value;
                  rxChannelObj.settings[name] = value;
              });

              var rxInputs = rxChannel.querySelectorAll('input');
              rxInputs.forEach(function(input) {
                  var name = input.getAttribute('name');
                  var value = input.value;
                  rxChannelObj.settings[name] = value;
              });

              rxChannelsArray.push(rxChannelObj);
          });

          commChannelObj.rxChannels = rxChannelsArray;
          ecu.commChannels.push(commChannelObj);
      });

      configuration.push(ecu);
  });

  // Convert configuration to JSON
  var jsonConfig = JSON.stringify(configuration, null, 2);
  sendJSONStringToPHP(jsonConfig);
});

// Send JSON data to server-side script using AJAX

function sendJSONStringToPHP(jsonString) {

  var eventSource = new EventSource("php/sse.php");

  eventSource.addEventListener('error', function(e) {
    logToConsole(e.data, "error");
  }, false);

  eventSource.addEventListener('success', function(e) {
    logToConsole(e.data, "success");
  }, false);

  eventSource.addEventListener('done', function(e) {
    // logToConsole(e.data);
    eventSource.close(); // Close the connection when "done" is received
  }, false);

  eventSource.onmessage = (event) => {
    logToConsole(event.data);
  }

  logToConsole("System: Starting network simulation");

  var xhr = new XMLHttpRequest();
  xhr.open("POST", "php/create-config-dynamic.php", true);
  xhr.setRequestHeader("Content-Type", "application/json");
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {

        displayNetworkLogs()
        displayMaliciousLogs()

      } else {
        logToConsole("System: Internal error at AJAX request", "error"); // Log error message
      }
    }
  };
  xhr.send(jsonString);
}

function logToConsole(message, type) {
  const consoleContent = document.getElementById('console-content');
  const p = document.createElement('p');
  p.textContent = message;

  if (type == "error") {
    p.className = "error"
  } else if (type == "success") {
    p.className = "success"
  } else {
    p.className = "regular"
  }

  consoleContent.appendChild(p);
  consoleContent.scrollTop = consoleContent.scrollHeight;
}

function clearConsole() {
  const consoleContent = document.getElementById('console-content');

  // Remove all child nodes
  while (consoleContent.firstChild) {
    consoleContent.removeChild(consoleContent.firstChild);
  }
}




function fetchMessages(ecuId, type) {
  const url = `../php/return-messages.php?ecuId=${ecuId}&type=${type}`;
  fetch(url)
      .then(response => response.json())
      .then(messages => {
          const messagesContainer = document.getElementById(`${type}-${ecuId}`);
          messagesContainer.innerHTML = '';
          messages.forEach(message => {
              const p = document.createElement('p');
              p.textContent = message;
              messagesContainer.appendChild(p);
          });
      })
      .catch(error => console.error('Error fetching messages:', error));
}

function displayNetworkLogs() {
  let ecus = ['ecu1', 'ecu2', 'ecu3', 'ecu4'];

  for (let i = 0; i < ecus.length; i++) {
    
    let ecuId = ecus[i]
    fetchMessages(ecuId, "sent")
    fetchMessages(ecuId, "received")

  }

}

function displayMaliciousLogs() {
  let ecus = ['mim1', 'mim2'];

  for (let i = 0; i < ecus.length; i++) {
    
    let ecuId = ecus[i]
    fetchMessages(ecuId, "sent")

  }

}