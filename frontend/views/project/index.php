<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\ProjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Projects';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-index">

  <h1><?= Html::encode($this->title) ?></h1>

  <p>
    <?= Html::a('Create Project', ['create'], ['class' => 'btn btn-success']) ?>
  </p>

  <?php Pjax::begin(); ?>
  <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

  <?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
      ['class' => 'yii\grid\SerialColumn'],

      'id',
      'title',
      'description:ntext',
      [
        "attribute" => "active",
        "value" => function (\common\models\Project $model) {
          return $model::STATUSES_LABELS[$model->active];
        }
      ],
      [
        "attribute" => "creator_username",
        "value" => function (\common\models\Project $model) {
          return $model->creator->username;
        }
      ],
      [
        "attribute" => "updater_username",
        "value" => function (\common\models\Project $model) {
          return $model->updater->username;
        }
      ],

      ['class' => 'yii\grid\ActionColumn'],
    ],
  ]); ?>

  <?php Pjax::end(); ?>

</div>
