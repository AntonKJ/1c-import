<?php

namespace backend\modules\products\controllers;

use backend\modules\products\models\search\GoodsSearch;
use common\models\Goods;
use common\traits\FormAjaxValidationTrait;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class GoodsController extends Controller
{
    use FormAjaxValidationTrait;

    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $goods = new Goods;


        $this->performAjaxValidation($goods);

        if ($goods->load(Yii::$app->request->post()) && $goods->save()) {
            return $this->redirect(['index']);
        }
        $searchModel = new GoodsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $goods,
        ]);
    }

    /**
     * @param integer $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $good = $this->findModel($id);

        $this->performAjaxValidation($good);

        if ($good->load(Yii::$app->request->post()) && $good->save()) {
            return $this->redirect(['index']);
        }
        $goods = Goods::find()->andWhere(['not', ['id' => $id]])->all();
        $goods = ArrayHelper::map($goods, 'code', 'naimenovanie');

        return $this->render('update', [
            'model' => $good,
            'categories' => $goods,
        ]);
    }

    /**
     * @param integer $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * @param integer $id
     *
     * @return Goods the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Goods::findOne((int)$id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
