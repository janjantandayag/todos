<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\slider\Slider;
use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model app\models\Tasks */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tasks-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'standard',
    ]) ?>

    <?= $form->field($model, 'progress')->widget(Slider::classname(), [
        'name' => 'Progress',
        'sliderColor'=>Slider::TYPE_GREY,
        'handleColor'=>Slider::TYPE_DANGER,
        'pluginOptions'=>[
            'min'=>0,
            'max'=>100,
            'step'=>1,
        ]
    ]) ?>

    <?= $form->field($model, 'priority')->dropDownList([ 'LOW' => 'LOW', 'NORMAL' => 'NORMAL', 'HIGH' => 'HIGH', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'status')->dropDownList([ 'ENABLED' => 'ENABLED', 'CANCELLED' => 'CANCELLED', 'FINISHED' => 'FINISHED', ], ['prompt' => '']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
