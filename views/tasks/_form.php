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

    <?php $form = ActiveForm::begin(['id' => 'taskCreate-form']); ?>

    <?= $form->field($model, 'title')->textInput() ?>

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

    <?= $form->field($model, 'priority')->dropDownList([ '1' => 'LOW', '2' => 'NORMAL', '3' => 'HIGH', ], ['prompt' => 'Select priority']
    ) ?>

    <?= $form->field($model, 'status')->dropDownList([ 'ENABLED' => 'ENABLED', 'CANCELLED' => 'CANCELLED', 'FINISHED' => 'FINISHED', ], ['prompt' => 'Select status']) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-success',
            'name' => 'create-button',
            'value'=>'save'
        ]) ?>
         <?= Html::submitButton(Yii::t('app', 'Save & Close'), ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-success',
            'name' => 'create-button',
            'value'=>'save-close'
        ]) ?>
         <?= Html::submitButton(Yii::t('app', 'Save & New'), ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-success',
            'name' => 'create-button',
            'value'=>'save-new'
        ]) ?>

         <?= Html::a(Yii::t('app', 'Cancel'), ['index'], ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-success' ]) ?>

    </div>

    <?php ActiveForm::end(); ?>
</div> 


<?php
if(Yii::$app->request->isAjax){
    $script = <<< JS
        $('form#{$form->id}').on('beforeSubmit', function (e) {
            var form = $(this);
            $.ajax({
                type: 'post',
                url: form.attr('action'),
                data: form.serialize(),
                success: function(response){
                    if (response.status == 'success') {
                        $('#task-modal').modal('hide');
                        $(form).trigger("reset");
                        $.pjax.reload({container: '#taskGrid'});
                    } 
                }
            });
            return false;
        });
JS;
    $this->registerJs($script); 
}
?>