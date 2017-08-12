<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\helpers\CustomHelpers;

/* @var $this yii\web\View */
/* @var $model app\models\Tasks */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Tasks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tasks-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->task_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->task_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'task_id',
            'title',
            [
                'attribute' => 'description',
                'format' => 'raw',
                'label' => 'Formatted description'
            ],
            [
                'attribute' => 'progress',
                'format' => 'raw',
                'value' => function ($model){
                    $class = CustomHelpers::getProgressClass($model->progress);
return <<< PROGRESS_BAR
                        <div class="progress">
                          <div class="progress-bar $class" role="progressbar" aria-valuenow="$model->progress%" aria-valuemin="0" aria-valuemax="100" style="width:$model->progress%">
                            $model->progress%
                          </div>
                        </div>
PROGRESS_BAR;
                }
            ],
            'progress',
            'priority',
            'status',
        ],
    ]) ?>

</div>
