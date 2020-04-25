<?php


namespace frontend\models;


use common\models\User;
use yii\base\Model;

class PasswordResetForm extends Model {

    public $email;
    public $password;
    public $confirmationPassword;

    public function rules() {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'string', 'min' => 2, 'max' => 255],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            ['confirmationPassword', 'required'],
            ['confirmationPassword', 'string', 'min' => 6],
        ];
    }

    public function changePassword() {
        if (!$this->validate()) {
            return null;
        }
        $user = User::findByEmail($this->email);
        if ($user != null && $this->password === $this->confirmationPassword) {
            $user->setPassword($this->password);
            $user->save();
            return 1;
        }
        return -1;
    }
}