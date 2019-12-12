<?php

namespace common\models;

use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "project".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property int $active
 * @property string $creator_username
 * @property int $creator_id
 * @property string $updater_username
 * @property int|null $updater_id
 * @property int $created_at
 * @property int|null $updated_at
 * @property int|null $completed_at
 *
 * @property User $creator
 * @property User $updater
 * @property User[] $users
 * @property ProjectUser[] $projectUsers
 *
 * @property Task[] $tasks
 */
class Project extends \yii\db\ActiveRecord
{
  const RELATION_USERS = 'users';
  const RELATION_TASKS = 'tasks';
  const RELATION_PROJECT_USERS = 'projectUsers';

  const STATUS_INACTIVE = 0;
  const STATUS_ACTIVE = 1;

  const STATUSES = [
    self::STATUS_INACTIVE,
    self::STATUS_ACTIVE
  ];

  const STATUSES_LABELS = [
    self::STATUS_INACTIVE => "Неактивный",
    self::STATUS_ACTIVE => "Активный"
  ];

  public $creator_username;
  public $updater_username;
  public $completed_at;

  public function behaviors()
  {
    return [
      TimestampBehavior::class,
      [
        "class" => BlameableBehavior::class,
        "createdByAttribute" => "creator_id",
        "updatedByAttribute" => "updater_id"
      ],
      'saveRelations' => [
        'class' => SaveRelationsBehavior::class,
        'relations' => [
          self::RELATION_PROJECT_USERS,
        ]
      ]
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function tableName()
  {
    return 'project';
  }

  /**
   * {@inheritdoc}
   */
  public function rules()
  {
    return [
      [['title', 'description'], 'required'],
      [['description'], 'string'],
      [['active'], 'in', 'range' => self::STATUSES],
      [['active', 'completed_at'], 'integer'],
      [['title'], 'string', 'max' => 255],
      [['creator_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['creator_id' => 'id']],
      [['updater_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updater_id' => 'id']],
    ];
  }

  public function getTasks() {
    return $this->hasMany(Task::class, ["project_id" => "id"]);
  }

  /**
   * {@inheritdoc}
   */
  public function attributeLabels()
  {
    return [
      'id' => 'ID',
      'title' => 'Title',
      'description' => 'Description',
      'active' => 'Active',
      'creator_id' => 'Creator ID',
      'updater_id' => 'Updater ID',
      'created_at' => 'Created At',
      'updated_at' => 'Updated At',
    ];
  }

  /**
   * @return \yii\db\ActiveQuery
   */
  public function getCreator()
  {
    return $this->hasOne(User::className(), ['id' => 'creator_id']);
  }

  /**
   * @return \yii\db\ActiveQuery
   */
  public function getUpdater()
  {
    return $this->hasOne(User::className(), ['id' => 'updater_id']);
  }

  /**
   * @return \yii\db\ActiveQuery
   */
  public function getProjectUsers()
  {
    return $this->hasMany(ProjectUser::className(), ['project_id' => 'id']);
  }

  /**
   * {@inheritdoc}
   * @return \common\models\query\ProjectQuery the active query used by this AR class.
   */
  public static function find()
  {
    return new \common\models\query\ProjectQuery(get_called_class());
  }

  public function getUsersToProject() {
    $users = User::find()
      ->select(['id', 'username'])
      ->where(['<>', 'id', Yii::$app->getUser()->id])
      ->andWhere(['status' => User::STATUS_ACTIVE])
      ->asArray()
      ->all();

    return ArrayHelper::map($users, 'id', 'username');
  }

  public function getUsersData() {
    return ArrayHelper::map($this->getProjectUsers()->asArray()->all(), 'user_id', 'role');
  }

  public function isUserInProject() {
    return ArrayHelper::isIn(Yii::$app->getUser()->getId(), ArrayHelper::map($this->projectUsers, 'id', 'user_id'));
  }

  public function isUserManagerInProject() {
    if ($this->isUserInProject()) {
      return ProjectUser::findOne([
        "user_id" => Yii::$app->getUser()->getId(),
        "project_id" => $this->id
      ])->role === ProjectUser::ROLE_MANAGER;
    }

    return false;
  }
}
