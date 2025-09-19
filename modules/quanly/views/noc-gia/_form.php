<?php
use yii\helpers\Html;
use kartik\form\ActiveForm;

?>

<div class="noc-gia-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'so_nha')->textInput() ?>

    <?= $form->field($model, 'ten_duong')->textInput() ?>

    <?= $form->field($model, 'khupho_id')->textInput() ?>

    <?= $form->field($model, 'phuongxa_id')->textInput() ?>

    <?= $form->field($model, 'dia_chi')->textInput() ?>

    <?= $form->field($model, 'geom')->textInput() ?>

    <?= $form->field($model, 'lat')->textInput() ?>

    <?= $form->field($model, 'long')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput() ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
