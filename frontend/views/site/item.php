<?php
$this->registerCssFile("@web/css/veltrix/chartist/css/chartist.min.css");
$this->registerJsFile("@web/js/veltrix/chartist/js/chartist.min.js", ['depends' => 'app\assets\VeltrixAsset']);
$this->registerJsFile("@web/js/veltrix/chartist/js/chartist-plugin-tooltip.min.js", ['depends' => 'app\assets\VeltrixAsset']);
$this->registerJsFile("@web/js/veltrix/pages/dashboard.js", ['depends' => 'app\assets\VeltrixAsset']);
$this->registerJsFile("@web/js/qrcodejs/qrcode.js");

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

?>

    <div class="card">
        <div class="card-body">

            <?php ActiveForm::begin(['action' => ['site/edititem']]) ?>
            <input type="hidden" id="itemId" value="<?= $this->params['item']->id ?>">
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Nom :</label>
                <input class="col-sm-9 col-form-label form-control" name="name" type="text"
                       value="<?= $this->params['item']->name ?>">
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Description :</label>
                <input class="col-sm-9 col-form-label form-control" name="description" type="text"
                       value="<?= $this->params['item']->description ?>">
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Estimation :</label>
                <input class="col-sm-9 col-form-label form-control" name="estimation" type="number"
                       value="<?= $this->params['item']->estimation ?>">

            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Photo</label>
                <img class="" name="picture" width="50px" height="50px"
                     src="<?= $this->params['item']->picture ?>">
                <input type="hidden" value='<?= $this->params['item']->picture ?>' name='picture'/>

            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">QR</label>
                <div class="col-sm-9 col-form-label" id="qrcode"></div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Mandant
                </label>
                <select name="mandantId" class="col-sm-9 col-form-label form-control">
                    <?php foreach ($this->params['mandants'] as $mandant) { ?>
                        <option <?= $this->params['item']->mandant_id === $mandant->id ? 'selected' : '' ?>
                                value="<?= $mandant->id ?>"><?= $mandant->name . ' ' . $mandant->firstname ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Vente
                </label>
                <input class="col-sm-9 col-form-label" name="date" type="date"
                       value="<?= $this->params['item']->sale != null ? gmdate('d/m/Y', $this->params['item']->sale->date) : '' ?>">
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Client
                </label>
                <select name="clientId" class="col-sm-9 col-form-label form-control">
                    <?php foreach ($this->params['clients'] as $client) { ?>
                        <option <?= $this->params['item']->client->id === $client->id ? 'selected' : '' ?>
                                value="<?= $client->id ?>"><?= $client->name . ' ' . $client->firstname ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Adjudication
                </label>
                <input class="col-sm-9 col-form-label" name="adjudication" type="number"
                       value="<?= $this->params['item']->adjudication ?>">
            </div>
            <?= Html::submitButton(\Yii::t('login', 'Modifier'), ['class' => 'btn btn-primary', 'name' => 'itemId', 'value' => $this->params['item']->id]) ?>
            <?php ActiveForm::end() ?>

            <?php ActiveForm::begin(['action' => ['site/deleteitem']]) ?>
            <?= Html::submitButton(\Yii::t('login', 'Supprimer'), ['class' => 'btn btn-primary', 'name' => 'itemId', 'value' => $this->params['item']->id]) ?>
            <?php ActiveForm::end() ?>
        </div>
    </div>
<?php
$script = <<<JS
    new QRCode(document.getElementById("qrcode"), {
     "text":   document.URL,
     "width":128,
     "height":128,
    });
JS;

$this->registerJs($script, View::POS_END);
