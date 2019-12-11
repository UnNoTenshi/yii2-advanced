<?php

use yii\web\User;
use yii\web\UserEvent;

$params = array_merge(
  require __DIR__ . '/../../common/config/params.php',
  require __DIR__ . '/../../common/config/params-local.php',
  require __DIR__ . '/params.php',
  require __DIR__ . '/params-local.php'
);

return [
  'id' => 'app-backend',
  'basePath' => dirname(__DIR__),
  'controllerNamespace' => 'backend\controllers',
  'bootstrap' => ['log'],
  'modules' => [],
  'components' => [
    'request' => [
      'csrfParam' => '_csrf-backend',
    ],
    'user' => [
      'identityClass' => 'common\models\User',
      'enableAutoLogin' => true,
      'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
      'on ' . User::EVENT_AFTER_LOGIN => function(UserEvent $event) {
        Yii::info(\common\models\User::findOne($event->identity->getId())->username . ' is login in back', 'auth');
      }
    ],
    'session' => [
      // this is the name of the session cookie used for login on the backend
      'name' => 'advanced-backend',
    ],
    'log' => [
      'traceLevel' => YII_DEBUG ? 3 : 0,
      'targets' => [
        [
          'class' => 'yii\log\FileTarget',
          'levels' => ['error', 'warning'],
        ],
        [
          'class' => 'yii\log\FileTarget',
          'categories' => ['auth'],
          'logFile' => '@runtime/logs/authorizations.log',
          'logVars' => []
        ],
      ],
    ],
    'errorHandler' => [
      'errorAction' => 'site/error',
    ],

    'urlManager' => [
      'enablePrettyUrl' => true,
      'showScriptName' => false,
      'rules' => [
      ],
    ],

  ],
  'params' => $params,
];
