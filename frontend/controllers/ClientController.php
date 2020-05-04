<?php


namespace frontend\controllers;


use common\models\Client;
use common\models\Sale;
use common\models\User;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Settings;
use PhpOffice\PhpWord\TemplateProcessor;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class ClientController extends Controller {
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
                            'facture',
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
        if (Yii::$app->request->get("clientId", -1) === -1) {
            $clients = Client::find()->where(['user_id' => Yii::$app->user->id])
                ->orderBy(['name' => SORT_ASC])
                ->all();
            $this->view->params = ['clients' => ($clients !== null ? $clients : [])];
            return $this->render("clients");
        } else {
            $client = Client::findOne(['id' => Yii::$app->request->get("clientId", -1)]);
            $client->getSales();
            $dates = [];
            foreach ($client->sales as $sale) {
                if ($sale->date !== null) {
                    $dates[] = $sale->date;
                }
            }
            $this->view->params['salesDate'] = array_unique($dates);
            $this->view->params['client'] = $client;
            return $this->render("client");
        }
    }

    public function actionExcel() {
        $clients = Client::findAll(['user_id' => Yii::$app->user->id]);
        PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);

        $objPHPExcel = new PHPExcel;
        $s = $objPHPExcel->getActiveSheet();
        for ($i = 0; $i < count($clients); $i++) {
            $s->setCellValue('A' . ($i + 1), $clients[$i]->name);
            $s->setCellValue('B' . ($i + 1), $clients[$i]->firstname);
        }
        $writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        header('Content-Disposition: attachment;filename="ClientList.xlsx"');
        $writer->save('php://output');
    }

    public function actionNew() {
        Client::newClient(Yii::$app->request->post());
        return $this->redirect(['/client/get']);
    }

    public function actionEdit() {
        Client::editClient(Yii::$app->request->post());
        return $this->redirect(['/client/get', 'clientId' => Yii::$app->request->post('clientId')]);

    }

    public function actionDelete() {
        $client = Client::findOne(['id' => Yii::$app->request->post('clientId')]);
        if ($client !== null) {
            $client->delete();
        }
        $this->redirect(['/client/get']);
    }

    public function actionFacture() {
        $sale = Sale::find()
            ->innerJoin('sale_step', 'sale_step.sale_id = sale.id')
            ->where(['sale_step.client_id' => Yii::$app->request->post('clientId')])
            ->andWhere(['date' => Yii::$app->request->post('dateSale')])
            ->one();
        if ($sale != null) {
            $sale->getSalesStep(Yii::$app->request->post('clientId'));
            $sale->getPrices(Yii::$app->request->post('fees'));
        }

        $templateProcessor = new TemplateProcessor("../assets/modelFacture.docx");
        $user = User::findOne(['id' => Yii::$app->user->id]);
        if ($user->logo != null) {
            $temp = tmpfile();
            $handle = fopen("../web/images/" . $user->logo, 'r');
            $data = fread($handle, filesize("../web/images/" . $user->logo));
            fclose($handle);
            fwrite($temp, $data);
            $templateProcessor->setImageValue('logo', stream_get_meta_data($temp)['uri']);
        } else {
            $templateProcessor->setValue('logo', $sale->saleSteps[0]->client->name);

        }

        if ($user->marianne != null) {
//            $temp = tmpfile();
//            $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $user->marianne));
//
//            fwrite($temp, $data);

            $temp = tmpfile();
            $handle = fopen("../web/images/" . $user->marianne, 'r');
            $data = fread($handle, filesize("../web/images/" . $user->marianne));
            fclose($handle);
            fwrite($temp, $data);
            $templateProcessor->setImageValue('marianne', stream_get_meta_data($temp)['uri']);

//            $templateProcessor->setImageValue('marianne', $user->marianne);
        } else {

            $templateProcessor->setValue('marianne', $sale->saleSteps[0]->client->name);
        }

        $templateProcessor->setValue('NAME', $sale->saleSteps[0]->client->name);
        $templateProcessor->setValue('FIRSTNAME', $sale->saleSteps[0]->client->firstname);
        $templateProcessor->setValue('ADRESSE', $sale->saleSteps[0]->client->address);
        $templateProcessor->setValue('POSTAL', $sale->saleSteps[0]->client->postal);
        $templateProcessor->setValue('CITY', $sale->saleSteps[0]->client->city);
        $templateProcessor->setValue('PHONE', $sale->saleSteps[0]->client->phone);
        $templateProcessor->setValue('MAIL', $sale->saleSteps[0]->client->mail);

        $templateProcessor->setValue('DATE', $sale->date);

        $templateProcessor->setValue('FEE', Yii::$app->request->post('fees'));
        $templateProcessor->setValue('TOTALRAW', $sale->prices['price']);
        $templateProcessor->setValue('TOTALFEE', $sale->prices['fees']);
        $templateProcessor->setValue('RAWPLUSFEE', $sale->prices['feetax']);
        $templateProcessor->setValue('TOTAL', $sale->prices['total']);

        $templateProcessor->setValue('QUANTITY', count($sale->saleSteps));

        $values = [];
        foreach ($sale->saleSteps as $saleStep) {
            $values[] = [
                'ITEMID' => $saleStep->lot_number,
                'ITEMNAME' => $saleStep->item->name,
                'ITEMPRICE' => $saleStep->item->adjudication,
            ];
        }
        $templateProcessor->cloneRowAndSetValues('ITEMID', $values);
        header('Content-Disposition: attachment;filename="Facture_' . $sale->saleSteps[0]->client->name . '_' . $sale->saleSteps[0]->client->firstname . '.docx"');
        $templateProcessor->saveAs('php://output');
        exit;
    }
}
