<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Reset password';
$this->params['breadcrumbs'][] = $this->title;
?>


<!-- Begin page -->
<div class="accountbg"></div>
<div class="wrapper-page account-page-full">

    <div class="card">
        <div class="card-body">

            <div class="text-center">
                <a href="#" class="logo"><img src="images/ikarBlack.png" height="60" alt="logo"></a>
            </div>

            <div class="p-3">
                <h4 class="font-18 m-b-5 text-center"><?php echo \Yii::t('login', 'Renouvellement de mot de passe'); ?></h4>
                <div class="alert alert-success m-t-30" role="alert">
                    <?php echo \Yii::t('login', 'Choisissez un nouveau mot de passe'); ?>
                </div>

                <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

                <?= $form->field($model, 'password')->passwordInput(['autofocus' => true])->label(\Yii::t('login', 'Mot de passe')) ?>

                <div class="form-group">
                    <?= Html::submitButton(\Yii::t('login', 'Enregistrer'), ['class' => 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>

        </div>
    </div>

    <div class="m-t-40 text-center">
        <p><?php echo \Yii::t('login', 'Vous vous en souvenez ?'); ?> <?= Html::a(\Yii::t('login', 'Connexion'), ['site/login']) ?> </p>
    </div>
</div>