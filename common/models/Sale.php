<?php


namespace common\models;


use DateTime;
use yii\db\ActiveRecord;

/**
 * User model
 *
 * @property integer $id
 * @property integer $date
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

    public function getSalesStep($clientId = null) {
        $saleSteps = SaleStep::find()->where(['sale_id' => $this->id]);
        if ($clientId !== null)
            $saleSteps->andWhere(['client_id' => $clientId]);
        $this->saleSteps = $saleSteps->all();
        foreach ($this->saleSteps as $saleStep) {
            $saleStep->getItem();
            $saleStep->getClient();
        }
    }

    public function getPrices($fee) {
        if ($this->saleSteps == []) {
            $this->getSalesStep(null);
        }
        $totalPrice = 0;
        foreach ($this->saleSteps as $saleStep) {
            $totalPrice += $saleStep->item->adjudication;
        }
        $this->prices = [
            'price' => $totalPrice,
            'fees' => round($totalPrice * $fee / 100, 2),
            'feetax' => round(($totalPrice * $fee / 100) - (($totalPrice * $fee / 100) / 1.2), 2),
            'total' => $totalPrice + round($totalPrice * $fee / 100, 2)
        ];
    }

    public static function newSale(array $post) {

        $date = DateTime::createFromFormat('d/m/Y', $post['date']);
        $sale = new Sale([
            'date' => $date->getTimestamp(),
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
            [['date', 'user_id'], 'number'],
        ];
    }


}