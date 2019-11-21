<?php
namespace console\controllers;

use yii\console\Controller;

class HelloWorldController extends Controller
{
  public function actionIndex()
  {
    echo "Hello, Console!";
  }
}