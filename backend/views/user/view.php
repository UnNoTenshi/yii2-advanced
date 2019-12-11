<?php

use common\models\Project;
use common\models\User;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

  <h1><?= Html::encode($this->title) ?></h1>

  <p>
    <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    <?= Html::a('Delete', ['delete', 'id' => $model->id], [
      'class' => 'btn btn-danger',
      'data' => [
        'confirm' => 'Are you sure you want to delete this item?',
        'method' => 'post',
      ],
    ]) ?>
  </p>

  <?= DetailView::widget([
    'model' => $model,
    'attributes' => [
      [
        "attribute" => "avatar",
        "format" => ["html"],
        "value" => function ($model) {
          return Html::img($model->getThumbUploadUrl('avatar', User::AVATAR_PREVIEW));
        },
      ],
      'id',
      'username',
      'email:email',
      [
        "attribute" => "status",
        "value" => $model::STATUS_LABELS[$model->status]
      ],
      [
        "attribute" => "created_at",
        "format" => ["date", "php:d F Y H:i:s"]
      ],
      [
        "attribute" => "updated_at",
        "format" => ["date", "php:d F Y H:i:s"]
      ],
    ],
  ]) ?>

  <?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
      ['class' => 'yii\grid\SerialColumn'],
      [
        'attribute' => 'title',
        'value' => function ($model) {
          return Project::findOne($model->project_id)->title;
        }
      ],
      'role',
      [
        'class' => 'yii\grid\ActionColumn',
        'template' => '{view}',
        'buttons' => [
          'view' => function ($url, $model) {
            $iconView = \yii\bootstrap\Html::icon('eye-open');
            return Html::a($iconView, ['/project/view/', 'id' => $model->project_id]);
          }
        ]
      ]
    ]
  ])
  ?>
</div>
