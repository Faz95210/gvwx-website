<?php
$this->registerCssFile("@web/css/veltrix/chartist/css/chartist.min.css");
$this->registerJsFile("@web/js/veltrix/chartist/js/chartist.min.js", ['depends' => 'app\assets\VeltrixAsset']);
$this->registerJsFile("@web/js/veltrix/chartist/js/chartist-plugin-tooltip.min.js", ['depends' => 'app\assets\VeltrixAsset']);
$this->registerJsFile("@web/js/veltrix/pages/dashboard.js", ['depends' => 'app\assets\VeltrixAsset']);

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

?>
<?php ActiveForm::begin(['action' => ['site/editclient'], 'id' => 'editClient']) ?>
<div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Nom</label>
        <div class="col-sm-10">
            <input name="name" class="input" type="text" value="<?= $this->params['client']->name ?>"
                   placeholder="Text input">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Prenom</label>
        <div class="col-sm-10">
            <input name="firstname" value="<?= $this->params['client']->firstname ?>" class="input" type="text"
                   placeholder="Text input">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Adresse</label>
        <div class="col-sm-10">
            <input name="address" value="<?= $this->params['client']->address ?>" class="input" type="text"
                   placeholder="Text input">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Code Postal</label>
        <div class="col-sm-10">
            <input name="postal" value="<?= $this->params['client']->postal ?>" class="input" type="text"
                   placeholder="Text input">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Phone</label>
        <div class="col-sm-10">
            <input name="phone" value="<?= $this->params['client']->phone ?>" class="input" type="text"
                   placeholder="Text input">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">City</label>
        <div class="col-sm-10">
            <input name="city" value="<?= $this->params['client']->city ?>" class="input" type="text"
                   placeholder="Text input">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Mail</label>
        <div class="col-sm-10">
            <input name="mail" value="<?= $this->params['client']->mail ?>" class="input" type="text"
                   placeholder="Text input">
        </div>
    </div>
    <input type="hidden" name="clientId" value="<?= $this->params['client']->id ?>">
    <?= Html::submitButton(\Yii::t('login', 'Modifier'), ['class' => 'btn btn-primary', 'name' => 'edit-client-button']) ?>
    <?php ActiveForm::end() ?>
    <?php ActiveForm::begin(['action' => ['site/deleteclient'], 'id' => 'editClient']) ?>
    <?= Html::submitButton(\Yii::t('login', 'Supprimer'), ['class' => 'btn btn-primary', 'name' => 'clientId', 'value' => $this->params['client']->id]) ?>
    <?php ActiveForm::end() ?>

</div>
<h2>Liste des acquisitions</h2>
<div class="table-container">
    <table class="table is-bordered is-hoverable is-fullwidth">
        <thead>
        <tr>
            <th>Date :</th>
            <th>Nom :</th>
            <th>Montant :</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($this->params['client']->sales as $sale) { ?>
            <?php foreach ($sale->saleSteps as $saleStep) { ?>
                <tr>
                    <td> <?= gmdate('d/m/Y', $sale->date) ?></td>
                    <td> <?= $saleStep->item->name ?></td>
                    <td> <?= $saleStep->item->adjudication ?> </td>
                </tr>
            <?php } ?>
        <?php } ?>
        </tbody>
    </table>
    <?php ActiveForm::begin(['action' => ['site/generatefacture'], 'id' => 'generateFacture']) ?>
    <?= Html::submitButton(\Yii::t('login', 'Facture'), ['class' => 'btn btn-primary', 'name' => 'clientId', 'value' => $this->params['client']->id]) ?>
    <?php ActiveForm::end() ?>
</div>
</div>
