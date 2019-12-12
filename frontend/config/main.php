<?php

use yii\web\User;
use yii\web\UserEvent;

$params = array_merge(
  require __DIR__ . '/../../common/config/params.php',
  require __DIR__ . '/../../common/config/params-local.php',
  require __DIR__ . '/params.php',
  require __DIR__ . '/params-local.php'
);

$config = [
  'id' => 'app-frontend',
  'basePath' => dirname(__DIR__),
  'bootstrap' => ['log'],
  'controllerNamespace' => 'frontend\controllers',
  'components' => [
    'request' => [
      'csrfParam' => '_csrf-frontend',
      'cookieValidationKey' => 'xxxxxxx',
      "parsers" => [
        "application/json" => "yii\web\JsonParser"
      ]
    ],
    'user' => [
      'identityClass' => 'common\models\User',
      'enableAutoLogin' => true,
      'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
      'on ' . User::EVENT_AFTER_LOGIN => function(UserEvent $event) {
        Yii::info(\common\models\User::findOne($event->identity->getId())->username . ' is login in front', 'auth');
      }
    ],
    'session' => [
      // this is the name of the session cookie used for login on the frontend
      'name' => 'advanced-frontend',
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
        ['class' => \yii\rest\UrlRule::class, "controller" => ["api/task", "api/project"]],
        'users' => 'user/index',
        'user/<id:\d+>' => 'user/view',
        'profile' => 'user/profile',
        'projects' => 'project/index',
        'project/<id:\d+>' => 'project/view',
      ],
    ],

  ],

  "modules" => [
    "api" => [
      "class" => "frontend\modules\api\Module"
    ]
  ],

  'params' => $params,
];

if (!YII_ENV_TEST) {
  // configuration adjustments for 'dev' environment
  $config['bootstrap'][] = 'debug';
  $config['modules']['debug'] = [
    'class' => 'yii\debug\Module',
    "allowedIPs" => ["*"]
  ];

  $config['bootstrap'][] = 'gii';
  $config['modules']['gii'] = [
    'class' => 'yii\gii\Module',
    "allowedIPs" => ["*"]
  ];
}

return $config;