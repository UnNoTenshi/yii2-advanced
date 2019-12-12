<?php

use common\events\AssignRoleEvent;
use common\services\ProjectService;

return [
  'aliases' => [
    '@bower' => '@vendor/bower-asset',
    '@npm' => '@vendor/npm-asset',
  ],
  'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
  'components' => [
    'cache' => [
      'class' => 'yii\caching\FileCache',
    ],
    'emailService' => [
      'class' => \common\services\EmailService::class
    ],
    'notificationService' => [
      'class' => \common\services\NotificationService::class
    ],
    'projectService' => [
      'class' => ProjectService::class,
      'on ' . ProjectService::EVENT_ASSIGN_ROLE => function (AssignRoleEvent $event) {
        Yii::$app->notificationService->sendEmailAboutNewRoleInProject($event);
      }
    ],
    'authManager' => [
      'class' => 'yii\rbac\DbManager',
    ],
  ],
  'modules' => [
    'Chat' => [
      'class' => 'common\modules\chat\Module',
    ],
  ],
];
