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
<div id="container" class="container">
    <div class="field">
        <label>Nom</label>
        <div class="control">
            <input name="name" class="input" type="text" value="<?= $this->params['mandant']->name ?>"
                   placeholder="Text input">
        </div>
    </div>
    <div class="field">
        <label>Prenom</label>
        <div class="control">
            <input name="firstname" value="<?= $this->params['mandant']->firstname ?>" class="input" type="text"
                   placeholder="Text input">
        </div>
    </div>
    <div class="field">
        <label>Adresse</label>
        <div class="control">
            <input name="address" value="<?= $this->params['mandant']->address ?>" class="input" type="text"
                   placeholder="Text input">
        </div>
    </div>
    <div class="field">
        <label>Code Postal</label>
        <div class="control">
            <input name="postal" value="<?= $this->params['mandant']->postal ?>" class="input" type="text"
                   placeholder="Text input">
        </div>
    </div>
    <div class="field">
        <label>Phone</label>
        <div class="control">
            <input name="phone" value="<?= $this->params['mandant']->phone ?>" class="input" type="text"
                   placeholder="Text input">
        </div>
    </div>
    <div class="field">
        <label>City</label>
        <div class="control">
            <input name="city" value="<?= $this->params['mandant']->city ?>" class="input" type="text"
                   placeholder="Text input">
        </div>
    </div>
    <div class="field">
        <label>Mail</label>
        <div class="control">
            <input name="mail" value="<?= $this->params['mandant']->mail ?>" class="input" type="text"
                   placeholder="Text input">
        </div>
    </div>
</div>
<?= Html::submitButton(\Yii::t('login', 'Modifier'), ['class' => 'btn btn-primary', 'name' => 'mandantId', 'value' => $this->params['mandant']->id]) ?>
<?php ActiveForm::end() ?>
<?php ActiveForm::begin(['action' => ['site/deletemandant']]) ?>
<?= Html::submitButton(\Yii::t('login', 'Supprimer'), ['class' => 'btn btn-primary', 'name' => 'mandantId', 'value' => $this->params['mandant']->id]) ?>
<?php ActiveForm::end() ?>

<h2>Liste des objets mandatés :</h2>
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
                <td> <?= $item->sale->date ?> </td>
                <td> <?= $item->name ?>"</td>
                <td> <?= $item->adjudication ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    <?php ActiveForm::begin(['action' => ['site/facturemandant']]) ?>
    <?= Html::submitButton(\Yii::t('login', 'Facture'), ['class' => 'btn btn-primary', 'name' => 'mandantId', 'value' => $this->params['mandant']->id]) ?>
    <?php ActiveForm::end() ?></div>
