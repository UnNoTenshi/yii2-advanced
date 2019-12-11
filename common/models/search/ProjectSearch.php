<?php

namespace common\models\search;

use common\models\User;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Project;

/**
 * ProjectSearch represents the model behind the search form of `common\models\Project`.
 */
class ProjectSearch extends Project
{
  /**
   * {@inheritdoc}
   */
  public function rules()
  {
    return [
      [['id', 'creator_id', 'updater_id', 'created_at', 'updated_at'], 'integer'],
      [['active', 'creator_username', 'updater_username'], 'string'],
      [['title', 'description'], 'safe'],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function scenarios()
  {
    // bypass scenarios() implementation in the parent class
    return Model::scenarios();
  }

  /**
   * Creates data provider instance with search query applied
   *
   * @param array $params
   *
   * @return ActiveDataProvider
   */
  public function search($params)
  {
    $query = Project::find();

    // add conditions that should always apply here

    $dataProvider = new ActiveDataProvider([
      'query' => $query,
    ]);

    $this->load($params);

    if (!$this->validate()) {
      // uncomment the following line if you do not want to return any records when validation fails
      // $query->where('0=1');
      return $dataProvider;
    }

    // grid filtering conditions
    $query->andFilterWhere([
      'id' => $this->id,
      'creator_id' => $this->creator_id,
      'updater_id' => $this->updater_id,
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at,
    ]);

    if ($this->active) {
      $query->andWhere(['in', 'status', array_keys(preg_grep("/" . $this->active . "/ui", self::STATUSES_LABELS))]);
    }

    if ($this->creator_username) {
      $query->andWhere(['in', 'creator_id', User::find()->select(["id"])->where(["like", "username", $this->creator_username])->asArray()->column()]);
    }

    if ($this->updater_username) {
      $query->andWhere(['in', 'updater_id', User::find()->select(["id"])->where(["like", "username", $this->updater_username])->asArray()->column()]);
    }

    $query->andFilterWhere(['like', 'title', $this->title])
      ->andFilterWhere(['like', 'description', $this->description]);

    return $dataProvider;
  }
}
