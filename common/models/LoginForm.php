<?php

namespace common\models;

use DateTime;
use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model {
    public $email;
    public $password;
    public $rememberMe = true;

    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            // email and password are both required
            [['email', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params) {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, \Yii::t('login', 'Utilisateur ou mot de passe incorrect'));
            }
        }
    }

    public function checkInactiveUser() {
        $this->_user = User::findInactiveByEmail($this->email);
        if ($this->_user) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Logs in a user using the provided email and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login() {
        if ($this->validate()) {
            $date = DateTime::createFromFormat('d/m/Y', $this->getUser()->license_date);
            if ($this->getUser()->license_paid && $date != false && time() < $date->getTimestamp())
                return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
            else {
                if (!$this->getUser()->license_paid || $date != false) {
                    $this->addError('password', \Yii::t('login', 'Votre licence a expirée, merci de contacter votre administrateur.'));
                }
                return false;
            }
        }
        return false;
    }

    /**
     * Finds user by [[email]]
     *
     * @return User|null
     */
    public function getUser() {
        if ($this->_user === null) {
            $this->_user = User::findByEmail($this->email);
        }

        return $this->_user;
    }
}
