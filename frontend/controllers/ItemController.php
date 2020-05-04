<?php


namespace frontend\controllers;


use common\models\Client;
use common\models\Item;
use common\models\Mandant;
use DateTime;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Settings;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class ItemController extends Controller {
    public $layout = "veltrix";

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                //'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => [
                            'get',
                            'excel',
                            'new',
                            'edit',
                            'delete',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    'addsalestep' => ['post'],
                ],
            ],
        ];
    }

    public function actionGet() {
        if (Yii::$app->request->get("itemId", -1) === -1) {
            $items = Item::find()->where(['user_id' => Yii::$app->user->id])->orderBy(['name' => SORT_ASC])->all();
            $mandants = Mandant::find()->where(['user_id' => Yii::$app->user->id])->orderBy(['name' => SORT_ASC])->all();
            $clients = Client::find()->where(['user_id' => Yii::$app->user->id])->orderBy(['name' => SORT_ASC])->all();
            $this->view->params = ['items' => ($items !== null ? $items : []), 'mandants' => ($mandants !== null ? $mandants : [])];
            $this->view->params['clients'] = $clients;
            return $this->render("items");
        } else {
            $item = null;
            $itemId = Yii::$app->request->get("itemId", -1);
            if ($itemId != -1) {
                $item = Item::findOne(['id' => $itemId]);
                $item->getSale();
                $item->getClient();
                $item->getMandant();
            }
            $this->view->params['item'] = $item;
            $this->view->params['mandants'] = Mandant::findAll(['user_id' => Yii::$app->user->id]);
            $this->view->params['clients'] = Client::findAll(['user_id' => Yii::$app->user->id]);
            return $this->render("item");
        }
    }


    public function actionExcel() {
        $items = Item::findAll(['user_id' => Yii::$app->user->id]);
        PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);

        $objPHPExcel = new PHPExcel;
        $s = $objPHPExcel->getActiveSheet();
        for ($i = 0; $i < count($items); $i++) {
            $s->setCellValue('A' . ($i + 1), $items[$i]->id);
            $s->setCellValue('B' . ($i + 1), $items[$i]->name);
        }
        $writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        header('Content-Disposition: attachment;filename="ItemsList.xlsx"');
        $writer->save('php://output');
    }

    public function actionNew() {
        Item::newItem(Yii::$app->request->post());
        return $this->redirect(['/item/get']);
    }


    public function actionEdit() {
        $item = Item::findOne(['id' => Yii::$app->request->post('itemId')]);
        if ($item !== null) {
            $item->name = Yii::$app->request->post('name');
            $item->description = Yii::$app->request->post('description');
            $item->estimation = Yii::$app->request->post('estimation');
            $item->estimation2 = Yii::$app->request->post('estimation2');
            $handle = fopen("../../frontend/web/images/items/$item->id", 'w') or die('Cannot open file'); //implicitly creates file
            $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', Yii::$app->request->post('picture')));
            fwrite($handle, $data);
            fclose($handle);
            $item->picture = "items/$item->id";
            $item->mandant_id = Yii::$app->request->post('mandantId') == -1 ? null : Yii::$app->request->post('mandantId');

            $item->date_mandat = Yii::$app->request->post('date_mandat');
            $item->update();
        }
        $this->redirect(['/item/get', 'itemId' => Yii::$app->request->post('itemId')]);
    }

    public function actionDelete() {
        $item = Item::findOne(['id' => Yii::$app->request->post('itemId')]);
        if ($item !== null) {
            $item->delete();
        }
        $this->redirect(['/item/get']);
    }


}