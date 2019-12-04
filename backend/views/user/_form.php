<?php

use common\models\User;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="user-form">

  <?php $form = ActiveForm::begin(
    [
      'options' => ['enctype' => 'multipart/form-data'],
      'layout' => 'horizontal',
      'fieldConfig' => [
        'horizontalCssClasses' => ['label' => 'col-sm-2',]
      ]
    ]
  ); ?>

  <?= Html::img($model->getThumbUploadUrl('avatar', User::AVATAR_PREVIEW)) ?>
  <?= $form->field($model, 'avatar')->fileInput(['accept' => 'image/*']) ?>
  <?= $form->field($model, 'status')->dropDownList($model::STATUS_LABELS) ?>
  <?= $form->field($model, 'username')->textInput() ?>
  <?= $form->field($model, 'email')->textInput() ?>
  <?= $form->field($model, 'password')->textInput() ?>

  <div class="form-group">
    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
  </div>

  <?php ActiveForm::end(); ?>

</div>
