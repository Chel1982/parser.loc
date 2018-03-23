<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Goods;

/**
 * GoodsSearch represents the model behind the search form of `app\models\Goods`.
 */
class GoodsSearch extends Goods
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'groups_id', 'sites_id'], 'integer'],
            [['name_goods', 'groups_name', 'sites_id', 'from_date', 'to_date', 'uri_goods', 'created_at'], 'safe'],
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
        $query = Goods::find()->orderBy(['id' => SORT_DESC])->joinWith('groups');

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
        $query->andFilterWhere(['=', 'goods.id',$this->id])
            ->andFilterWhere(['like', 'name_goods', $this->name_goods])
            ->andFilterWhere(['like', 'uri_goods', $this->uri_goods])
            ->andFilterWhere(['between','goods.created_at', $this->from_date, $this->to_date])
            ->andFilterWhere(['like','groups.name', $this->groups_name]);

        if($this->sites_id != 1 && $this->sites_id != ''){
            $query->andFilterWhere(['=','sites_id', $this->sites_id]);
        }

        return $dataProvider;
    }
}
