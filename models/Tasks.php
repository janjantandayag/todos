<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tasks".
 *
 * @property integer $task_id
 * @property string $title
 * @property string $description
 * @property integer $progress
 * @property string $priority
 * @property string $status
 *
 * @property UserTask[] $userTasks
 * @property User[] $users
 */
class Tasks extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tasks';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'description', 'progress', 'priority', 'status'], 'required'],
            [['progress'], 'integer'],
            [['priority', 'status'], 'string'],
            [['title'], 'string', 'max' => 50],
            [['description'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'task_id' => 'Task ID',
            'title' => 'Title',
            'description' => 'Description',
            'progress' => 'Progress',
            'priority' => 'Priority',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserTasks()
    {
        return $this->hasMany(UserTask::className(), ['task_id' => 'task_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['user_id' => 'user_id'])->viaTable('user_task', ['task_id' => 'task_id']);
    }
}
