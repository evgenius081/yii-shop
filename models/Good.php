<?php


namespace app\models;


use Yii;
use yii\db\ActiveRecord;

class Good extends ActiveRecord
{
    public static function tableName()
    {
        return 'good';
    }

    public function getAllGoods()
    {
        $goods = Yii::$app->cache->get('goods');
        if (!$goods) {
            $goods = self::find()->asArray()->all();
            Yii::$app->cache->set('goods', $goods, 30);
        }
        return $goods;
    }

    public function getGoodsCategories($id)
    {
        return self::find()->where(['category' => $id])->asArray()->all();
    }

    public function getSearchResults($search)
    {
        return self::find()->where(['like', 'name', $search])->asArray()->all();
    }

    public function getOneGood($name)
    {
        return self::find()->where(['link_name' => $name])->one();
    }



}