<?php


namespace frontend\tests\unit\models;


use Codeception\Test\Unit;
use common\fixtures\UserFixture;
use common\models\User;
use frontend\models\ContactForm;

class TestTest extends Unit
{
  public $tester;

  public function _before()
  {
    $this->tester->haveFixtures([
      'user' => [
        'class' => UserFixture::className(),
        'dataFile' => codecept_data_dir() . 'user.php'
      ]
    ]);
  }

  public function testAssertTrue()
  {
    $user = User::findByUsername('okirlin');
    $this->assertTrue($user->email === 'brady.renner@rutherford.com');
  }

  public function testAssertEquals() {
    $user = User::findByVerificationToken('4ch0qbfhvWwkcuWqjN8SWRq72SOw1KYT_1548675330');
    $this->assertEquals($user->username, 'test.test');
  }

  public function testAssertLessThan() {
    $user = User::findByUsername('test2.test');
    $this->assertLessThan($user->status, 9);
  }

  public function testAssertAttributeEquals() {
    $model = new ContactForm();

    $model->attributes = [
      'name' => 'TestName',
      'email' => 'test@test.test',
      'subject' => 'Test Subject',
      'body' => 'THis is test',
    ];
    $this->assertAttributeEquals('test@test.test', 'email', $model);
  }

  public function testAssertArrayHasKey() {
    $arrayTest = [
      "test" => "test",
      "testSecond" => "test"
    ];

    $this->assertArrayHasKey("test", $arrayTest);
  }
}