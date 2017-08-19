<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PoItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="po-item-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'pjax' => true,
        'columns' => [
            'po_item_no',
            'quantity'
        ],
    ]); ?>
</div>
