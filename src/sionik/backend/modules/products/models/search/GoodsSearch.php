<?php

namespace backend\modules\products\models\search;

use common\models\Goods;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PageSearch represents the model behind the search form about `common\models\Page`.
 */
class GoodsSearch extends Goods
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'id1c'], 'string'],
            [['code', 'naimenovanie'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Goods::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'code' => $this->code,
            'naimenovanie' => $this->naimenovanie,
        ]);

        $query->andFilterWhere(['like', 'city_id', $this->city_id ])
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'id1c', $this->id1c]);

        return $dataProvider;
    }
}
