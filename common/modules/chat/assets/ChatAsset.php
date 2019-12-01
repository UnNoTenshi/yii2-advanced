<?php


namespace common\modules\chat\assets;


use yii\web\AssetBundle;

class ChatAsset extends AssetBundle
{
  public $sourcePath = '@common/assets';
  public $css = [
    "css/chat.css"
  ];
  public $js = [
    "js/chat.js"
  ];
  public $depends = [
  ];
}