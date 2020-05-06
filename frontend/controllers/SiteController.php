<?php

namespace frontend\controllers;

use common\models\AuthAssignment;
use common\models\AuthItem;
use common\models\Client;
use common\models\Item;
use common\models\LoginForm;
use common\models\Mandant;
use common\models\Sale;
use common\models\SaleStep;
use common\models\User;
use console\controllers\RbacController;
use DateTime;
use Fpdf\Fpdf;
use frontend\models\PasswordResetForm;
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
                            'changepassword',
                            'editprofile',
                            'profile',
                            'changepasswordself'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ], [
                        'actions' => [
                            'admin',
                            'adduserrole',
                            'deleteuser',
                            'changelicensestatus',
                            'changelicensedate',
                        ],
                        'allow' => true,
                        'roles' => ['admin']
                    ]
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

    public function actionChangelicensestatus() {
        $user = User::findIdentity(Yii::$app->request->post('userId'));
        if ($user != null) {
            $user->license_paid = !$user->license_paid;
            $user->update();
        }
        $this->redirect(['site/admin']);
    }

    public function actionDeleteuser() {
        $user = User::findIdentity(Yii::$app->request->post('userId'));
        if ($user != null) {
            AuthAssignment::deleteAll(['user_id' => $user->id]);
            foreach (Mandant::findAll(['user_id' => $user->id]) as $mandant) {
                $mandant->delete();
            }
            foreach (Client::findAll(['user_id' => $user->id]) as $mandant) {
                $mandant->delete();
            }
            foreach (Sale::findAll(['user_id' => $user->id]) as $mandant) {
                $mandant->delete();
            }
            $user->delete();
        }
        exit;
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
        $this->view->params['user'] = User::findOne(['id' => Yii::$app->user->id]);
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

    public function actionChangepassword() {
        $admin = Yii::$app->user->can('admin');

        $user = User::findOne(['email' => Yii::$app->request->post("PasswordResetForm")['email']]);
        if ($user == null) {
            if ($admin) {
                $this->redirect(['site/admin']);
            } else {
                $this->redirect(['/site/profile']);
            }
            return -1;
        }
        if ($user->id != Yii::$app->user->id && !$admin) {
            $this->redirect(['/site/home']);
        }
        if (Yii::$app->request->post('PasswordResetForm')['password'] === Yii::$app->request->post('PasswordResetForm')['confirmationPassword']) {
            $user->setPassword(Yii::$app->request->post('PasswordResetForm')['password']);
            $user->save();
            if ($admin) {
                $this->redirect(['/site/admin']);
            } else {
                $this->redirect(['/site/profile']);
            }
        }

    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset() {
        $this->layout = "veltrix";

        return $this->render('requestPasswordResetToken');
    }

    public function actionChangelicensedate() {
        $user = User::findIdentity(Yii::$app->request->post('userId'));
        if ($user !== null) {
            $user->license_date = Yii::$app->request->post('date');
            $user->license_paid = 1;
            return $user->save();
        }
        return -1;
    }

    public function actionChangepasswordself() {
        $user = User::findIdentity(Yii::$app->user->id);
        if ($user != null) {
            if ($user->validatePassword(Yii::$app->request->post('oldpwd'))
                && Yii::$app->request->post('newpwd') === Yii::$app->request->post('confirmpwd')) {
                $user->setPassword(Yii::$app->request->post('newpwd'));
                $user->save();
                Yii::$app->session->setFlash('succes', 'Votre mot de passe a bien été changé');
            } else {
                if (!$user->validatePassword(Yii::$app->request->post('oldpwd'))) {
                    Yii::$app->session->setFlash('error', 'Mot de passe incorrect');
                }
                if (Yii::$app->request->post('newpwd') != Yii::$app->request->post('confirmpwd')) {
                    Yii::$app->session->setFlash('error', 'Les deux mots de passe sont different');
                }
            }
        }
        $this->redirect(['/site/profile']);
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


    public function actionAdduserrole() {
        $post = Yii::$app->request->post();
        foreach (User::find()->all() as $user) {
            RbacController::revokeUserRoles($user->id);
        }
        foreach (array_keys($post) as $id) {
            if (is_int($id)) {
                RbacController::addUserRole($id, 'admin');
            }
        }
        $this->redirect(['/site/admin']);
    }

    public function actionAdmin() {
        $users = User::find()->all();
        foreach ($users as $user) {
            $assignment = AuthAssignment::find()->where(['user_id' => $user->id])->one();
            if ($assignment == null) {
                $user->admin = false;
            } else {
                $user->admin = true;
            }
            $this->view->params['all_users'][] = $user;

        }
        return $this->render('admin');
    }
}

