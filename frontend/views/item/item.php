<?php
$this->registerCssFile("@web/css/veltrix/chartist/css/chartist.min.css");
$this->registerJsFile("@web/js/veltrix/chartist/js/chartist.min.js", ['depends' => 'app\assets\VeltrixAsset']);
$this->registerJsFile("@web/js/veltrix/chartist/js/chartist-plugin-tooltip.min.js", ['depends' => 'app\assets\VeltrixAsset']);
$this->registerJsFile("@web/js/veltrix/pages/dashboard.js", ['depends' => 'app\assets\VeltrixAsset']);
$this->registerJsFile("@web/js/qrcodejs/qrcode.js");
$this->registerJsFile("@web/js/veltrix/plugins/sweet-alert2/sweetalert2.js", ['depends' => 'app\assets\VeltrixAsset']);
$this->registerCssFile("@web/js/veltrix/plugins/sweet-alert2/sweetalert2.css", ['depends' => 'app\assets\VeltrixAsset']);

use common\widgets\DatePickerWidget\DatePickerWidget;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

?>

    <div class="page-title-box">
        <div class="row align-items-center">
            <div class="col-sm-12">
                <h4 class="page-title"><?= \Yii::t('frontend', 'Item'); ?></h4>
                <p></p>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <?php ActiveForm::begin(['action' => ['item/edit'], 'id' => 'editItem']) ?>
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
                <label class="col-sm-2 col-form-label">Estimation 2 :</label>
                <input class="col-sm-9 col-form-label form-control" name="estimation2" type="number"
                       value="<?= $this->params['item']->estimation2 ?>">
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Photo :</label>
                <?= Html::img('images/' . $this->params['item']->picture, ['id' => 'itemPreview', 'width' => '50px', 'height' => '50px']); ?>
                <input type="hidden" id="iteminput" value='<?= $this->params['item']->picture ?>' name='picture'/>
                <input onchange="encodeImageFileAsURL(this)" type="file" class="filestyle" data-input="false"
                       data-buttonname="btn-secondary" id="filestyle-1"
                       tabindex="-1" style="position: absolute; clip: rect(0px, 0px, 0px, 0px);">
                <div class="">
                            <span class="group-span-filestyle " tabindex="0">
                                <label for="filestyle-1" class="btn btn-secondary ">
                                    <span class="icon-span-filestyle fas fa-folder-open"></span>
                                    <span class="buttonText">
                                        Choisir
                                    </span>
                                </label>
                            </span>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">QR :</label>
                <div class="col-sm-9 col-form-label" id="qrcode"></div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Mandant :</label>
                <select name="mandantId" class="col-sm-9 col-form-label form-control">
                    <option value="-1"></option>
                    <?php foreach ($this->params['mandants'] as $mandant) { ?>
                        <option <?= $this->params['item']->mandant_id === $mandant->id ? 'selected' : '' ?>
                                value="<?= $mandant->id ?>"><?= $mandant->name . ' ' . $mandant->firstname ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="mandat-input">Date Mandat</label>
                <div class="col-sm-9">
                    <?= DatePickerWidget::widget([
                        'name' => 'date_mandat',
                        'value' => $this->params['item']->date_mandat,
                        'template' => '{addon}{input}',
                        'clientOptions' => [
                            'autoclose' => true,
                            'format' => 'dd/mm/yyyy'
                        ]
                    ]); ?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Vente :</label>
                <p class="col-sm-9 col-form-label"><?= $this->params['item']->sale->date ?></p>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Client :</label>
                <select disabled name="clientId" class="col-sm-9 col-form-label form-control">
                    <option>-</option>
                    <?php foreach ($this->params['clients'] as $client) { ?>
                        <option <?= $this->params['item']->client->id === $client->id ? 'selected' : '' ?>
                                value="<?= $client->id ?>"><?= $client->name . ' ' . $client->firstname ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Adjudication :</label>
                <p disabled class="col-sm-9 col-form-label" name="adjudication"
                   type="number"><?= $this->params['item']->adjudication ?></p>
            </div>
            <input type="hidden" name="itemId" value="<?= $this->params['item']->id ?>">
            <?php ActiveForm::end() ?>

            <div class="row">
                <div>
                    <?= Html::submitButton(\Yii::t('login', 'Modifier'), ['form' => 'editItem', 'class' => 'btn btn-primary col-sm-offset-2', 'name' => 'itemId', 'value' => $this->params['item']->id]) ?>
                </div>
                <div>
                    <?php
                    if ($this->params['item']->sale === null) {
                        ?>
                        <?php ActiveForm::begin(['id' => 'deleteItem', 'action' => ['item/delete']]) ?>
                        <?= Html::submitButton(\Yii::t('login', 'Supprimer'), ['form' => 'deleteItem', 'class' => 'btn btn-primary col-sm-offset-2', 'name' => 'itemId', 'value' => $this->params['item']->id]) ?>
                        <?php ActiveForm::end() ?>
                    <?php } else { ?>
                        <button class="btn btn-primary col-sm-offset-2" onclick="cantDelete()" type="button">Supprimer
                        </button>
                    <?php } ?>
                </div>
            </div>
        </div>

    </div>
<?php

$script = <<<JS

    function encodeImageFileAsURL(element) {
        const file = element.files[0];
        const reader = new FileReader();
        reader.onloadend = function () {
            document.getElementById('itemPreview').src = reader.result;
            document.getElementById('iteminput').value = reader.result;
        };
        reader.readAsDataURL(file);
    }

    new QRCode(document.getElementById("qrcode"), {
     "text":   document.URL,
     "width":128,
     "height":128,
    });

    function cantDelete(){
        Swal.fire(
          'Erreur',
          ' Cet item a été adjugé - Suppression impossible. Vous devez supprimer l’acquisition depuis l’onglet « Ventes »',
        );
    }

JS;

$this->registerJs($script, View::POS_END);
