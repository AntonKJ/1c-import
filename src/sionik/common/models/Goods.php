<?php

namespace common\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "goods".
 * @property integer $key_map
 * @property string $code
 * @property string $naimenovanie
 * @property string $count_in_city
 * @property string $price_in_city
 * @property string $city_id
 * @property string $weight
 * @property string $id1c
 */
class Goods extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goods}}';
    }

    public static function primaryKey()
    {
        return ['id'];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(GoodsCity::class, ['id' => 'city_id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['naimenovanie'], 'required'],
            [['code'], 'string'],
            [['city_id'], 'string', 'max' => 120],
            [['weight'], 'string', 'max' => 50],
            [['id1c'], 'string', 'max' => 120],
            [['count_in_city'], 'string', 'max' => 25],
            [['price_in_city'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'code' => Yii::t('common', 'Код'),
            'city_id' => Yii::t('common', 'ИД города'),
            'naimenovanie' => Yii::t('common', 'Наименование'),
            'count_in_city' => Yii::t('common', 'Количество'),
            'price_in_city' => Yii::t('common', 'Цена'),
            'weight' => Yii::t('common', 'Вес'),
            'id1c' => Yii::t('common', 'ИД 1С'),
        ];
    }
}
