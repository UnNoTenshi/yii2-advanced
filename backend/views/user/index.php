<?php

use common\models\User;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

  <h1><?= Html::encode($this->title) ?></h1>

  <p>
    <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
  </p>

  <?php Pjax::begin(); ?>
  <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

  <?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
      ['class' => 'yii\grid\SerialColumn'],

      'id',
      [
        "attribute" => "avatar",
        "format" => "html",
        "value" => function ($model) {
          return Html::img($model->getThumbUploadUrl('avatar', User::AVATAR_ICO));
        },
      ],
      'username',
      'email:email',
      [
        "attribute" => "status_user",
        "value" => function ($model) {
          return $model::STATUS_LABELS[$model->status];
        },
      ],
      [
        "attribute" => "created_at",
        "format" => ["date", "php:d F Y H:i:s"]
      ],
      [
        "attribute" => "updated_at",
        "format" => ["date", "php:d F Y H:i:s"]
      ],

      ['class' => 'yii\grid\ActionColumn'],
    ],
  ]); ?>

  <?php Pjax::end(); ?>

</div>
