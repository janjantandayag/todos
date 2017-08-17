<?php

namespace app\controllers;

use Yii;
use app\models\Po;
use app\models\PoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\PoItem;
use app\models\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
/**
 * PoController implements the CRUD actions for Po model.
 */
class PoController extends Controller
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
        ];
    }

    /**
     * Lists all Po models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if(Yii::$app->request->post('hasEditable')){
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $po_no  = Yii::$app->request->post('editableKey');  
            $po = $this->findModel($po_no);

            $post = [];
            $value = '';
            if(Yii::$app->request->post('po_no')){
                $value = Yii::$app->request->post('editableKey');
            }
            if(Yii::$app->request->post('description')){
                $value.= Yii::$app->request->post('description');
            }

            $posted = current($_POST['Po']);
            $post['Po'] = $posted;
            if($po->load($post)){
                $po->save(); 
                return ['output'=>$value, 'message' => ''];
            } else {
                return ['output'=>'', 'message' => ''];
            }            
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Po model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Po model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Po();
        $modelsPoItems = [new PoItem];

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $modelsPoItems = Model::createMultiple(PoItem::classname());
            Model::loadMultiple($modelsPoItems, Yii::$app->request->post());

            // ajax validation
            // if (Yii::$app->request->isAjax) {
            //     Yii::$app->response->format = Response::FORMAT_JSON;
            //     return ArrayHelper::merge(
            //         ActiveForm::validateMultiple($modelsAddress),
            //         ActiveForm::validate($modelCustomer)
            //     );
            // }

            // validate all models
            $valid = $model->validate();
            $valid = Model::validateMultiple($modelsPoItems) && $valid;
            
            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        foreach ($modelsPoItems as $modelsPoItem) {
                            $modelsPoItem->po_id = $model->id;
                            if (! ($flag = $modelsPoItem->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }else {
            return $this->render('create', [
                'model' => $model,
                'modelsPoItems' => (empty($modelsPoItems)) ? [new modelsPoItem] : $modelsPoItems
            ]);
        }
    }

    /**
     * Updates an existing Po model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelsPoItems = $model->poItems;

        if ($model->load(Yii::$app->request->post())) {
            $oldIDs = ArrayHelper::map($modelsPoItems, 'id', 'id');
            $modelsPoItems = Model::createMultiple(PoItem::classname(), $modelsPoItems);
            Model::loadMultiple($modelsPoItems, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsPoItems, 'id', 'id')));

            // ajax validation
            // if (Yii::$app->request->isAjax) {
            //     Yii::$app->response->format = Response::FORMAT_JSON;
            //     return ArrayHelper::merge(
            //         ActiveForm::validateMultiple($modelsAddress),
            //         ActiveForm::validate($modelCustomer)
            //     );
            // }

            // validate all models
            $valid = $model->validate();
            $valid = Model::validateMultiple($modelsPoItems) && $valid;

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        if (! empty($deletedIDs)) {
                            PoItem::deleteAll(['id' => $deletedIDs]);
                        }
                        foreach ($modelsPoItems as $modelsPoItem) {
                            $modelsPoItem->po_id = $model->id;
                            if (! ($flag = $modelsPoItem->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        } else {
            return $this->render('update', [
                'model' => $model,
                'modelsPoItems' => $modelsPoItems
            ]);
        }
    }

    /**
     * Deletes an existing Po model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Po model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Po the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Po::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
