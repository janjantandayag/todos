<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Tasks;

/**
 * TasksSearch represents the model behind the search form about `app\models\Tasks`.
 */
class TasksSearch extends Tasks
{
    public $global_search='';
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_id'], 'integer'],
            [['title', 'description', 'priority', 'status','progress','global_search'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'global_search' => 'Global Search'
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
        $query = Tasks::find()->joinWith('users', true);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // // grid filtering conditions
        // $query->andFilterWhere([
        //     // 'user_task.task_id' => $this->task_id,
        //     // 'progress' => $this->progress,
        // ]);
        $id = Yii::$app->user->identity->user_id;
        $query->where([
            'and', 
            ['user_task.user_id' => $id],
            [
                "or",
                ['LIKE', 'title', $this->global_search],
                ['LIKE', 'priority', $this->global_search],
                ['LIKE', 'status', $this->global_search],
                ['LIKE', 'progress', $this->global_search]
            ]
        ]);
        // var_dump($query->prepare(Yii::$app->db->queryBuilder)->createCommand()->rawSql);
        // die();
        // $query->orFilterWhere(['like', 'title', $this->global_search])
        //     ->orFilterWhere(['like', 'description', $this->global_search])
        //     ->orFilterWhere(['like', 'priority', $this->global_search])
        //     ->orFilterWhere(['like', 'status', $this->global_search]);

        // $query->where("AND") 
        //         ->orFilterWhere(['like', 'description', $this->global_search])
        //         ->orFilterWhere(['like', 'priority', $this->global_search])
        //         ->orFilterWhere(['like', 'title', $this->global_search])
        //         ->orFilterWhere(['like', 'status', $this->global_search]);
        return $dataProvider;
        }
}
