<?php

use common\models\User;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Project */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="project-view">

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
      'id',
      'title',
      'description:ntext',
      [
        'attribute' => 'active',
        'value' => function (\common\models\Project $model) {
          return $model::STATUSES_LABELS[$model->active];
        }
      ],
      [
        'attribute' => 'creator',
        'value' => function (\common\models\Project $model) {
          return $model->getCreatorUsername();
        }
      ],
      [
        'attribute' => 'updater',
        'value' => function (\common\models\Project $model) {
          return $model->getUpdaterUsername();
        }
      ],
    ],
  ]) ?>

  <?= GridView::widget(
    [
      'dataProvider' => $dataProvider,
      'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
          'attribute' => 'username',
          'value' => function ($model) {
            return User::findOne($model->user_id)->username;
          }
        ],
        'role',
        [
          'class' => 'yii\grid\ActionColumn',
          'template' => '{view}',
          'buttons' => [
            'view' => function ($url, $model) {
              $iconView = \yii\bootstrap\Html::icon('eye-open');
              return Html::a($iconView, ['/user/view/', 'id' => $model->user_id]);
            }
          ]
        ]
      ]
    ]
  )
  ?>
</div>
