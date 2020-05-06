<?php


namespace frontend\controllers;


use common\models\Mandant;
use common\models\MandantFile;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Settings;
use PhpOffice\PhpWord\TemplateProcessor;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class MandantController extends Controller {
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
                            'excel',
                            'new',
                            'edit',
                            'delete',
                            'uploadpdf',
                            'getfile',
                            'deletefile'
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

    public function actionGetfile() {
        $mandat = MandantFile::findOne(['id' => Yii::$app->request->post('file')]);
        return Yii::$app->response->sendFile("../../frontend/web/images/mandats/" . $mandat->file, $mandat->file, ['inline' => true]);
    }

    public function actionDeletefile() {
        $mandat = MandantFile::findOne(['id' => Yii::$app->request->post('file')]);
        $mandat->delete();
        unlink("../../frontend/web/images/mandats/" . $mandat->file);
        $this->redirect(['mandant/get', 'mandantId' => Yii::$app->request->post('mandantId')]);
    }


    public function actionGet() {
        if (Yii::$app->request->get("mandantId", -1) === -1) {
            $mandants = Mandant::find()->where(['user_id' => Yii::$app->user->id])->orderBy(['name' => SORT_ASC])->all();
            $this->view->params = ['mandants' => ($mandants !== null ? $mandants : [])];
            return $this->render("mandants");
        } else {
            $mandant = null;
            $mandantId = Yii::$app->request->get("mandantId", -1);
            if ($mandantId != -1) {
                $mandant = Mandant::findOne(['id' => $mandantId]);
                $mandant->getItems();
            }
            $this->view->params['mandant'] = $mandant;
            $this->view->params['mandats'] = MandantFile::find()->all();
            $dates = [];
            foreach ($mandant->items as $item) {
                $sale = $item->sale;
                if ($sale->date != null) {
                    $dates[] = $sale->date;
                }
            }
            $this->view->params['salesDate'] = array_unique($dates);

            return $this->render("mandant");
        }
    }

    public function actionNew() {
        Mandant::newMandant(Yii::$app->request->post());
        $this->redirect(['/mandant/get']);
    }

    public function actionEdit() {
        $mandant = Mandant::findOne(['id' => Yii::$app->request->post('mandantId')]);
        if ($mandant !== null) {
            $mandant->firstname = Yii::$app->request->post('firstname');
            $mandant->name = Yii::$app->request->post('name');
            $mandant->address = Yii::$app->request->post('address');
            $mandant->postal = Yii::$app->request->post('postal');
            $mandant->phone = Yii::$app->request->post('phone');
            $mandant->city = Yii::$app->request->post('city');
            $mandant->birthdate = Yii::$app->request->post('birthdate');
            $mandant->birthplace = Yii::$app->request->post('birthplace');
            $mandant->mail = Yii::$app->request->post('mail');
            $mandant->update();
        }
        $this->redirect(['/mandant/get', 'mandantId' => Yii::$app->request->post('mandantId')]);
    }


    public function actionDelete() {
        $mandant = Mandant::findOne(['id' => Yii::$app->request->post('deleteMandant')]);
        if ($mandant !== null) {
            $mandant->delete();
        }
        $this->redirect(['/mandant/get']);
    }

    public function actionUploadpdf() {
        $mandantId = Yii::$app->request->post('mandantId');
        foreach ($_FILES as $file) {
            if (move_uploaded_file($_FILES['file']['tmp_name'], "../../frontend/web/images/mandats/${mandantId}_" . $file['name'])) {
                $mandantFile = new MandantFile();
                $mandantFile->mandant_id = $mandantId;
                $mandantFile->file = $mandantId . "_" . $file['name'];
                if ($mandantFile->save())
                    return 1;
                return -1;
            } else {
                return 0;
            }
        }
        return 1;
    }

    public function actionExcel() {
        $mandants = Mandant::findAll(['user_id' => Yii::$app->user->id]);
        PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);

        $objPHPExcel = new PHPExcel;
        $s = $objPHPExcel->getActiveSheet();
        for ($i = 0; $i < count($mandants); $i++) {
            $s->setCellValue('A' . ($i + 1), $mandants[$i]->name);
            $s->setCellValue('B' . ($i + 1), $mandants[$i]->firstname);
        }
        $writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        header('Content-Disposition: attachment;filename="MandantsList.xlsx"');
        $writer->save('php://output');
    }

    public function actionFacture() {
        $mandant = Mandant::findOne(['id' => Yii::$app->request->post('mandantId')]);
        $mandant->getItems();
        $templateProcessor = new TemplateProcessor("../assets/modelmandant.docx");

        $templateProcessor->setValue('DATE', Yii::$app->request->post('dateSale'));
        $templateProcessor->setValue('USER_NAME', $mandant->name . ' ' . $mandant->firstname);
        $values = [];
        foreach ($mandant->items as $item) {
            if ($item->sale && $item->sale->date == Yii::$app->request->post('dateSale')) {
                $item->sale->getPrices(str_replace(",", ".", Yii::$app->request->post('fees')));
                $round = round($item->adjudication * (str_replace(",", ".", Yii::$app->request->post('fees')) / 100), 2);
                $values[] = [
                    'ITEM_NAME' => $item->name,
                    'ITEM_ADJUDICATION' => $item->adjudication,
                    'ITEM_DESCRIPTION' => $item->description,
                    'ITEM_FEES' => $round,
                    'ITEM_TOTAL' => $item->adjudication - $round,
                ];
            }
        }
        $templateProcessor->cloneRowAndSetValues('ITEM_NAME', $values);

        header('Content-Disposition: attachment;filename="Mandant_' . $mandant->name . '_' . $mandant->firstname . '.docx"');
        $templateProcessor->saveAs('php://output');
        exit;
    }
}