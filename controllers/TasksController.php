<?php

namespace app\controllers;

use Yii;
use app\models\Tasks;
use app\models\TasksSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\User;

/**
 * TasksController implements the CRUD actions for Tasks model.
 */
class TasksController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','view','create','update','delete'],
                'rules' => [
                    [
                        'actions' => ['index','view','create','update','delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['login','signup'],
                        'allow' => true,
                        'roles' => ['?']
                    ]
                ],
            ],
        ];
    }

    /**
     * Lists all Tasks models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TasksSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Tasks model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        if(User::hasThisTask($id)) {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        } else {
            throw new NotFoundHttpException("You don't own this post!");            
        }
    }

    /**
     * Creates a new Tasks model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $task = new Tasks();

        if ($task->load(Yii::$app->request->post())) {
            $buttonClick = Yii::$app->request->post('create-button');
            $user = User::findOne(Yii::$app->user->identity->user_id);
            $task->save();
            $task->link('users', $user);
            if (Yii::$app->request->isAjax){
                Yii::$app->response->format = 'json';
                return [
                    'status' => 'success',
                ];
            } else {                    
                $this->directTo($task,$buttonClick);
            }
        } else {
            if(Yii::$app->request->isAjax) {
                return $this->renderAjax('create', [
                    'model' => $task,
                ]); 
            } else {
                return $this->render('create', [
                    'model' => $task,
                ]);                 
            }
        }
    }

    /**
     * Updates an existing Tasks model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if(User::hasThisTask($id)) {
            $model = $this->findModel($id);
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->task_id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }else {
            throw new NotFoundHttpException("You don't own this post!");
        }
    }

    /**
     * Deletes an existing Tasks model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if(User::hasThisTask($id)) {
            $user = User::findOne(Yii::$app->user->identity->user_id);
            $task = $this->findModel($id);
            $task->delete();
            $task->unlink('users',$user);
            return $this->redirect(['index']);
        } else {
            throw new NotFoundHttpException("You don't own this post!");            
        }
    }

    /**
     * Finds the Tasks model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Tasks the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tasks::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function directTo($task, $buttonClick){
        if($buttonClick == 'save'){
            return $this->redirect(['update', 'id' => $task->task_id]);
        }elseif($buttonClick == 'save-close'){
            return $this->redirect(['index']);                        
        }elseif($buttonClick == 'save-new'){
            return $this->redirect(['create']);                        
        }else{
            return $this->redirect(['index']);                          
        }
    }
}
