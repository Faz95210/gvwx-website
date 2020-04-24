<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model {
    public $firstname;
    public $name;
    public $email;
    public $password;

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            ['firstname', 'trim'],
            ['firstname', 'required'],
            ['firstname', 'string', 'min' => 2, 'max' => 255],

            ['name', 'trim'],
            ['name', 'required'],
            ['name', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => \Yii::t('login', 'Cet email est déjà utilisé')],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup() {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->firstname = $this->firstname;
        $user->name = $this->name;
        $user->email = $this->email;
        $user->status = 10;

        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        $res = $user->save();

        return $res;
        //Do it here so we have a user ID
    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($user) {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' website'])
            ->setTo($this->email)
            ->setSubject(\Yii::t('login', 'Création de compte IKAR'))
            ->send();
    }
}
