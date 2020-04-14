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
 * @property integer $adjudicataire_number
 * @property integer $lot_number
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
            "item_id" => $post['itemId'],
            "client_id" => $post['clientId'],
            "sale_id" => $post['saleId'],
            "lot_number" => $post['lotNumber'],
            "adjudicataire_number" => $post['adjudicataire_number'],
        ]);
        $res1 = $sale->save();
        $item = Item::findOne(['id' => $post['itemId']]);
        $item->adjudication = $post['adjudication'];
        if ($res1 && $item->update()) {
            return $sale->id;
        }
        return $sale->id;
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['sale_id'], 'required']
        ];
    }
}