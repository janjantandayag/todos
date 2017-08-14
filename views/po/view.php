<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Po */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="po-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
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
            'id',
            'po_no',
            'description:ntext',
            [
                'value' => call_user_func(function($model){
                    $value = $model->poItems;
                    $string = '';
                    foreach($value as $lala){
                        $string .= Html::a($lala->po_item_no, ['po-item/view', 'id' => $lala->id]);
                        $string .= '<br/>';
                    }
                    return $string;
                }, $model),
                'attribute' => 'po_item_no',
                'format' => 'raw'
            ]
        ],
    ]) ?>

</div>
