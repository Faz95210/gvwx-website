<?php
$this->registerCssFile("@web/css/veltrix/chartist/css/chartist.min.css");
$this->registerJsFile("@web/js/veltrix/chartist/js/chartist.min.js", ['depends' => 'app\assets\VeltrixAsset']);
$this->registerJsFile("@web/js/veltrix/chartist/js/chartist-plugin-tooltip.min.js", ['depends' => 'app\assets\VeltrixAsset']);
$this->registerJsFile("@web/js/veltrix/pages/dashboard.js", ['depends' => 'app\assets\VeltrixAsset']);

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

?>

<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-sm-12">
            <h4 class="page-title"><?= \Yii::t('frontend', 'Clients'); ?></h4>
            <p></p>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <!--        <input class="input is-focused" placeholder="rechercher" type="text" name="search"/>-->
        <div class="table-container">
            <table class="table is-bordered is-hoverable is-fullwidth">
                <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prenom</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($this->params['clients'] as $client) { ?>
                    <tr onclick="toClient('<?= $client->id ?>')">
                        <td> <?= $client->name ?> </td>
                        <td> <?= $client->firstname ?> </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <?php ActiveForm::begin(['action' => ['site/clientexcel'], 'id' => 'generateFacture']) ?>
        <?= Html::submitButton(\Yii::t('login', 'Telecharger'), ['class' => 'btn btn-primary', 'name' => 'clientId', 'value' => $this->params['client']->id]) ?>
        <?php ActiveForm::end() ?>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <h3 class="subTitle"> Ajouter un Nouveau Client</h3>

        <?php ActiveForm::begin(['action' => ['site/newclient'], 'id' => 'addNewClient']) ?>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Nom</label>
            <div class="col-sm-2 col-form-label">
                <input name="name" class="input" type="text" placeholder="">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Prenom</label>
            <div class="col-sm-2 col-form-label">
                <input name="firstname" class="input" type="text" placeholder="">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Adresse</label>
            <div class="col-sm-2 col-form-label">
                <input name="address" class="input" type="text" placeholder="">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Code Postal</label>
            <div class="col-sm-2 col-form-label">
                <input name="postal" class="input" type="text" placeholder="">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Telephone</label>
            <div class="col-sm-2 col-form-label">
                <input name="phone" class="input" type="text" placeholder="">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Ville</label>
            <div class="col-sm-2 col-form-label">
                <input name="city" class="input" type="text" placeholder="">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Mail</label>
            <div class="col-sm-2 col-form-label">
                <input name="mail" class="input" type="text" placeholder="">
            </div>
        </div>
        <?= Html::submitButton(\Yii::t('login', 'Ajouter'), ['class' => 'btn btn-primary', 'name' => 'new-client-button']) ?>
        <?php ActiveForm::end() ?>
    </div>
</div>
<script>
    function toClient(clientId) {
        window.location.href = '<?=Yii::$app->urlManager->createAbsoluteUrl(['site/client']);?>' + '&clientId=' + clientId;
    }
</script>