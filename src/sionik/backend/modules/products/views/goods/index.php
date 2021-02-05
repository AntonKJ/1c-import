<?php

use common\grid\EnumColumn;
use common\models\Goods;
use common\models\GoodsCity;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\helpers\Html;

/**
 * @var $this         yii\web\View
 * @var $searchModel  \backend\models\search\GoodsSearch
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $model        common\models\Goods
 */

$this->title = Yii::t('backend', 'Goods');

$this->params['breadcrumbs'][] = $this->title;

?>
<div class="box box-success collapsed-box">
    <div class="box-header with-border">
        <h3 class="box-title"><?php echo Yii::t('backend', 'Create {modelClass}', ['modelClass' => 'Goods']) ?></h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool btn-info" data-toggle="collapse" data-target="#box-body"><i class="fa fa-plus"></i></button>
        </div>
    </div>
    <div class="box-body">
        <?php echo $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>

<?php echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'options' => [
        'class' => 'grid-view table-responsive',
    ],
    'columns' => [
        [
            'attribute' => 'code',
        ],
        [
            'attribute' => 'naimenovanie',
            'value' => function ($model) {
                return Html::a(Html::encode($model->naimenovanie), ['update', 'id' => $model->id]);
            },
            'format' => 'raw',
        ],
        [
            'attribute' => 'count_in_city',
        ],
        [
            'attribute' => 'price_in_city',
        ],
        [
            'attribute' => 'city_id',
            'options' => ['style' => 'width: 10%'],
            'value' => function ($model) {
                return $model->city ? $model->city->name : null;
            },
            'filter' => ArrayHelper::map(GoodsCity::find()->all(), 'id', 'name'),
        ],
        [
            'attribute' => 'weight',
        ],
        [
            'attribute' => 'id1c',
        ],
        [
            'class' => \common\widgets\ActionColumn::class,
            'template' => '{delete}',
        ],
    ],
]);

$this->registerJs("
    $( document ).ready(function() {
    $('#w0').slideUp();
    i = 1;
        $('.btn-info').click(function () {
            if (i==1){
                $('#w0').slideDown();
                i = 0;
           } else {
                $('#w0').slideUp();
                i = 1;
           }
        });
    });", \yii\web\View::POS_END);
?>
