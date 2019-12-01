<?php

namespace common\modules\chat\components;

use \Yii;
use common\models\User;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {
  protected $clients;

  public function __construct() {
    $this->clients = new \SplObjectStorage;
  }

  public function onOpen(ConnectionInterface $conn) {
    // Store the new connection to send messages to later
    $this->clients->attach($conn);

    echo "New connection! ({$conn->resourceId})\n";
  }

  public function onMessage(ConnectionInterface $from, $msg) {
    $numRecv = count($this->clients) - 1;
    echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
      , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

    $arrayMessage = json_decode($msg, true);

    foreach ($this->clients as $client) {
      if ($arrayMessage === null) {
        $arrayMessage["message"] = $msg;
      }

      if (!array_key_exists("username", $arrayMessage)) {
        $arrayMessage["username"] = "Guest";
      }

      if (!array_key_exists("message", $arrayMessage)) {
        $arrayMessage["message"] = $msg[0];
      }

      $client->send(json_encode($arrayMessage));
    }
  }

  public function onClose(ConnectionInterface $conn) {
    // The connection is closed, remove it, as we can no longer send it messages
    $this->clients->detach($conn);

    echo "Connection {$conn->resourceId} has disconnected\n";
  }

  public function onError(ConnectionInterface $conn, \Exception $e) {
    echo "An error has occurred: {$e->getMessage()}\n";

    $conn->close();
  }
}