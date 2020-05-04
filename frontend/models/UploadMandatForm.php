<?php


namespace frontend\models;

use yii\base\Model;
use yii\web\UploadedFile;

class UploadMandatForm extends Model {
    /**
     * @var UploadedFile
     */
    public $mandat;
    public $mandant_id;

    public function rules() {
        return [
            [['mandat'], 'file', 'skipOnEmpty' => false, 'extensions' => 'pdf'],
            [['mandantId'], 'integer']
        ];
    }

    public function upload() {
        if ($this->validate()) {
            $this->mandat->saveAs('images/' . $this->mandant_id . '_' . $this->mandat->baseName . '.' . $this->mandat->extension);
            return true;
        } else {
            return false;
        }
    }
}
