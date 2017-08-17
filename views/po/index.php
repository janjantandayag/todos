<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\models\PoItemSearch;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'P.O';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="po-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Po', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'perfectScrollbar' => true,
        'condensed' => true,
        'hover' => true,
        'pjax' => true,
        'columns' => [
            [
                'class' => 'kartik\grid\ExpandRowColumn',
                'value' => function ($model, $key, $index, $column){
                    return GridView::ROW_COLLAPSED;
                },
                'detail' => function ($model, $key, $index, $column){
                    $searchModel = new PoItemSearch;
                    $searchModel->po_id = $model->id;
                    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                    return Yii::$app->controller->renderPartial('_poitems', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider
                    ]);
                }
            ],
            [
                'class' => 'kartik\grid\EditableColumn',
                'attribute' => 'po_no',

            ],
            [
                'class' => 'kartik\grid\EditableColumn',
                'attribute' => 'description',
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
