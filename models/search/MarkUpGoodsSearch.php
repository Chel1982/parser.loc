<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MarkUpGoods;

/**
 * MarkUpGoodsSearch represents the model behind the search form of `app\models\MarkUpGoods`.
 */
class MarkUpGoodsSearch extends MarkUpGoods
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'price_value', 'from_value', 'to_value', 'groups_id'], 'integer'],
            [['percent', 'absolute'], 'safe'],
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
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = MarkUpGoods::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'price_value' => $this->price_value,
            'from_value' => $this->from_value,
            'to_value' => $this->to_value,
            'groups_id' => $this->groups_id,
        ]);

        $query->andFilterWhere(['like', 'percent', $this->percent])
            ->andFilterWhere(['like', 'absolute', $this->absolute]);

        return $dataProvider;
    }
}
