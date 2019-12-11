<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $email
 * @property string $avatar
 * @property string $auth_key
 * @property integer $status
 * @property string $status_user
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 *
 * @property Task[] $activeTasks
 * @property Task[] $createdTasks
 * @property Task[] $updaterTasks
 * @property Project[] $createdProjects
 * @property Project[] $updatedProjects
 */
class User extends ActiveRecord implements IdentityInterface
{
  const STATUS_DELETED = 0;
  const STATUS_INACTIVE = 9;
  const STATUS_ACTIVE = 10;

  const STATUSES = [
    self::STATUS_ACTIVE,
    self::STATUS_DELETED,
    self::STATUS_INACTIVE
  ];

  const STATUS_LABELS = [
    self::STATUS_ACTIVE => "Активный",
    self::STATUS_INACTIVE => "Неактивный",
    self::STATUS_DELETED => "Удален"
  ];

  const AVATAR_PREVIEW = "preview";
  const AVATAR_ICO = "ico";

  const RELATION_ACTIVE_TASKS = "activeTasks";
  const RELATION_CREATED_TASKS = "createdTasks";
  const RELATION_UPDATED_TASKS = "updatedTasks";
  const RELATION_CREATED_PROJECTS = "createdProjects";
  const RELATION_UPDATED_PROJECTS = "updatedProjects";
  const RELATION_PROJECTS_USER = "projectUsers";

  const SCENARIO_CREATE = "create";
  const SCENARIO_UPDATE = "update";

  private $password;
  public $status_user;

  /**
   * {@inheritdoc}
   */
  public static function tableName()
  {
    return '{{%user}}';
  }

  /**
   * {@inheritdoc}
   */
  public function behaviors()
  {
    return [
      [
        'class' => \mohorev\file\UploadImageBehavior::class,
        'attribute' => 'avatar',
        'scenarios' => [self::SCENARIO_UPDATE],
        //'placeholder' => '@app/modules/user/assets/images/userpic.jpg',
        'path' => '@frontend/web/upload/user/{id}',
        'url' => Yii::$app->params['hosts.front'] . Yii::getAlias('@web/upload/user/{id}'),
        'thumbs' => [
          self::AVATAR_ICO => ['width' => 30, 'height' => 30, 'quality' => 90],
          self::AVATAR_PREVIEW => ['width' => 200, 'height' => 200, 'quality' => 100]
        ],
      ],
      TimestampBehavior::className(),
    ];
  }

  public function beforeSave($insert)
  {
    if (!parent::beforeSave($insert)) {
      return false;
    }

    $this->generateAuthKey();

    return true;
  }

  /**
   * {@inheritdoc}
   */
  public function rules()
  {
    return [
      [['username', 'email', 'password'], 'required'],
      ['status', 'default', 'value' => self::STATUS_ACTIVE],
      ['avatar', 'image', 'extensions' => 'jpg, jpeg, gif, png'],
      ['avatar', 'default', 'value' => '/assets/99f32e21/img/user2-160x160.jpg'],
      ['email', 'email'],
      ['status', 'in', 'range' => self::STATUSES]
    ];
  }

  public function scenarios()
  {
    return [
      self::SCENARIO_CREATE => ['username', 'email', 'password', 'status'],
      self::SCENARIO_UPDATE  => []
    ];
  }

  public function getActiveTasks()
  {
    return $this->hasMany(Task::class, ["executor_id" => "id"]);
  }

  public function getCreatedTasks()
  {
    return $this->hasMany(Task::class, ["creator_id" => "id"]);
  }

  public function getUpdatedTasks()
  {
    return $this->hasMany(Task::class, ["updater_id" => "id"]);
  }

  public function getCreatedProjects()
  {
    return $this->hasMany(Project::class, ["creator_id" => "id"]);
  }

  public function getUpdatedProjects()
  {
    return $this->hasMany(Project::class, ["updater_id" => "id"]);
  }

  public function getProjectsUser()
  {
    return $this->hasMany(ProjectUser::class, ['user_id' => 'id']);
  }

  /**
   * {@inheritdoc}
   */
  public static function findIdentity($id)
  {
    return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
  }

  /**
   * {@inheritdoc}
   */
  public static function findIdentityByAccessToken($token, $type = null)
  {
    throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
  }

  /**
   * Finds user by username
   *
   * @param string $username
   * @return static|null
   */
  public static function findByUsername($username)
  {
    return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
  }

  /**
   * Finds user by password reset token
   *
   * @param string $token password reset token
   * @return static|null
   */
  public static function findByPasswordResetToken($token)
  {
    if (!static::isPasswordResetTokenValid($token)) {
      return null;
    }

    return static::findOne([
      'password_reset_token' => $token,
      'status' => self::STATUS_ACTIVE,
    ]);
  }

  /**
   * Finds user by verification email token
   *
   * @param string $token verify email token
   * @return static|null
   */
  public static function findByVerificationToken($token)
  {
    return static::findOne([
      'verification_token' => $token,
      'status' => self::STATUS_INACTIVE
    ]);
  }

  /**
   * Finds out if password reset token is valid
   *
   * @param string $token password reset token
   * @return bool
   */
  public static function isPasswordResetTokenValid($token)
  {
    if (empty($token)) {
      return false;
    }

    $timestamp = (int)substr($token, strrpos($token, '_') + 1);
    $expire = Yii::$app->params['user.passwordResetTokenExpire'];
    return $timestamp + $expire >= time();
  }

  /**
   * {@inheritdoc}
   */
  public function getId()
  {
    return $this->getPrimaryKey();
  }

  public function getStatusUser()
  {
    return self::STATUS_LABELS[$this->status];
  }

  /**
   * {@inheritdoc}
   */
  public function getAuthKey()
  {
    return $this->auth_key;
  }

  /**
   * {@inheritdoc}
   */
  public function validateAuthKey($authKey)
  {
    return $this->getAuthKey() === $authKey;
  }

  /**
   * Validates password
   *
   * @param string $password password to validate
   * @return bool if password provided is valid for current user
   */
  public function validatePassword($password)
  {
    return Yii::$app->security->validatePassword($password, $this->password_hash);
  }

  /**
   * Generates password hash from password and sets it to the model
   *
   * @param string $password
   */
  public function setPassword($password)
  {
    $this->password = $password;

    if ($password) {
      $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }
  }

  public function getPassword()
  {
    return $this->password;
  }

  /**
   * Generates "remember me" authentication key
   */
  public function generateAuthKey()
  {
    $this->auth_key = Yii::$app->security->generateRandomString();
  }

  /**
   * Generates new password reset token
   */
  public function generatePasswordResetToken()
  {
    $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
  }

  public function generateEmailVerificationToken()
  {
    $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
  }

  /**
   * Removes password reset token
   */
  public function removePasswordResetToken()
  {
    $this->password_reset_token = null;
  }
}
