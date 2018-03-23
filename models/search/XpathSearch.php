<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Xpath;

/**
 * XpathSearch represents the model behind the search form of `app\models\Xpath`.
 */
class XpathSearch extends Xpath
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'sites_id', 'name_regular_id'], 'integer'],
            [['regular'], 'safe'],
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
        $query = Xpath::find();

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
            'sites_id' => $this->sites_id,
            'name_regular_id' => $this->name_regular_id,
        ]);

        $query->andFilterWhere(['like', 'regular', $this->regular]);

        return $dataProvider;
    }
}
