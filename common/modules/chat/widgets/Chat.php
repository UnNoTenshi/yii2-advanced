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
    $this->view->registerJsVar("username", !Yii::$app->getUser()->isGuest ? Yii::$app->getUser()->getIdentity()->username : "Guest");
    $this->view->registerJsVar("avatar", !Yii::$app->getUser()->isGuest ? Yii::$app->getUser()->getIdentity()->getThumbUploadUrl('avatar', User::AVATAR_ICO) : '/assets/99f32e21/img/user2-160x160.jpg');
  }

  public function run()
  {
    return $this->render("chat");
  }
}
