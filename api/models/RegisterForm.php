<?php

namespace api\models;

use phpDocumentor\Reflection\Types\String_;
use Yii;
use yii\base\Model;
use common\models\Device;

/**
 * Register form
 */
class RegisterForm extends Model {
    public $imei;
    private $password;

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            ['imei', 'trim'],
            ['imei', 'required'],
            ['imei', 'string', 'min' => 6, 'max' => 255],
            ['imei', 'unique', 'targetClass' => '\common\models\Device', 'message' => \Yii::t('api', 'IMEI déjà enregistré')]
        ];
    }

    public function __construct() {
        $this->password = Yii::$app->security->generateRandomString();
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getPassword() {
        return $this->password;
    }

    /**
     * Register user up.
     *
     * @return bool whether the creating new device was successful
     */
    public function register() {
        if (!$this->validate()) {
            return null;
        }

        $device = new Device();
        $device->imei = $this->imei;
        $device->setPassword($this->password);
        $device->generateAuthKey();
        $device->generateApiKey();
        $device->generateImeiVerificationToken();

        if ($device->save()) {
            return $device;
        } else {
            return null;
        }

    }
}
