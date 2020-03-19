<?php


namespace common\models;


use yii\db\ActiveRecord;

/**
 * User model
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property double $estimation
 * @property double $adjudication
 * @property string $picture
 * @property string $qrcode
 * @property integer $mandant_id
 *
 */
class Item extends ActiveRecord {

    public $sale = null;
    public $client = null;
    public $mandant = null;


    public function getMandant() {
        $this->mandant = Mandant::findOne(['id' => $this->mandant_id]);
    }

    public function getSale() {
        $this->sale = Sale::find()->innerJoin('sale_step', 'sale_step.sale_id = sale.id')->where(['sale_step.item_id' => $this->id])->one();
    }

    public function getClient() {
        $this->client = Client::find()->innerJoin('sale_step', 'sale_step.client_id = client.id')->where(['sale_step.item_id' => $this->id])->one();
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'item';
    }

    public static function newItem(array $post) {
        $item = new Item([
            'name' => $post['name'],
            'description' => $post['description'],
            'estimation' => $post['estimation'],
            'mandant_id' => $post['mandant'],
            'picture' => $post['picture'],
            'user_id' => \Yii::$app->user->id,
        ]);
        return $item->save();

    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['name', 'mandant_id', 'estimation'], 'required'],
            [['adjudication', 'mandant_id', 'estimation'], 'number'],
        ];
    }


}