<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sites;

/**
 * SitesSearch represents the model behind the search form of `app\models\Sites`.
 */
class SitesSearch extends Sites
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'delay_parsing', 'usleep_start', 'usleep_stop'], 'integer'],
            [['name', 'url', 'down_url', 'status'], 'safe'],
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
        $query = Sites::find();

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
            'delay_parsing' => $this->delay_parsing,
            'usleep_start' => $this->usleep_start,
            'usleep_stop' => $this->usleep_stop,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'down_url', $this->down_url])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
