function getUsername() {
  var element = document.querySelector('.dropdown.user.user-menu .dropdown-toggle span');

  var username = 'Guest';

  if (element && element.textContent.length > 0) {
    username = element.textContent;
  }

  return username
}

function getBodyChat() {
  return document.querySelector('.dropdown.messages-menu');
}

function getInputMessage() {
  var bodyChat = getBodyChat();

  var inputMessage = null;

  if (bodyChat) {
    inputMessage = bodyChat.querySelector('input#send-message');
  }

  return inputMessage;
}

function getValueInputMessage() {
  var inputMessage = getInputMessage();

  var value = '';

  if (inputMessage) {
    value = inputMessage.value;
  }

  return value;
}

function clearInputMessage() {
  var inputMessage = getInputMessage();

  if (inputMessage) {
    inputMessage.value = '';
  }
}

function sendMessageToChat() {
  if (this.hasOwnProperty('connectionChat')) {
    var message = getValueInputMessage();

    if (message.length > 0) {
      var objectMessage = {
        username: getUsername(),
        message: message
      };

      initConnectionChat(function (connection) {
        connection.send(JSON.stringify(objectMessage));
        clearInputMessage();
        updateTimeInBlocksMessage();
        updateCountMessages();
      });
    }
  }
}

function addToInputMessageEventListeners() {
  var inputMessage = getInputMessage();

  if (inputMessage) {
    inputMessage.addEventListener('keydown', function () {
      if (event.keyCode === 13) {
        sendMessageToChat();
      }
    })
  }
}

function getButtonSend() {
  var bodyChat = getBodyChat();

  var buttonSend = null;

  if (bodyChat) {
    buttonSend = bodyChat.querySelector('.button-send-message');
  }

  return buttonSend;
}

function addToButtonSendEventListeners() {
  var buttonSend = getButtonSend();

  if (buttonSend) {
    buttonSend.addEventListener('click', function () {
      sendMessageToChat();
    })
  }
}

function getButtonDropdown() {
  var bodyChat = getBodyChat();

  var buttonDropdown = null;

  if (bodyChat) {
    buttonDropdown = bodyChat.querySelector('.dropdown-toggle');
  }

  return buttonDropdown;
}

function addToButtonDropdownEventListeners() {
  var buttonDropdown = getButtonDropdown();

  if (buttonDropdown) {
    buttonDropdown.addEventListener('click', function () {
      updateTimeInBlocksMessage();
      updateCountMessages();
    })
  }
}

function getBodyViewMessages() {
  var bodyChat = getBodyChat();

  var bodyViewMessage = null;

  if (bodyChat) {
    bodyViewMessage = bodyChat.querySelector('#body-view-messages');
  }

  return bodyViewMessage;
}

function getElementCountMessages() {
  var bodyChat = getBodyChat();

  var elementCountMessages = null;

  if (bodyChat) {
    elementCountMessages = bodyChat.querySelector('#icon-count-messages');
  }

  return elementCountMessages;
}

function getHeaderCountMessages() {
  var bodyChat = getBodyChat();

  var headerCountMessages = null;

  if (bodyChat) {
    headerCountMessages = bodyChat.querySelector('.header#count-messages');
  }

  return headerCountMessages;
}

function updateCountMessages() {
  var arrayBlocksMessage = getArrayBlocksMessage();
  var elementCountMessages = getElementCountMessages();
  var headerCountMessages = getHeaderCountMessages();

  var countMessages = arrayBlocksMessage.length;

  if (elementCountMessages) {
    elementCountMessages.textContent = (countMessages > 0) ? countMessages : '';
  }

  if (headerCountMessages) {
    headerCountMessages.textContent =
      (countMessages > 0) ?
        'You have ' + countMessages + ' messages' :
        'You have not received messages';
  }
}

function getArrayBlocksMessage() {
  var bodyViewMessages = getBodyViewMessages();

  var arrayBlocksMessage = [];

  if (bodyViewMessages) {
    var blocksMessage = bodyViewMessages.querySelectorAll('.body-message');

    if (blocksMessage.length > 0) {
      for (var i = 0; i < blocksMessage.length; i++) {
        if (blocksMessage[i].nodeType === 1) {
          arrayBlocksMessage.push(blocksMessage[i]);
        }
      }
    }
  }

  return arrayBlocksMessage;
}

