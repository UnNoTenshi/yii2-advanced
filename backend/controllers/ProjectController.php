<?php

namespace backend\controllers;

use common\models\ProjectUser;
use common\models\User;
use Yii;
use common\models\Project;
use common\models\search\ProjectSearch;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProjectController implements the CRUD actions for Project model.
 */
class ProjectController extends Controller
{
  /**
   * {@inheritdoc}
   */
  public function behaviors()
  {
    return [
      "access" => [
        "class" => AccessControl::class,
        "rules" => [
          [
            "allow" => true,
            "roles" => ["@"]
          ]
        ]
      ],
      'verbs' => [
        'class' => VerbFilter::className(),
        'actions' => [
          'delete' => ['POST'],
        ],
      ],
    ];
  }

  /**
   * Lists all Project models.
   * @return mixed
   */
  public function actionIndex()
  {
    $searchModel = new ProjectSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

    return $this->render('index', [
      'searchModel' => $searchModel,
      'dataProvider' => $dataProvider,
    ]);
  }

  /**
   * Displays a single Project model.
   * @param integer $id
   * @return mixed
   * @throws NotFoundHttpException if the model cannot be found
   */
  public function actionView($id)
  {
    $model = $this->findModel($id);

    $dataProvider = new ActiveDataProvider([
      "query" => Project::findOne($id)->getProjectUsers()
    ]);

    return $this->render('view', [
      'dataProvider' => $dataProvider,
      'model' => $model,
    ]);
  }

  /**
   * Creates a new Project model.
   * If creation is successful, the browser will be redirected to the 'view' page.
   * @return mixed
   */
  public function actionCreate()
  {
    $model = new Project();

    if ($this->loadModel($model) && $model->save()) {
      return $this->redirect(['view', 'id' => $model->id]);
    }

    return $this->render('create', [
      'model' => $model,
      'users' => $model->getUsersToProject()
    ]);
  }

  /**
   * Updates an existing Project model.
   * If update is successful, the browser will be redirected to the 'view' page.
   * @param integer $id
   * @return mixed
   * @throws NotFoundHttpException if the model cannot be found
   */
  public function actionUpdate($id)
  {
    $model = $this->findModel($id);

    if ($this->loadModel($model) && $model->save()) {
      return $this->redirect(['view', 'id' => $model->id]);
    }

    return $this->render('update', [
      'model' => $model,
      'users' => $model->getUsersToProject()
    ]);
  }

  /**
   * Deletes an existing Project model.
   * If deletion is successful, the browser will be redirected to the 'index' page.
   * @param integer $id
   * @return mixed
   * @throws NotFoundHttpException if the model cannot be found
   */
  public function actionDelete($id)
  {
    $this->findModel($id)->delete();

    ProjectUser::deleteAll(['project_id' => $id]);

    return $this->redirect(['index']);
  }

  /**
   * Finds the Project model based on its primary key value.
   * If the model is not found, a 404 HTTP exception will be thrown.
   * @param integer $id
   * @return Project the loaded model
   * @throws NotFoundHttpException if the model cannot be found
   */
  protected function findModel($id)
  {
    if (($model = Project::findOne($id)) !== null) {
      return $model;
    }

    throw new NotFoundHttpException('The requested page does not exist.');
  }

  private function loadModel(Project $model) {
    $data = Yii::$app->request->post($model->formName());
    $projectUsers = $data[Project::RELATION_PROJECT_USERS] ?? null;

    if ($projectUsers !== null) {
      $model->projectUsers = $projectUsers === '' ? [] : $projectUsers;
    }

    return $model->load(Yii::$app->request->post());
  }
}
