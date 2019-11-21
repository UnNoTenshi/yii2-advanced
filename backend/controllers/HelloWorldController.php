<?php


namespace backend\controllers;


use yii\web\Controller;

class HelloWorldController extends Controller
{
  public function actionIndex()
  {
    echo "Hello, Backend!";
  }
}