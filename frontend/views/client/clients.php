<?php
$this->registerCssFile("@web/css/veltrix/chartist/css/chartist.min.css");
$this->registerJsFile("@web/js/veltrix/chartist/js/chartist.min.js", ['depends' => 'app\assets\VeltrixAsset']);
$this->registerJsFile("@web/js/veltrix/chartist/js/chartist-plugin-tooltip.min.js", ['depends' => 'app\assets\VeltrixAsset']);
$this->registerJsFile("@web/js/veltrix/pages/dashboard.js", ['depends' => 'app\assets\VeltrixAsset']);

use common\widgets\DatePickerWidget\DatePickerWidget;
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
        <?php ActiveForm::begin(['action' => ['client/excel'], 'id' => 'generateFacture']) ?>
        <?= Html::submitButton(\Yii::t('login', 'Telecharger'), ['class' => 'btn btn-primary', 'name' => 'clientId', 'value' => $this->params['client']->id]) ?>
        <?php ActiveForm::end() ?>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <h3 class="subTitle"> Ajouter un Nouveau Client</h3>

        <?php ActiveForm::begin(['action' => ['client/new'], 'id' => 'addNewClient']) ?>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Nom</label>
            <input required name="name" class="col-sm-9 col-form-label form-control" type="text" placeholder="">
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Prenom</label>
            <input required name="firstname" class="col-sm-9 col-form-label form-control" type="text" placeholder="">
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Adresse</label>
            <input name="address" class="col-sm-9 col-form-label form-control" type="text" placeholder="">
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Code Postal</label>
            <input name="postal" class="col-sm-9 col-form-label form-control" type="text" placeholder="">
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Telephone</label>
            <input name="phone" class="col-sm-9 col-form-label form-control" type="text" placeholder="">
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Ville</label>
            <input name="city" class="col-sm-9 col-form-label form-control" type="text" placeholder="">
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Mail</label>
            <input name="mail" class="col-sm-9 col-form-label form-control" type="text" placeholder="">
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Date de naissance</label>
            <div class="col-sm-2 col-form-label">
                <?= DatePickerWidget::widget([
                    'name' => 'birthdate',
                    'class' => 'col-sm-9 col-form-label form-control',
                    'value' => $this->params['mandant']->birthdate,
                    'template' => '{addon}{input}',
                    'clientOptions' => [
                        'autoclose' => true,
                        'format' => 'dd/mm/yyyy'
                    ]
                ]); ?>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Lieu de naissance</label>
            <div class="col-sm-2 col-form-label">
                <input name="birthplace" class="input" type="text" placeholder="">
            </div>
        </div>
        <?= Html::submitButton(\Yii::t('login', 'Ajouter'), ['class' => 'btn btn-primary', 'name' => 'new-client-button']) ?>
        <?php ActiveForm::end() ?>
    </div>
</div>
<script>
    function toClient(clientId) {
        window.location.href = '<?=Yii::$app->urlManager->createAbsoluteUrl(['client/get']);?>' + '&clientId=' + clientId;
    }
</script>