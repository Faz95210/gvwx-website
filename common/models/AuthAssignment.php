<?php


namespace common\models;


use yii\db\ActiveRecord;

/**
 * This is the model class for table "Company".
 *
 * @property int $id
 * @property int $user_id
 * @property int $created_at
 * @property int $updated_at
 * @property string $item_name
 */
class AuthAssignment extends ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'auth_assignment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id'], 'integer'],
            [['item_name'], 'string', 'max' => 100]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'user_id' => 'User Id',
            'item_name' => 'Item name',
            'created_at' => 'Created at',
            'updated_at' => 'Updated at',
        ];
    }
}