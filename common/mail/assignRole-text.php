<?php

use yii\helpers\Html;

/* @var $user common\models\User  */
/* @var $project common\models\User  */
/* @var $role string  */
?>

Уважаемый <?= Html::encode($user->username) ?>

В проекте <?= $project->title ?> Ваша роль изменена на <?= $role ?>