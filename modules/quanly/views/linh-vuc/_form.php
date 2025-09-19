<?php
use yii\helpers\Html;
use kartik\form\ActiveForm;

?>

<div class="linh-vuc-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'ten_linh_vuc')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'trong_so_nghiem_trong')->textInput() ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
