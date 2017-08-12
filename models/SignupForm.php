<?php
namespace app\models;

use yii\base\Model;
use app\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $first_name;
    public $last_name;
    public $username;
    public $password;
    public $file;
    public $profile_pic;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\app\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            ['last_name', 'required'],
            ['last_name', 'string', 'min' => 2, 'max' => 50],

            ['first_name', 'required'],
            ['first_name', 'string', 'min' => 2, 'max' => 50],

            [['file'], 'file'],

            ['profile_pic','string']
        ];
    }

    public function attributeLabels()
    {
        return [
            'file' => 'Upload Profile Picture'
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->first_name = $this->username;
        $user->last_name = $this->last_name;
        $user->username = $this->username;
        $user->profile_pic = $this->profile_pic;
        $user->encryptPassword($this->password);        
        return $user->save() ? $user : null;
    }

    
}
