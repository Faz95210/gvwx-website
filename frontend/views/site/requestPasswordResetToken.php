<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */


use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Request password reset';
$this->params['breadcrumbs'][] = $this->title;
?>


<!-- Begin page -->
<div class="accountbg"></div>
<div class="wrapper-page account-page-full">

    <div class="card">
        <div class="card-body">

            <div class="text-center">
                <a href="#" class="logo"><img src="images/auction.png" height="60" alt="logo"></a>
            </div>

            <div class="p-3">
                <h4 class="font-18 m-b-5 text-center"><?php echo \Yii::t('login', 'Renouvellement de mot de passe'); ?></h4>
                <div class="alert alert-success m-t-30" role="alert">
                    <?php echo \Yii::t('login', 'Entrez votre email et votre nouveau mot de passe'); ?>
                </div>

                <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>

                <?= $form->field($model, 'email')->textInput(['autofocus' => true])->label(\Yii::t('login', 'Email')) ?>
                <?= $form->field($model, 'password')->passwordInput()->label(\Yii::t('login', 'Mot de passe')) ?>
                <?= $form->field($model, 'confirmationPassword')->passwordInput()->label(\Yii::t('login', 'Confirmez votre mot de passe')) ?>

                <div class="form-group">
                    <?= Html::submitButton(\Yii::t('login', 'Envoyer'), ['class' => 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>

        </div>
    </div>

    <div class="m-t-40 text-center">
        <p><?php echo \Yii::t('login', 'Vous vous en souvenez ?'); ?> <?= Html::a(\Yii::t('login', 'Connexion'), ['site/login']) ?> </p>
    </div>
</div>
