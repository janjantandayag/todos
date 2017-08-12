<?php


use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\helpers\CustomHelpers;
/* @var $this yii\web\View */
/* @var $searchModel app\models\TasksSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tasks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tasks-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Tasks', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
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
            'priority',
            'status',
            ['class' => 'yii\grid\ActionColumn'],
        ]
    ]); ?>
    <?php Pjax::end(); ?>
</div>
