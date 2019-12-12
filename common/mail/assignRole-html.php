<?php

use yii\helpers\Html;

/* @var $user common\models\User  */
/* @var $project common\models\User  */
/* @var $role string  */
?>

<div>
  <h1>Уважаемый <?= Html::encode($user->username) ?></h1>
  <p>В проекте <?= $project->title ?> Ваша роль изменена на <?= $role ?></p>
</div>