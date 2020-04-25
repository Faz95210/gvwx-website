<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>


<!--
<div class="home-btn d-none d-sm-block">
    <a href="index" class="text-white"><i class="fas fa-home h2"></i></a>
</div>
-->

<!-- Begin page -->
<div class="accountbg"></div>

<div class="wrapper-page account-page-full">

    <div class="card">
        <div class="card-body">

            <div class="text-center">
                <a href="#" class="logo"><img src="images/auction.png" height="60" alt="logo"></a>
            </div>

            <div class="p-3">
                <h4 class="font-18 m-b-5 text-center"><?php echo \Yii::t('login', 'Bienvenue'); ?></h4>
                <p class="text-muted text-center"><?php echo \Yii::t('login', 'Connectez-vous pour continuer sur AuctionManager'); ?></p>

                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'email')->textInput(['autofocus' => true])->label(\Yii::t('login', 'Email')) ?>

                <?= $form->field($model, 'password')->passwordInput()->label(\Yii::t('login', 'Mot de passe')) ?>

                <?= $form->field($model, 'rememberMe')->checkbox()->label(\Yii::t('login', 'Se souvenir de moi')) ?>

                <div style="color:#999;margin:1em 0">
                    <?= Html::a(\Yii::t('login', 'Mot de passe oublié ?'), [], ['onclick' => 'window.location.href = "mailto:​gvxconseil@gmail.com?subject=Changement de mot de passe&body=Bonjour je souhaiterai changer mon mot de passe"']) ?>
                    <br>
                    Veuillez contacter votre administrateur.
                </div>

                <div class="form-group">
                    <?= Html::submitButton(\Yii::t('login', 'Connexion'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>
                <!--
                                <form class="form-horizontal m-t-30" action="index">

                                    <div class="form-group">
                                        <label for="username">Username</label>
                                        <input type="text" class="form-control" id="username" placeholder="Enter username">
                                    </div>

                                    <div class="form-group">
                                        <label for="userpassword">Password</label>
                                        <input type="password" class="form-control" id="userpassword" placeholder="Enter password">
                                    </div>

                                    <div class="form-group row m-t-20">
                                        <div class="col-sm-6">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="customControlInline">
                                                <label class="custom-control-label" for="customControlInline">Remember me</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 text-right">
                                            <button class="btn btn-primary w-md waves-effect waves-light" type="submit">Log In</button>
                                        </div>
                                    </div>

                                    <div class="form-group m-t-10 mb-0 row">
                                        <div class="col-12 m-t-20">
                                            <a href="pages-recoverpw-2"><i class="mdi mdi-lock"></i> Forgot your password?</a>
                                        </div>
                                    </div>
                                </form> -->
            </div>

        </div>
    </div>

    <div class="m-t-40 text-center">
        <p><?= \Yii::t('login', "Vous n'avez pas de compte ?"); ?> <?= Html::a(\Yii::t('login', 'Inscrivez-vous'), ['site/signup']) ?> </p>
    </div>

</div>
