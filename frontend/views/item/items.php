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
            <h4 class="page-title"><?= \Yii::t('frontend', 'Items'); ?></h4>
            <p></p>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">

        <!--        <input class="input is-focused" placeholder="rechercher" type="text" name="search"/>-->
        <div class="table-responsive-lg">
            <table class="table table-bordered table-hoverable">
                <thead class="thead-default">
                <th>Nom</th>
                </thead>
                <tbody>
                <?php foreach ($this->params['items'] as $item) {
                    ?>
                    <tr onclick="toItem('<?= $item->id ?>')">
                        <td><?= $item->name ?></td>
                    </tr>
                    <?
                } ?>
                </tbody>
            </table>
        </div>
        <?php ActiveForm::begin(['action' => ['item/excel'], 'id' => 'generateFacture']) ?>
        <?= Html::submitButton(\Yii::t('login', 'Telecharger'), ['class' => 'btn btn-primary', 'name' => 'clientId', 'value' => $this->params['client']->id]) ?>
        <?php ActiveForm::end() ?>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h3 class="subTitle"> Ajouter un item</h3>
        <?php ActiveForm::begin(['action' => ['item/new'], 'id' => 'addNewItem']) ?>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="name-input">Nom</label>
            <input class="col-sm-9 col-form-label form-control" required id="name-input" name="name" type="text"
                   placeholder="">
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="description-input">Description</label>
            <textarea maxlength="300" required id="description-input" name="description"
                      class="col-sm-9 col-form-label form-control" type="text"
                      placeholder="">
            </textarea>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="estimation-input">Estimation</label>
            <input id="estimation-input" required name="estimation" class="col-sm-9 col-form-label form-control"
                   type="number"
                   placeholder="">
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="estimation-input">Estimation 2</label>
            <input id="estimation-input" required name="estimation2" class="col-sm-9 col-form-label form-control"
                   type="number"
                   placeholder="">
        </div>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Mandant</label>
            <select name="mandant" required class="form-control col-sm-9">
                <option value="-1"></option>
                <?php foreach ($this->params['mandants'] as $mandant) { ?>
                    <option value="<?= $mandant->id ?>"><?= $mandant->name . " " . $mandant->firstname ?></option>
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

        <input type="hidden" id="base64" name="picture">
        <div class="form-group row">
            <label class="col-form-label col-sm-2">Photo</label>
            <div class="">
                <input onchange="encodeImageFileAsURL(this)" type="file" class="filestyle" data-input="false"
                       data-buttonname="btn-secondary" id="filestyle-1"
                       tabindex="-1" style="position: absolute; clip: rect(0px, 0px, 0px, 0px);">
                <div class="bootstrap-filestyle input-group">
                <span class="group-span-filestyle " tabindex="0">
                    <label for="filestyle-1" class="btn btn-secondary ">
                        <span class="icon-span-filestyle fas fa-folder-open"></span>
                        <span class="buttonText">
                            Photo
                        </span>
                    </label>
                </span>
                </div>
            </div>
            <div class="col-form-label col-sm-6">
                <img id="img-preview" class="d-flex align-self-center rounded mr-3" src="" alt="Preview" height="50"
                     width="50">
            </div>

        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="sale-input">Date Vente</label>
            <div class="col-sm-9">
                <?= DatePickerWidget::widget([
                    'name' => 'sale_date',
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
            <label class="col-sm-2 col-form-label" for="mandat-input">Client</label>
            <select name="client_id" class="form-control col-sm-9">
                <option value="-1>"></option>

                <?php foreach ($this->params['clients'] as $client) { ?>
                    <option value="<?= $client->id ?>"><?= $client->name . ' ' . $client->firstname ?></option>
                <? } ?>
            </select>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="lot-input">Lot nº</label>
            <input id="lot-input" name="lot_number" class="col-sm-9 col-form-label form-control" type="number"
                   placeholder="">
        </div>


        <img>
        <?= Html::submitButton(\Yii::t('login', 'Ajouter'), ['class' => 'btn btn-primary', 'name' => 'new-item-button']) ?>
        <?php ActiveForm::end() ?>
    </div>
</div>
<script>

    function downloadElement(e) {
        console
    }

    function toItem(itemId) {
        window.location.href = '<?=Yii::$app->urlManager->createAbsoluteUrl(['item/get']);?>' + '&itemId=' + itemId;
    }


    function encodeImageFileAsURL(element) {
        console.log(element);
        console.log(element.files);
        const file = element.files[0];
        const reader = new FileReader();
        reader.onloadend = function () {
            console.log('RESULT', reader.result);
            document.getElementById('base64').value = reader.result;
            document.getElementById('img-preview').src = reader.result;
            document.getElementById('img-preview').style.display = 'flex';
        };
        reader.readAsDataURL(file);
    }

    $(document).ready(function () {
        // bind 'myForm' and provide a simple callback function
        $('#addItem').ajaxForm(function () {
            alert("Thank you for your comment!");
        });
    });

</script>
