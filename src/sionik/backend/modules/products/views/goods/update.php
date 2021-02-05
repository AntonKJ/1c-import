<?php

/**
 * @var $this  yii\web\View
 * @var $model common\models\Goods
 */

$this->title = Yii::t('backend', 'Update {modelClass}: ', [
        'modelClass' => 'Goods',
    ]) . ' ' . $model->naimenovanie;

$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Goods'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');

?>

<?php echo $this->render('_form', [
    'model' => $model,
]) ?>
