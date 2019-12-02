<?php


namespace frontend\modules\api\models;


use yii\helpers\StringHelper;

class Project extends \common\models\Project
{
  public function fields() {
    return [
      "id",
      "title",
      "description_short" => function() {
        return StringHelper::truncate($this->description, 50);
      },
      "active"
    ];
  }

  public function extraFields() {
    return [
      "tasks"
    ];
  }
}