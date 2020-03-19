<?php
$this->registerCssFile("@web/css/veltrix/chartist/css/chartist.min.css");
$this->registerJsFile("@web/js/veltrix/chartist/js/chartist.min.js", ['depends' => 'app\assets\VeltrixAsset']);
$this->registerJsFile("@web/js/veltrix/chartist/js/chartist-plugin-tooltip.min.js", ['depends' => 'app\assets\VeltrixAsset']);
$this->registerJsFile("@web/js/veltrix/pages/dashboard.js", ['depends' => 'app\assets\VeltrixAsset']);

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

?>
<?php ActiveForm::begin(['action' => ['site/editmandant']]) ?>
<div class="card">
    <div class="card-body">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Nom</label>
            <input name="name" class="col-sm-9 col-form-label form-control" type="text"
                   value="<?= $this->params['mandant']->name ?>"
                   placeholder="Text input">
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Prenom</label>
            <input name="firstname" value="<?= $this->params['mandant']->firstname ?>"
                   class="col-sm-9 col-form-label form-control" type="text"
                   placeholder="Text input">
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Adresse</label>
            <input name="address" value="<?= $this->params['mandant']->address ?>"
                   class="col-sm-9 col-form-label form-control" type="text"
                   placeholder="Text input">
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Code Postal</label>
            <input name="postal" value="<?= $this->params['mandant']->postal ?>"
                   class="col-sm-9 col-form-label form-control" type="text"
                   placeholder="Text input">
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Phone</label>
            <input name="phone" value="<?= $this->params['mandant']->phone ?>"
                   class="col-sm-9 col-form-label form-control" type="text"
                   placeholder="Text input">
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">City</label>
            <input name="city" value="<?= $this->params['mandant']->city ?>"
                   class="col-sm-9 col-form-label form-control" type="text"
                   placeholder="Text input">
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Mail</label>
            <input name="mail" value="<?= $this->params['mandant']->mail ?>"
                   class="col-sm-9 col-form-label form-control" type="text"
                   placeholder="Text input">
        </div>
        <div class="row">
            <?php ActiveForm::begin(['action' => ['site/editmandant']]) ?>
            <?= Html::submitButton(\Yii::t('login', 'Modifier'), ['class' => 'btn btn-primary col-sm-offset-2', 'name' => 'mandantId', 'value' => $this->params['mandant']->id]) ?>
            <?php ActiveForm::end() ?>

            <?php ActiveForm::begin(['action' => ['site/deletemandant']]) ?>
            <?= Html::submitButton(\Yii::t('login', 'Supprimer'), ['class' => 'btn btn-primary col-sm-offset-2', 'name' => 'mandantId', 'value' => $this->params['mandant']->id]) ?>
            <?php ActiveForm::end() ?>
        </div>

    </div>
</div>
<div class="card">
    <div class="card-body">

        <h2 class="card-title">Liste des objets mandatés :</h2>
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
                <?php foreach ($this->params['mandant']->items as $item) { ?>
                    <tr>
                        <td> <?= gmdate('d/m/Y', $item->sale->date) ?> </td>
                        <td> <?= $item->name ?>"</td>
                        <td> <?= $item->adjudication ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>

            <?php ActiveForm::begin(['action' => ['site/facturemandant']]) ?>
            <?= Html::submitButton(\Yii::t('login', 'Facture'), ['class' => 'btn btn-primary', 'name' => 'mandantId', 'value' => $this->params['mandant']->id]) ?>
            <?php ActiveForm::end() ?>
        </div>
    </div>
</div>
