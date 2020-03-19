<?php
$this->registerCssFile("@web/css/veltrix/chartist/css/chartist.min.css");
$this->registerJsFile("@web/js/veltrix/chartist/js/chartist.min.js", ['depends' => 'app\assets\VeltrixAsset']);
$this->registerJsFile("@web/js/veltrix/chartist/js/chartist-plugin-tooltip.min.js", ['depends' => 'app\assets\VeltrixAsset']);
$this->registerJsFile("@web/js/veltrix/pages/dashboard.js", ['depends' => 'app\assets\VeltrixAsset']);

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

?>
<div class="card">
    <div class="card-body">

        <input class="input is-focused" placeholder="rechercher" type="text" name="search"/>
        <div class="table-container">
            <table class="table is-bordered is-hoverable is-fullwidth">
                <thead>
                <th>Prenom</th>
                <th>Nom</th>
                <th></th>
                </thead>
                <tbody>
                <?php foreach ($this->params['mandants'] as $mandant) { ?>
                    <tr onclick="toMandant('<?= $mandant->id ?>')">
                        <td><?= $mandant->firstname ?></td>
                        <td><?= $mandant->name ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        <?php ActiveForm::begin(['action' => ['site/mandantsexcel'], 'id' => 'generateFacture']) ?>
        <?= Html::submitButton(\Yii::t('login', 'Telecharger'), ['class' => 'btn btn-primary', 'name' => 'clientId', 'value' => $this->params['client']->id]) ?>
        <?php ActiveForm::end() ?>
    </div>
</div>
<div class="card">
    <div class="card-body">

        <h3 class="card-title"> Ajouter un Nouveau Mandant</h3>
        <?php ActiveForm::begin(['action' => ['site/newmandant'], 'id' => 'addNewMandant']) ?>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Nom</label>
            <input name="name" class="col-sm-9 form-control" type="text" placeholder="Text input">
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Prenom</label>
            <input name="firstname" class="col-sm-9 form-control" type="text" placeholder="Text input">
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Adresse</label>
            <input name="address" class="col-sm-9 form-control" type="text" placeholder="Text input">
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Code Postal</label>
            <input name="postal" class="col-sm-9 form-control" type="text" placeholder="Text input">
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Phone</label>
            <input name="phone" class="col-sm-9 form-control" type="text" placeholder="Text input">
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">City</label>
            <input name="city" class="col-sm-9 form-control" type="text" placeholder="Text input">
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Mail</label>
            <input name="mail" class="col-sm-9 form-control" type="text" placeholder="Text input">
        </div>
    </div>
    <div class="card-footer">
        <?= Html::submitButton(\Yii::t('login', 'Ajouter'), ['class' => 'btn btn-primary', 'name' => 'new-mandant-button']) ?>
        <?php ActiveForm::end() ?>
    </div>
</div>

<script>
    function toMandant(mandantId) {
        window.location.href = '<?=Yii::$app->urlManager->createAbsoluteUrl(['site/mandant']);?>' + '&mandantId=' + mandantId;
    }
</script>