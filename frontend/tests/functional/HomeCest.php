<?php

namespace frontend\tests\functional;

use common\fixtures\UserFixture;
use frontend\tests\FunctionalTester;

class HomeCest
{
  public function _fixtures()
  {
    return [
      'user' => [
        'class' => UserFixture::className(),
        'dataFile' => codecept_data_dir() . 'login_data.php',
      ],
    ];
  }

  protected function formParams($login, $password)
  {
    return [
      'LoginForm[username]' => $login,
      'LoginForm[password]' => $password,
    ];
  }

  public function _before(FunctionalTester $I)
  {
    $I->amOnRoute('site/login');
  }

  public function checkOpen(FunctionalTester $I)
  {
    $I->submitForm('#login-form', $this->formParams('erau', 'password_0'));
    $I->amOnPage(\Yii::$app->homeUrl);
    $I->see('My Application');
    $I->seeLink('About');
    $I->click('About');
    $I->see('This is the About page.');
  }
}