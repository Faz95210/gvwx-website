<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Signup';
$this->params['breadcrumbs'][] = $this->title;
?>


<!-- Begin page -->
<div class="accountbg"></div>
<div class="wrapper-page account-page-full">

    <div class="card">
        <div class="card-body">

            <h3 class="text-center">
                <a href="#" class="logo"><img src="images/ikarBlack.png" height="60" alt="logo"></a>
            </h3>

            <div class="p-3">
                <h4 class="font-18 m-b-5 text-center"><?php echo \Yii::t('login', 'Inscription'); ?></h4>
                <p class="text-muted text-center"><?php echo \Yii::t('login', 'Inscrivez-vous à IKAR maintenant !'); ?></p>

                <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                <?= $form->field($model, 'givenname')->textInput(['autofocus' => true])->label(\Yii::t('login', 'Prénom')) ?>

                <?= $form->field($model, 'name')->label(\Yii::t('login', 'Nom')) ?>

                <?= $form->field($model, 'email')->label(\Yii::t('login', 'Email')) ?>

                <?= $form->field($model, 'password')->passwordInput()->label(\Yii::t('login', 'Mot de passe')) ?>

                <div class="form-group">
                    <?= Html::submitButton(\Yii::t('login', 'Valider'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>
                <!--

                                        <form class="form-horizontal m-t-30" action="index">

                                            <div class="form-group">
                                                <label for="useremail">Email</label>
                                                <input type="email" class="form-control" id="useremail" placeholder="Enter email">
                                            </div>

                                            <div class="form-group">
                                                <label for="username">Username</label>
                                                <input type="text" class="form-control" id="username" placeholder="Enter username">
                                            </div>

                                            <div class="form-group">
                                                <label for="userpassword">Password</label>
                                                <input type="password" class="form-control" id="userpassword" placeholder="Enter password">
                                            </div>

                                            <div class="form-group row m-t-20">
                                                <div class="col-12 text-right">
                                                    <button class="btn btn-primary w-md waves-effect waves-light" type="submit">Register</button>
                                                </div>
                                            </div>
                -->
                <div class="form-group m-t-10 mb-0 row">
                    <div class="col-12 m-t-20">
                        <p class="mb-0"><?php echo \Yii::t('login', 'En vous inscrivant à IKAR, vous acceptez les '); ?> <?= Html::a(\Yii::t('login', 'conditions générales'), ['site/termsofuse']) ?></p>
                    </div>
                </div>
                <!-- </form> -->
            </div>

        </div>
    </div>

    <div class="m-t-40 text-center">
        <p><?php echo \Yii::t('login', 'Vous avez déjà un compte ?'); ?> <?= Html::a(\Yii::t('login', 'Connexion'), ['site/login']) ?></p>
    </div>

</div>
<!-- end wrapper-page -->
