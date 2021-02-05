<?php

use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/**
 * @var $this  yii\web\View
 * @var $model common\models\Page
 */

?>

<?php $form = ActiveForm::begin([
    'enableClientValidation' => false,
    'enableAjaxValidation' => true,
]) ?>

<?php echo $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

<?php echo $form->field($model, 'naimenovanie')->textarea(['maxlength' => true]) ?>

<?php echo $form->field($model, 'city_id')->textInput(['maxlength' => true]) ?>

<?php echo $form->field($model, 'weight')->textInput(['maxlength' => true]) ?>

<?php echo $form->field($model, 'id1c')->textInput(['maxlength' => true]) ?>

<div class="form-group">
    <?php echo Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end() ?>
