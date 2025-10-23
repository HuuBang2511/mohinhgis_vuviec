<?php
use yii\helpers\Html;
use kartik\form\ActiveForm;

?>

<div class="kp-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'geom')->textInput() ?>

    <?= $form->field($model, 'OBJECTID')->textInput() ?>

    <?= $form->field($model, 'TenPhuong')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'TenQuan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'TenKhuPho')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'MaQuan')->textInput() ?>

    <?= $form->field($model, 'MaPhuong')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Shape_Leng')->textInput() ?>

    <?= $form->field($model, 'Shape_Area')->textInput() ?>

    <?= $form->field($model, 'mv_dvhc')->textInput(['maxlength' => true]) ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
