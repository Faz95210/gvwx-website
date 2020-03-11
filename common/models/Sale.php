<?php


namespace common\models;


use yii\db\ActiveRecord;

/**
 * User model
 *
 * @property integer $id
 * @property integer $date
 * @property integer $client_id
 * @property integer $user_id
 *
 */
class Sale extends ActiveRecord {

    public $saleSteps = [];
    public $prices = [];

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'sale';
    }

    public function getSalesStep() {
        $saleSteps = SaleStep::findAll(['sale_id' => $this->id]);
        if ($saleSteps != null) $this->saleSteps = $saleSteps;
        foreach ($saleSteps as $saleStep) {
            $saleStep->getItem();
            $saleStep->getClient();
        }
    }

    public function getPrices() {
        if ($this->saleSteps == []) {
            $this->getSalesStep();
        }
        $totalPrice = 0;
        foreach ($this->saleSteps as $saleStep) {
            $totalPrice += $saleStep->item->adjudication;
        }
        $this->prices = [
            'price' => $totalPrice,
            'fees' => $totalPrice * 20 / 100,
            'feetax' => ($totalPrice * 20 / 100) - (($totalPrice * 20 / 100) / 1.2),
            'total' => $totalPrice + ($totalPrice * 20 / 100)
        ];
    }

    public static function newSale(array $post) {

        $sale = new Sale([
            'date' => strtotime($post['date']),
            'user_id' => \Yii::$app->user->id
        ]);
        return $sale->save();
    }


    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['date', 'user_id'], 'required'],
            [['date', 'user_id', 'client_id'], 'number'],
        ];
    }


}