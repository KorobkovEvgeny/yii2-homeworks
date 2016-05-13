<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models;
use dosamigos\datetimepicker\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Calendar */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="calendar-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

    
    <?= $form->field($model, 'date_event')->widget(DateTimePicker::className(), [
    'language' => 'ru',
    'size' => 'ms',
    //'template' => '{input}',
    'pickButtonIcon' => 'glyphicon glyphicon-time',
    //'inline' => true,
    'clientOptions' => [
        'startView' => 1,
        'minView' => 0,
        'maxView' => 1,
        'autoclose' => true,
        'pickerPosition'=>'bottom-left',
        'format' => 'yyyy-mm-dd hh:ii:ss', // if inline = true
        // 'format' => 'HH:ii P', // if inline = false
        
    ]
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
