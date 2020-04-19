<?php


namespace common\models;


use DateTime;
use Yii;
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
 * @property integer $date_mandat
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
        $date = DateTime::createFromFormat('Y-m-d H:i:s',
            Yii::$app->request->post('date_mandat') . " 00:00:00");

        $item = new Item([
            'name' => $post['name'],
            'description' => $post['description'],
            'estimation' => $post['estimation'],
            'mandant_id' => $post['mandant'],
            'date_mandat' => $date->getTimestamp(),
            'user_id' => Yii::$app->user->id,
        ]);

        $item->save();
        $handle = fopen("../../frontend/web/images/items/$item->id", 'w') or die('Cannot open file'); //implicitly creates file
        $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $post['picture']));
        fwrite($handle, $data);
        fclose($handle);
        $item->picture = "items/$item->id";
        $item->save();
        if ($post['sale_date'] != '') {
            $date = DateTime::createFromFormat('d/m/Y H:i:s', $post['sale_date'] . " 00:00:00");
            $sale = Sale::findOne(['date' => $date->getTimestamp(), 'user_id' => Yii::$app->user->id]);
            if ($sale == null) {
                $sale = new Sale(['user_id' => Yii::$app->user->id, 'date' => $date->getTimestamp()]);
                $sale->save();
            }
            $saleStep = new SaleStep([
                'item_id' => $item->id,
                'client_id' => $post['client_id'] == -1 ? null : $post['client_id'],
                'sale_id' => $sale->id,
                'lot_number' => $post['lot_number'],
            ]);
            $saleStep->save();
            $item->adjudication = $post['estimation'];
            $item->update();
        }
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