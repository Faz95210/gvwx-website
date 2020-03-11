<?php


namespace common\models;


use yii\db\ActiveRecord;

/**
 * User model
 *
 * @property integer $id
 * @property integer $sale_id
 * @property integer $client_id
 * @property integer $item_id
 *
 */
class SaleStep extends ActiveRecord {

    public $sale = null;
    public $client = null;
    public $item = null;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'sale_step';
    }

    public function getSale() {
        $this->sale = Sale::findOne(['id' => $this->sale_id]);
    }

    public function getItem() {
        $this->item = Item::findOne(['id' => $this->item_id]);
    }

    public function getClient() {
        $this->client = Client::findOne(['id' => $this->client_id]);
    }

    public static function newSaleStep(array $post) {
        $sale = new self([
            "item_id" => $post['item'],
            "client_id" => $post['client'],
            "sale_id" => $post['sale'],
        ]);

        if ($sale->save()) {
            $item = Item::findOne(['id' => $post['item']]);
            $item->adjudication = $post['adjudication'];
            $item->update();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['client_id', 'sale_id'], 'required']
        ];
    }
}