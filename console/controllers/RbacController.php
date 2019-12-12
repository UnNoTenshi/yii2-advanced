<?php


namespace console\controllers;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
  const PERMISSION_LOGIN_BACKEND = 'loginBackend';
  const PERMISSION_LOGIN_FRONTEND = 'loginFrontend';

  const ROLE_ADMIN = 'admin';
  const ROLE_USER = 'user';

  public function actionInit()
  {
    $auth = Yii::$app->authManager;

    $loginBackend = $auth->createPermission(self::PERMISSION_LOGIN_BACKEND);
    $loginBackend->description = 'Login in backend';
    $auth->add($loginBackend);

    $loginFrontend = $auth->createPermission(self::PERMISSION_LOGIN_FRONTEND);
    $loginFrontend->description = 'Login in frontend';
    $auth->add($loginFrontend);

    $user = $auth->createRole(self::ROLE_USER);
    $auth->add($user);
    $auth->addChild($user, $loginFrontend);

    $admin = $auth->createRole(self::ROLE_ADMIN);
    $auth->add($admin);
    $auth->addChild($admin, $loginBackend);
    $auth->addChild($admin, $user);

    $auth->assign($admin, 2);
  }
}