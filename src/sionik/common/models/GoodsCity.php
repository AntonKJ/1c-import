<?php

namespace common\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "city".
 *
 * @property integer         $id
 * @property string          $name
 * @property string          $id1c
 * @property integer         $datetime
 *
 * @property Goods[]       $goods
 */
class GoodsCity extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%city}}';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 512],
            [['id'], 'unique'],
            [['id'], 'string', 'max' => 120],
            ['status', 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'name' => Yii::t('common', 'Title'),
            'id1c' => Yii::t('common', '1C ID'),
            'status' => Yii::t('common', 'Status'),
            'datetime' => Yii::t('common', 'Date time'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCitys()
    {
        return $this->hasMany(Goods::class, ['city_id' => 'id']);
    }

}
