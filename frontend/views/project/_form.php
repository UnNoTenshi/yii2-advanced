<?php

use common\models\ProjectUser;
use unclead\multipleinput\MultipleInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Project */
/* @var $users array */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="project-form">

  <?php $form = ActiveForm::begin(); ?>

  <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

  <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

  <?= $form->field($model, 'active')->dropDownList($model::STATUSES_LABELS) ?>

  <?= $form->field($model, $model::RELATION_PROJECT_USERS)->widget(MultipleInput::className(), [
    //https://github.com/unclead/yii2-multiple-input/wiki
    'allowEmptyList'    => true,
    'addButtonPosition' => MultipleInput::POS_HEADER,
    'columns' => [
      [
        'name' => 'project_id',
        'type' => 'hiddenInput',
        'defaultValue' => $model->id
      ],
      [
        'name' => 'user_id',
        'type' => 'dropDownList',
        'title' => 'User',
        'items' => $users
      ],
      [
        'name' => 'role',
        'type' => 'dropDownList',
        'title' => 'Role',
        'items' => ProjectUser::ROLES_LABELS
      ]
    ]
  ]);
  ?>

  <div class="form-group">
    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
  </div>

  <?php ActiveForm::end(); ?>

</div>
