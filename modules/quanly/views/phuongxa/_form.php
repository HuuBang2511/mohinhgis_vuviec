<?php
use yii\helpers\Html;
use kartik\form\ActiveForm;

?>

<div class="phuongxa-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'geom')->textInput() ?>

    <?= $form->field($model, 'tenTinh')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'maTinh')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tenXa')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'maXa')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'danSo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dienTich')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ghiChu')->textInput(['maxlength' => true]) ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
