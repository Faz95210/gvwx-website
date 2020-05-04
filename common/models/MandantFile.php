<?php


namespace common\models;


use yii\db\ActiveRecord;


/**
 * This is the model class for table "AuthItem".
 *
 * @property int $id
 * @property int $mandant_id
 * @property string $file
 */
class MandantFile extends ActiveRecord {


    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'mandant_file';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id'], 'integer']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'mandant_id' => 'Mandant ID',
            'file' => 'File Name',
        ];
    }
}