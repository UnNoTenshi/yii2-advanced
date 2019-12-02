<?php

namespace common\modules\chat\widgets;

use common\models\User;
use common\modules\chat\assets\ChatAsset;
use Yii;

class Chat extends \yii\bootstrap\Widget
{
  public $port = 8080;

  public function init()
  {
    ChatAsset::register($this->view);
    $this->view->registerJsVar("wsPort", $this->port);
    $this->view->registerJsVar("username", !Yii::$app->getUser()->isGuest ? User::findOne(Yii::$app->getUser()->getId())->username : "Guest");
  }

  public function run()
  {
    return $this->render("chat");
  }
}
