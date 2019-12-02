<?php


namespace frontend\modules\api\controllers;


use frontend\modules\api\models\Task;
use yii\data\ActiveDataProvider;
use yii\rest\Controller;

class TaskController extends Controller
{
    public function actionIndex() {
      return new ActiveDataProvider(
        ["query" => Task::find()]
      );
    }
}