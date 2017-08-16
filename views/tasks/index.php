<?php


use yii\helpers\Html;
use yii\widgets\Pjax;
use app\helpers\CustomHelpers;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use kartik\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel app\models\TasksSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tasks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tasks-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Tasks', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::button('Add New Task', ['value' => Url::to('index.php?r=tasks/create'), 'class' => 'btn btn-success', 'id'=>'modalButton']) ?>
    </p>
    <?php
        Modal::begin([
            'header' => '<h4>Create Task</h4>',
            'id' => 'task-modal',
            'size' => 'modal-lg'
        ]);
        echo "<div id='modalContent'></div>";
        Modal::end();
    ?>
    <?php Pjax::begin(['id' => 'taskGrid']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'striped' => false,        
        'tableOptions' => ['class' => 'table table-bordered'],
        'rowOptions'=> function($model){
            return CustomHelpers::getBgColor($model->priority);
        },
        
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'Task Title',
                'format' => 'raw',
                'attribute' => 'title',
                'value' => function($model){
                    return Html::a($model->title, ['tasks/view','id'=>$model->task_id]);
                },
            ],
            [
                'attribute' => 'progress',
                'format' => 'raw',
                'value' => function ($model){
                    $value = $model->progress;
                    $class = CustomHelpers::getProgressClass($value);
                    return <<< PROGRESS_BAR
                        <div class="progress">
                          <div class="progress-bar $class" role="progressbar" aria-valuenow="$value%" aria-valuemin="0" aria-valuemax="100" style="width:$value%">
                            $value%
                          </div>
                        </div>
PROGRESS_BAR;
                },
            ],
            [
                'attribute' => 'priority',
                'value' => function ($model){
                    return CustomHelpers::evaluatePriority($model->priority);
                }
            ],
            'status',
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Action',
                'template' => '{view} {update} {delete}',
                'headerOptions' => [
                    'style' => [
                        'color' => '#337ab7'
                    ]
                ]
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
