<?php

namespace frontend\controllers;

use common\models\Client;
use common\models\Item;
use common\models\LoginForm;
use common\models\Mandant;
use common\models\Sale;
use common\models\SaleStep;
use common\models\User;
use DateTime;
use Fpdf\Fpdf;
use frontend\models\SignupForm;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Settings;
use PhpOffice\PhpWord\TemplateProcessor;
use Yii;
use yii\base\InvalidArgumentException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

/**
 * Site controller
 */
class SiteController extends Controller {
    public $layout = "veltrix";
    public $enableCsrfValidation = false;


    private function getStatusCodeMessage($status) {
        $codes = array(
            // Success 2xx
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            // Client Error 4xx
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            // Server Error 5xx
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported',
            509 => 'Bandwidth Limit Exceeded',
        );
        return (isset($codes[$status])) ? $codes[$status] : '';
    }


    private function setHeader($status) {
        $status_header = 'HTTP/1.1 ' . $status . ' ' . $this->getStatusCodeMessage($status);
        $content_type = "application/json; charset=utf-8";
        header($status_header);
        header('Content-type: ' . $content_type);
        header('X-Powered-By: ' . "WantCode <WantCode.in>");
    }

    public function jsonResponse($code, $data = '') {
        $response = array();
        $message = $this->getStatusCodeMessage($code);
        if (!empty($message)) {
            //$response = array("status" => false, "message" => $message, "data" => $data, "code" => $code);
            $response = $data;
        }
        $this->setHeader($code);

        echo json_encode($response);
        die;
    }


    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                //'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => [
                            'login',
                            'error',
                            'signup',
                            'verify-email',
                            'resend-verification-email',
                            'reset-password',
                            'widgetloader',
                            'request-password-reset',

                        ],
                        'allow' => true,
                        //'roles' => ['?'],
                    ],
                    [
                        'actions' => [
                            'logout',
                            'index',
                            'addsalestep',
                            'items',
                            'itemsexcel',
                            'item',
                            'newitem',
                            'edititem',
                            'deleteitem',
                            'sales',
                            'sale',
                            'newsale',
                            'editsale',
                            'deletesale',
                            'mandants',
                            'mandantsexcel',
                            'mandant',
                            'newmandant',
                            'editmandant',
                            'facturemandant',
                            'deletemandant',
                            'editprofile',
                            'profile',
                            'pvvente',
                            'generatefacture',
                            'clients',
                            'client',
                            'clientexcel',
                            'newclient',
                            'editclient',
                            'deleteclient',
                            'editsalestep'
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

    public function afterAction($action, $result) {
        return parent::afterAction($action, $result);
    }


    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex() {
        return $this->render('home');
    }

    public function actionProfile() {
        $this->view->params['user'] = User::findOne(['id' => Yii::$app->user->id]);
        return $this->render('profile');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin() {
        $this->layout = "veltrixLogin";

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup() {
        $this->layout = "veltrixLogin";

        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash(\Yii::t('login', 'Confirmation'), \Yii::t('login', "Merci pour votre inscription, vérifiez votre email pour plus d'instructions"));
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset() {
        $this->layout = "veltrixLogin";

        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash(\Yii::t('login', 'Confirmation'), \Yii::t('login', "Vérifiez votre email pour plus d'instructions"));

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash(\Yii::t('login', 'Erreur'), \Yii::t('login', "Désolé, nous n'arrivons pas à renouveller le mot de passe pour l'adresse email donée."));
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token) {
        $this->layout = "veltrixLogin";

        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash(\Yii::t('login', 'Confirmation'), \Yii::t('login', 'Nouveau mot de passe enregistré'));

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @return yii\web\Response
     * @throws BadRequestHttpException
     */
    public function actionVerifyEmail($token) {
        $this->layout = "veltrixLogin";

        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($user = $model->verifyEmail()) {
            if (Yii::$app->user->login($user)) {
                Yii::$app->session->setFlash(\Yii::t('login', 'Confirmation'), \Yii::t('login', 'Votre email a bien été vérifié'));
                return $this->goHome();
            }
        }

        Yii::$app->session->setFlash(\Yii::t('login', 'Erreur'), \Yii::t('login', 'Désolé, nous sommes incapable de vérifier votre compte.'));
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail() {
        $this->layout = "veltrixLogin";

        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash(\Yii::t('login', 'Confirmation'), \Yii::t('login', "Vérifiez votre email pour plus d'instructions"));
                return $this->goHome();
            }
            Yii::$app->session->setFlash(\Yii::t('login', 'Erreur'), \Yii::t('login', "Désolé, nous ne pouvons pas renvoyer d'email de confirmation à cette adresse."));
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }


    public function actionItems() {
        $items = Item::find()->where(['user_id' => Yii::$app->user->id])->orderBy(['name' => SORT_ASC])->all();
        $mandants = Mandant::find()->where(['user_id' => Yii::$app->user->id])->orderBy(['name' => SORT_ASC])->all();
        $clients = Client::find()->where(['user_id' => Yii::$app->user->id])->orderBy(['name' => SORT_ASC])->all();
        $this->view->params = ['items' => ($items !== null ? $items : []), 'mandants' => ($mandants !== null ? $mandants : [])];
        $this->view->params['clients'] = $clients;
        return $this->render("items");
    }

    public function actionItem() {
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

    public function actionNewitem() {
        Item::newItem(Yii::$app->request->post());
        return $this->redirect(['/site/items']);
    }

    public function actionMandants() {
        $mandants = Mandant::find()->where(['user_id' => Yii::$app->user->id])->orderBy(['name' => SORT_ASC])->all();
        $this->view->params = ['mandants' => ($mandants !== null ? $mandants : [])];
        return $this->render("mandants");
    }

    public function actionMandant() {
        $mandant = null;
        $mandantId = Yii::$app->request->get("mandantId", -1);
        if ($mandantId != -1) {
            $mandant = Mandant::findOne(['id' => $mandantId]);
            $mandant->getItems();
        }
        $this->view->params['mandant'] = $mandant;
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

    public function actionNewmandant() {
        Mandant::newMandant(Yii::$app->request->post());
        header("Location: " . Yii::$app->homeUrl . "?r=site/mandants");
        exit;
    }

    public function actionSales() {
        $sales = Sale::find()->where(['user_id' => Yii::$app->user->id])->orderBy(['date' => SORT_DESC])->all();
        $this->view->params = ['sales' => ($sales !== null ? $sales : [])];
        return $this->render("sales");
    }


    public function actionWidgetloader() {
        $klass = "";
        $params = [];
        foreach (Yii::$app->request->get() as $key => $value) {
            if ($key === "r") {
                continue;
            }
            if ($key === "widget") {
                $klass = "\common\widgets\\$value\\$value";
            } else {
                $params[$key] = $value;
            }
        }
        return $klass::widget($params);
    }

    public function actionSale() {
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

    public function actionNewsale() {
        Sale::newSale(Yii::$app->request->post());
        return $this->actionSales();
    }

    public function actionClients() {
        $clients = Client::find()->where(['user_id' => Yii::$app->user->id])
            ->orderBy(['name' => SORT_ASC])
            ->all();
        $this->view->params = ['clients' => ($clients !== null ? $clients : [])];
        return $this->render("clients");
    }

    public function actionClient($clientId = -1) {
        $client = null;
        if ($clientId === -1)
            $clientId = Yii::$app->request->get("clientId", -1);
        if ($clientId != -1) {
            $client = Client::findOne(['id' => $clientId]);
            $client->getSales();
        }
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

    public function actionNewclient() {
        Client::newClient(Yii::$app->request->post());
        return $this->redirect(['/site/clients']);
    }

    public function actionEditclient() {
        Client::editClient(Yii::$app->request->post());
        return $this->redirect(['/site/clients', 'clientId' => Yii::$app->request->post('clientId')]);
    }

    public function actionPvvente() {
        $sale = Sale::find()
            ->where(['id' => Yii::$app->request->post('saleId')])
            ->one();
        $sale->getSalesStep();
        $pdf = new FPDF();
        $pdf->SetTitle("PV " . $sale->date);
        $templateProcessor = new TemplateProcessor("../assets/modelpvvente.docx");
        $templateProcessor->setValue('DATE', gmdate('d/m/Y', $sale->date));
        $templateProcessor->setValue('AMOUNT', Yii::$app->request->post('fees'));

        $totalPrice = 0;
        $values = [];
        foreach ($sale->saleSteps as $saleStep) {
            $values[] = [
                'ITEMID' => $saleStep->id,
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

    public function actionAddsalestep() {
        return SaleStep::newSaleStep(Yii::$app->request->post());
    }

    public function actionEdititem() {
        $item = Item::findOne(['id' => Yii::$app->request->post('itemId')]);
        if ($item !== null) {
            $item->name = Yii::$app->request->post('name');
            $item->description = Yii::$app->request->post('description');
            $handle = fopen("../../frontend/web/images/items/$item->id", 'w') or die('Cannot open file'); //implicitly creates file
            $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', Yii::$app->request->post('picture')));
            fwrite($handle, $data);
            fclose($handle);
            $item->picture = "items/$item->id";
            $item->mandant_id = Yii::$app->request->post('mandantId');

            $date = DateTime::createFromFormat('Y-m-d H:i:s',
                Yii::$app->request->post('date_mandat') . " 00:00:00");
            $item->date_mandat = $date->getTimestamp();
            $item->update();
        }
        $this->redirect(['/site/item', 'itemId' => Yii::$app->request->post('itemId')]);
    }

    public function actionDeleteitem() {
        $item = Item::findOne(['id' => Yii::$app->request->post('itemId')]);
        if ($item !== null) {
            $item->delete();
        }
        $this->redirect(['/site/items']);
    }

    public function actionEditmandant() {
        $mandant = Mandant::findOne(['id' => Yii::$app->request->post('mandantId')]);
        if ($mandant !== null) {
            $mandant->firstname = Yii::$app->request->post('firstname');
            $mandant->name = Yii::$app->request->post('name');
            $mandant->address = Yii::$app->request->post('address');
            $mandant->postal = Yii::$app->request->post('postal');
            $mandant->phone = Yii::$app->request->post('phone');
            $mandant->city = Yii::$app->request->post('city');
            $mandant->mail = Yii::$app->request->post('mail');
            $mandant->update();
        }
        $this->redirect(['/site/mandant', 'mandantId' => Yii::$app->request->post('mandantId')]);
    }

    public function actionDeletemandant() {
        $mandant = Mandant::findOne(['id' => Yii::$app->request->post('deleteMandant')]);
        if ($mandant !== null) {
            $mandant->delete();
        }
        $this->redirect(['/site/mandants']);
    }

    public function actionDeleteclient() {
        $client = Client::findOne(['id' => Yii::$app->request->post('clientId')]);
        if ($client !== null) {
            $client->delete();
        }
        header("Location: " . Yii::$app->homeUrl . "?r=site/clients");
        exit;
    }

    public function actionGeneratefacture() {

        /* Note: any element you append to a document must reside inside of a Section. */

// Adding an empty Section to the document...
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

        $templateProcessor->setValue('DATE', gmdate('d/m/Y', $sale->date));

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

    public function actionClientexcel() {
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

    public function actionItemsexcel() {
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

    public function actionMandantsexcel() {
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

    public function actionFacturemandant() {
        $mandant = Mandant::findOne(['id' => Yii::$app->request->post('mandantId')]);
        $mandant->getItems();
        $templateProcessor = new TemplateProcessor("../assets/modelmandant.docx");

        $templateProcessor->setValue('USER_NAME', $mandant->name . ' ' . $mandant->firstname);
        $values = [];
        foreach ($mandant->items as $item) {
            $values[] = [
                'SALE_DATE' => $item->sale->date,
                'ITEM_NAME' => $item->name,
                'ITEM_ADJUDICATION' => $item->adjudication,
            ];
        }
        $templateProcessor->cloneRowAndSetValues('SALE_DATE', $values);

        header('Content-Disposition: attachment;filename="Mandant_' . $mandant->name . '_' . $mandant->firstname . '.docx"');
        $templateProcessor->saveAs('php://output');
        exit;

    }

    public function actionEditprofile() {
        $field = Yii::$app->request->post('field');
        $value = Yii::$app->request->post($field);
        $user = User::findOne(['id' => Yii::$app->user->id]);

        if ($field === 'logo' || $field === 'marianne') {
            $handle = fopen("../../frontend/web/images/users/" . Yii::$app->user->id . "_$field", 'w') or die('Cannot open file'); //implicitly creates file
            $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $value));
            fwrite($handle, $data);
            fclose($handle);
            $value = "users/" . Yii::$app->user->id . "_$field";
        }

        $user->$field = $value;
        $user->save();
        if (isset($_REQUEST["destination"])) {
            header("Location: {$_REQUEST["destination"]}");
        } else if (isset($_SERVER["HTTP_REFERER"])) {
            header("Location: {$_SERVER["HTTP_REFERER"]}");
        }
        exit;
    }

    public function actionDeletesale() {
        $saleId = Yii::$app->request->post('saleId');

        Sale::findOne(['id' => $saleId])->delete();

        header("Location: " . Yii::$app->homeUrl . "?r=site%2Fsales");
        exit;
    }

    public function actionEditsale() {
        $sale = Sale::findOne(['id' => Yii::$app->request->post('saleId')]);
        $date = DateTime::createFromFormat('Y-m-d H:i:s',
            Yii::$app->request->post('dateSale') . " 00:00:00");

        $sale->date = $date->getTimestamp();
        $sale->update();
        $this->redirect(['/site/sale', 'saleId' => Yii::$app->request->post('saleId')]);
    }

    public function actionEditsalestep() {
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
            return $this->redirect(['site/sale', 'saleId' => $step->sale_id]);
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
            return $this->redirect(['site/sale', 'saleId' => $saleId]);

        }
    }
}
