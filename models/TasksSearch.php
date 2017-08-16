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
    public $progress ='not-completed';
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

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $id = Yii::$app->user->identity->user_id;
        $value = $this->toQuery($this->progress);
        $query->where([
            'and', 
            ['user_task.user_id' => $id],
            [
                "or",
                ['LIKE', 'title', $this->global_search],
                ['LIKE', 'priority', $this->global_search],
                ['LIKE', 'status', $this->global_search],
            ]
        ]);

        if($this->progress <> 'all'){
            $query -> andWhere([
                $value['operator'],'progress',$value['value'] 
            ]);
        }
        return $dataProvider;
        }
}
