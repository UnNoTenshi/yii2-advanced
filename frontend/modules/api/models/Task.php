<?php


namespace frontend\modules\api\models;


use yii\helpers\StringHelper;

class Task extends \common\models\Task
{
  public function fields() {
    return [
      "id",
      "title",
      "description_short" => function() {
        return StringHelper::truncate($this->description, 50);
      }
    ];
  }

  public function extraFields() {
    return [
      "project"
    ];
  }
}