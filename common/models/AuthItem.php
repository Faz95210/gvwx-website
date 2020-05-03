<?php


namespace common\models;


use yii\db\ActiveRecord;


/**
 * This is the model class for table "AuthItem".
 *
 * @property int $id
 * @property int $type
 * @property string $description
 * @property string $rule_name
 * @property string $data
 * @property int $created_at
 * @property int $updated_at
 */
class AuthItem extends ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'auth_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id'], 'integer'],
            [['name'], 'string', 'max' => 100]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'data' => 'Data',
            'description' => 'Description',
            'rule_name' => 'Rule name',
            'created_at' => 'Created at',
            'updated_at' => 'Updated at',
        ];
    }

}