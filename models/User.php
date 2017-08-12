<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property integer $user_id
 * @property string $first_name
 * @property string $last_name
 * @property string $username
 * @property string $password
 *
 * @property UserTask[] $userTasks
 * @property Tasks[] $tasks
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name', 'username', 'password'], 'required'],
            [['first_name', 'last_name'], 'string', 'max' => 50],
            [['username', 'password'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'username' => 'Username',
            'password' => 'Password',
        ];
    }
        /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserTasks()
    {
        return $this->hasMany(UserTask::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Tasks::className(), ['task_id' => 'task_id'])->viaTable('user_task', ['user_id' => 'user_id']);
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        // return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        // return $this->getAuthKey() === $authKey;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['user_id' => $id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    public function encryptPassword($password)
    {
        $this->password = sha1($password);
    }

    public function validatePassword($password)
    {
        return $this->password === $this->decryptPassword($password);        
    }

    public function decryptPassword($password)
    {
        return sha1($password);
    }

    public static function hasThisTask($id)
    {
        $user = User::findOne(Yii::$app->user->identity->user_id);
        $tasks = $user->tasks;
        $taskIDs = [];
        foreach($tasks as $task){
            array_push($taskIDs, $task->task_id);
        }
        if(in_array($id, $taskIDs)){
            return true;
        } else {
            return false;
        }
    }
}