function updateTimeInBlocksMessage() {
  var arrayBlocksMessage = getArrayBlocksMessage();

  if (arrayBlocksMessage.length > 0) {
    for (var i = 0; i < arrayBlocksMessage.length; i++) {
      var timeElement = arrayBlocksMessage[i].querySelector('.time');

      if (timeElement && timeElement.dataset.time) {
        var timeSend = parseInt(timeElement.dataset.time, 10);
        var now = new Date();

        var diff = now.getTime() - timeSend;

        var seconds = Math.round(diff / 100);
        var minutes = Math.floor(seconds / 60);

        if (minutes > 0) {
          seconds = seconds - minutes * 60;
        }

        var hours = Math.floor(minutes / 60);

        if (hours > 0) {
          minutes = minutes - hours * 60;
        }

        var days = Math.floor(hours / 24);

        if (days > 0) {
          hours = hours - days * 24;
        }

        timeElement.textContent = ((days > 0) ? days + ' days ' : '') +
          ((hours > 0) ? hours + 'h ' : '') +
          ((minutes > 0) ? minutes + 'm ' : '') +
          ((seconds > 0) ? seconds + 's ' : '');
      }
    }
  }
}

function addNewMessageToBodyViewMessages(objectMessage) {
  console.log(objectMessage);
  var bodyViewMessages = getBodyViewMessages();

  if (bodyViewMessages) {
    var blockMessage = getNewBlockMessage(objectMessage);

    var firstChild = bodyViewMessages.childNodes[0];

    bodyViewMessages.insertBefore(blockMessage, firstChild);
  }
}

function getNewBlockMessage(objectMessage) {
  var bodyMessage = document.createElement('li');
  bodyMessage.classList.add('body-message');

  var link = document.createElement('a');
  link.setAttribute('href', '#');

  var pullLeft = document.createElement('div');
  pullLeft.classList.add('pull-left');

  var imgAvatar = document.createElement('img');
  imgAvatar.setAttribute('src', '/assets/99f32e21/img/user2-160x160.jpg');
  imgAvatar.classList.add('img-circle');

  pullLeft.appendChild(imgAvatar);

  var header = document.createElement('h4');
  if (objectMessage.hasOwnProperty('username') && objectMessage.username.length > 0) {
    header.textContent = objectMessage.username;
  }

  var time = document.createElement('small');

  var iconTime = document.createElement('i');
  iconTime.classList.add('fa', 'fa-clock-o');

  var now = new Date();

  time.appendChild(iconTime);

  var spanTime = document.createElement("span");
  spanTime.classList.add('time');
  spanTime.dataset.time = now.getTime().toString();
  spanTime.textContent = '0s';

  time.appendChild(spanTime);

  header.appendChild(time);

  var message = document.createElement('p');
  if (objectMessage.hasOwnProperty('message') && objectMessage.message.length > 0) {
    message.textContent = objectMessage.message;
  }

  link.appendChild(pullLeft);
  link.appendChild(header);
  link.appendChild(message);

  bodyMessage.appendChild(link);

  return bodyMessage;
}

function addErrorNotConnect() {
  var bodyChat = getBodyChat();

  if (bodyChat) {

    var dropdownMenu = bodyChat.querySelector('.dropdown-menu');

    if (dropdownMenu) {
      dropdownMenu.textContent = 'Не удалось установить соединение. Перезагрузите страницу';
    }
  }
}

function initConnectionChat(callback) {
  if (!this.hasOwnProperty('connectionChat') || this.connectionChat.readyState !== 1) {
    var port = (typeof wsPort === undefined) ? 8080 : wsPort;

    this.connectionChat = new WebSocket('ws://127.0.0.1:' + port);

    this.connectionChat.onerror = function () {
      addErrorNotConnect();
    };

    this.connectionChat.onmessage = function (e) {
      addNewMessageToBodyViewMessages(JSON.parse(e.data));
      updateTimeInBlocksMessage();
      updateCountMessages();
    };

    this.connectionChat.onopen = function () {
      callback(this);
    };

    return true;
  }

  callback(this.connectionChat);
}

function initChat() {
  initConnectionChat(function () {
    addToInputMessageEventListeners();
    addToButtonSendEventListeners();
    addToButtonDropdownEventListeners();
  });
}

window.addEventListener('load', function() {
  initChat();
});