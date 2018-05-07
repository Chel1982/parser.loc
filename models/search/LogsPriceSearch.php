<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\LogsPrice;

/**
 * LogsPriceSearch represents the model behind the search form of `app\models\LogsPrice`.
 */
class LogsPriceSearch extends LogsPrice
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'goods_id'], 'integer'],
            [['log'], 'safe'],
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
        $query = LogsPrice::find()->orderBy(['id' => SORT_DESC]);

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
            'goods_id' => $this->goods_id,
        ]);

        $query->andFilterWhere(['like', 'log', $this->log]);

        return $dataProvider;
    }
}
