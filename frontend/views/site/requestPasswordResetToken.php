<?php

$this->registerCssFile("@web/css/veltrix/chartist/css/chartist.min.css");
$this->registerJsFile("@web/js/veltrix/chartist/js/chartist.min.js", ['depends' => 'app\assets\VeltrixAsset']);
$this->registerJsFile("@web/js/veltrix/chartist/js/chartist-plugin-tooltip.min.js", ['depends' => 'app\assets\VeltrixAsset']);
$this->registerJsFile("@web/js/veltrix/pages/dashboard.js", ['depends' => 'app\assets\VeltrixAsset']);
$this->registerJsFile("@web/js/veltrix/plugins/sweet-alert2/sweetalert2.js", ['depends' => 'app\assets\VeltrixAsset']);
$this->registerCssFile("@web/js/veltrix/plugins/sweet-alert2/sweetalert2.css", ['depends' => 'app\assets\VeltrixAsset']);


use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>


<!-- Begin page -->

<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-sm-12">
            <h4 class="page-title"><?= \Yii::t('frontend', 'Changement de mot de passe'); ?></h4>
            <p></p>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">


        <div class="p-3">
            <div class="alert alert-success m-t-30" role="alert">
                <?php echo \Yii::t('login', 'Entrez votre nouveau mot de passe'); ?>
            </div>

            <?php $form = ActiveForm::begin(['action' => ['site/changepasswordself']]); ?>

            <div class="fom-group row">
                <label class="col-sm-2 col-form-label">Ancien mot de passe</label>
                <input type="password" name="oldpwd">
            </div>
            <div class="fom-group row">
                <label class="col-sm-2 col-form-label">Nouveau mot de passe</label>
                <input type="password" name="newpwd">
            </div>
            <div class="fom-group row">
                <label class="col-sm-2 col-form-label">Confirmer mot de passe</label>
                <input type="password" name="confirmpwd">
            </div>
            <!--                --><? //= $form->field($model, 'email')->textInput(['autofocus' => true])->label(\Yii::t('login', 'Email')) ?>
            <!--                --><? //= $form->field($model, 'password')->passwordInput()->label(\Yii::t('login', 'Mot de passe')) ?>
            <!--                --><? //= $form->field($model, 'password')->passwordInput()->label(\Yii::t('login', 'Mot de passe')) ?>
            <!--                --><? //= $form->field($model, 'confirmationPassword')->passwordInput()->label(\Yii::t('login', 'Confirmez votre mot de passe')) ?>

            <div class="form-group">
                <?= Html::submitButton(\Yii::t('login', 'Envoyer'), ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>

    </div>
</div>

</div>
