<?php

use common\models\User;
use common\modules\chat\widgets\Chat;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">

  <?= Html::a('<span class="logo-mini">APP</span><span class="logo-lg">' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

  <nav class="navbar navbar-static-top" role="navigation">

    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
      <span class="sr-only">Toggle navigation</span>
    </a>
    <?php
    if (!Yii::$app->getUser()->isGuest) {
      ?>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">

          <!-- Messages: style can be found in dropdown.less-->
          <?= Chat::widget() ?>

          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?= Yii::$app->getUser()->getIdentity()->getThumbUploadUrl('avatar', User::AVATAR_ICO) ?>" class="user-image" alt="User Image"/>
              <span class="hidden-xs"><?= Yii::$app->getUser()->getIdentity()->username ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<?= Yii::$app->getUser()->getIdentity()->getThumbUploadUrl('avatar', User::AVATAR_PREVIEW) ?>" class="img-circle"
                     alt="User Image"/>

                <p>
                  <?= Yii::$app->getUser()->getIdentity()->username ?>
                  <?= Yii::$app->getUser()->getIdentity()->email ?>
                  <small>Member since <?= date("d F Y", Yii::$app->getUser()->getIdentity()->created_at) ?></small>
                </p>
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <?= Html::a(
                    'Sign out',
                    ['/site/logout'],
                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                  ) ?>
                </div>
              </li>
            </ul>
          </li>

          <!-- User Account: style can be found in dropdown.less -->
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div>

      <?php
    }
    ?>
  </nav>
</header>
