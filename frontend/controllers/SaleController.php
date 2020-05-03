<?php


namespace frontend\controllers;


use common\models\Client;
use common\models\Item;
use common\models\Sale;
use common\models\SaleStep;
use DateTime;
use Fpdf\Fpdf;
use PhpOffice\PhpWord\TemplateProcessor;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class SaleController extends Controller {
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
                            'pvvente',
                            'facture',
                            'new',
                            'addstep',
                            'editstep',
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


    /**
     * {@inheritdoc}
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionGet() {
        if (Yii::$app->request->get("saleId", -1) === -1) {
            $sales = Sale::find()->where(['user_id' => Yii::$app->user->id])->orderBy(['date' => SORT_DESC])->all();
            $this->view->params = ['sales' => ($sales !== null ? $sales : [])];
            return $this->render("sales");

        } else {
            $sale = null;
            $saleId = Yii::$app->request->get("saleId", -1);

            if ($saleId != -1) {
                $sale = Sale::findOne(['id' => $saleId]);
                $sale->getSalesStep(null);
            }

            $items = Item::findAll(['user_id' => Yii::$app->user->id]);
            $this->view->params['sale'] = $sale;
            $this->view->params['items'] = $items;
            $this->view->params['clients'] = Client::findAll(['user_id' => Yii::$app->user->id]);

            return $this->render("sale");

        }
    }

    public function actionNew() {
        Sale::newSale(Yii::$app->request->post());
        return $this->redirect(['/sale/get']);
    }


    public function actionPvvente() {
        $sale = Sale::find()
            ->where(['id' => Yii::$app->request->post('saleId')])
            ->one();
        $sale->getSalesStep();
        $pdf = new FPDF();
        $pdf->SetTitle("PV " . $sale->date);
        $templateProcessor = new TemplateProcessor("../assets/modelpvvente.docx");
        $templateProcessor->setValue('DATE', date('m/d/Y', $sale->date));
        $templateProcessor->setValue('AMOUNT', Yii::$app->request->post('fees'));

        $totalPrice = 0;
        $values = [];
        foreach ($sale->saleSteps as $saleStep) {
            $values[] = [
                'ITEMID' => $saleStep->lot_number,
                'ITEMNAME' => $saleStep->item->name,
                'ITEMDESC' => $saleStep->item->description,
                'ITEMPRICE' => $saleStep->item->adjudication,
            ];
            $totalPrice += $saleStep->item->adjudication;
        }
        $templateProcessor->cloneRowAndSetValues('ITEMID', $values);

        $templateProcessor->setValue('SUMPRICES', $totalPrice);
        $templateProcessor->setValue('SUMFEES', $totalPrice * (Yii::$app->request->post('fees') / 100));

        header('Content-Disposition: attachment;filename="PVVENTE_' . $sale->date . '.docx"');
        $templateProcessor->saveAs('php://output');
        exit;
    }

    public function actionAddstep() {
        return SaleStep::newSaleStep(Yii::$app->request->post());
    }


    public function actionDelete() {
        $saleId = Yii::$app->request->post('saleId');

        Sale::findOne(['id' => $saleId])->delete();

        $this->redirect(['/sale/get']);
    }

    public function actionEdit() {
        $sale = Sale::findOne(['id' => Yii::$app->request->post('saleId')]);
        $date = DateTime::createFromFormat('Y-m-d H:i:s',
            Yii::$app->request->post('dateSale') . " 00:00:01");

        $sale->date = $date->getTimestamp();
        $sale->update();
        $this->redirect(['/sale/get', 'saleId' => Yii::$app->request->post('saleId')]);
    }

    public function actionEditstep() {
        $post = Yii::$app->request->post();
        $step = null;
        if (key_exists('saleStepEdit', $post)) {
            $item = Item::findOne(['id' => $post['itemId']]);
            if ($item != null) {
                $item->adjudication = $post['adjudication'];
                $item->save();
            }

            $step = SaleStep::findOne(['id' => $post['saleStepEdit']]);
            if ($step !== null) {
                $step->client_id = $post['clientId'];
                $step->adjudicataire_number = $post['adjudicataire_number'];
                $step->lot_number = $post['lotNumber'];
                $step->save();
            }
            return $this->redirect(['sale/get', 'saleId' => $step->sale_id]);
        } else if (key_exists('saleStepDelete', $post)) {
            $item = Item::findOne(['id' => $post['itemId']]);
            if ($item != null) {
                $item->adjudication = 0;
                $item->save();
            }

            $step = SaleStep::findOne(['id' => $post['saleStepDelete']]);
            $saleId = $step->sale_id;
            if ($step !== null) {
                $step->delete();
            }
            return $this->redirect(['sale/get', 'saleId' => $saleId]);

        }
    }
}