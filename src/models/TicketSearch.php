<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * TicketSearch represents the model behind the search form of `app\models\Ticket`.
 */
class TicketSearch extends Ticket
{
    public $created_at_from;
    public $created_at_to;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'category_id', 'assignee_id', 'status_id', 'betting_relative_user_id', 'created_by'], 'integer'],
            [['subject', 'description', 'betting_number', 'betting_time_of_occurrence', 'created_at', 'updated_at'], 'safe'],
            [['created_at_from', 'created_at_to'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios(): array
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
    public function search(array $params, string $formName = null): ActiveDataProvider
    {
        $query = Ticket::find()->with(['category', 'assignee', 'creator']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 5,
            ],
            'sort' => [
                'attributes' => [
                    'id',
                    'status_id',
                    'created_at',
                    'updated_at'
                ],
                'defaultOrder' => ['updated_at' => SORT_DESC],
            ],
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'category_id' => $this->category_id,
            'assignee_id' => $this->assignee_id,
            'status_id' => $this->status_id,
//            'betting_relative_user_id' => $this->betting_relative_user_id,
//            'betting_time_of_occurrence' => $this->betting_time_of_occurrence,
//            'created_by' => $this->created_by,
//            'created_at' => $this->created_at,
//            'updated_at' => $this->updated_at,
        ]);

        if ($this->created_at_from) {
            $query->andWhere(['>=', 'created_at', $this->created_at_from . ' 00:00:00']);
        }
        if ($this->created_at_to) {
            $query->andWhere(['<=', 'created_at', $this->created_at_to . ' 23:59:59']);
        }

        $query->andFilterWhere(['like', 'subject', $this->subject])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'betting_number', $this->betting_number]);

        return $dataProvider;
    }
}
