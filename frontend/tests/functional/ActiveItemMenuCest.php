<?php


namespace frontend\tests\functional;


use Codeception\Example;
use common\fixtures\UserFixture;
use frontend\tests\FunctionalTester;

class ActiveItemMenuCest
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

  /**
   * @return array
   */
  protected function pageProviderIsGuest()
  {
    return [
      ["url" => "/site", "active" => "Home"],
      ["url" => "site/signup", "active" => "SignUp"]
    ];
  }

  /**
   * @return array
   */
  protected function pageProviderIsNotGuest()
  {
    return [
      ["url" => "/site", "active" => "Home"],
      ["url" => "site/about", "active" => "About"],
      ["url" => "site/contact", "active" => "Contact"]
    ];
  }

  /**
   * @param FunctionalTester $I
   * @param Example $example
   *
   * @dataProvider pageProviderIsGuest
   */
  public function activeMenuItemGuest(FunctionalTester $I, Example $example)
  {
    $I->amOnPage($example['url']);
    $I->see($example['active'], '.sidebar-menu .active span');
  }

  /**
   * @param FunctionalTester $I
   * @param Example $example
   *
   * @dataProvider pageProviderIsNotGuest
   */
  public function activeMenuItemNotGuest(FunctionalTester $I, Example $example)
  {
    $I->amOnRoute('site/login');
    $I->submitForm('#login-form', $this->formParams('erau', 'password_0'));
    $I->amOnPage($example['url']);
    $I->see($example['active'], '.sidebar-menu .active span');
  }
}