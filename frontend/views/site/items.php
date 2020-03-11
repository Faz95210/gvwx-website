<?php
$this->registerCssFile("@web/css/veltrix/chartist/css/chartist.min.css");
$this->registerJsFile("@web/js/veltrix/chartist/js/chartist.min.js", ['depends' => 'app\assets\VeltrixAsset']);
$this->registerJsFile("@web/js/veltrix/chartist/js/chartist-plugin-tooltip.min.js", ['depends' => 'app\assets\VeltrixAsset']);
$this->registerJsFile("@web/js/veltrix/pages/dashboard.js", ['depends' => 'app\assets\VeltrixAsset']);

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

?>

<div class="container">
    <input class="input is-focused" placeholder="rechercher" type="text" name="search"/>
    <div class="table-responsive-lg">
        <table class="table table-bordered table-hoverable">
            <thead class="thead-default">
            <th>id</th>
            <th>Nom</th>
            </thead>
            <tbody>
            <?php foreach ($this->params['items'] as $item) {
                ?>
                <tr onclick="toItem('<?= $item->id ?>')">
                    <td><?= $item->id ?></td>
                    <td><?= $item->name ?></td>
                </tr>

                <?
            } ?>
            </tbody>
        </table>
    </div>
    <?php ActiveForm::begin(['action' => ['site/itemsexcel'], 'id' => 'generateFacture']) ?>
    <?= Html::submitButton(\Yii::t('login', 'Telecharger'), ['class' => 'btn btn-primary', 'name' => 'clientId', 'value' => $this->params['client']->id]) ?>
    <?php ActiveForm::end() ?>   </div>
<hr>
<div class="container">
    <h3 class="subTitle"> Ajouter un item</h3>
    <?php ActiveForm::begin(['action' => ['site/newitem'], 'id' => 'addNewItem']) ?>
    <div class="form-group row">
        <label for="name-input" class="col-sm-2 col-form-label">Nom</label>
        <div class="col-sm-10">
            <input id="name-input" name="name" class="input" type="text" placeholder="Text input">
        </div>
    </div>
    <div class="form-group row">
        <label for="description-input" class="col-sm-2 col-form-label">Description</label>
        <div class="col-sm-10">
            <input id="description-input" name="description" class="input" type="text" placeholder="Text input">
        </div>
    </div>
    <div class="form-group row">
        <label for="estimation-input" class="col-sm-2 col-form-label">Estimation</label>
        <div class="col-sm-10">
            <input id="estimation-input" name="estimation" class="input" type="number" placeholder="Text input">
        </div>
    </div>
    <div class="file">
        <label class="col-sm-2 col-form-label">
            <input type="hidden" id="base64" name="picture">
            <input onchange="encodeImageFileAsURL(this)" id="picture" class="col-sm-10" type="file">
            <span class="file-cta">
                          <span class="file-icon">
                            <i class="fas fa-upload"></i>
                          </span>
                          <span class="file-label">
                            Choose a file…
                          </span>
                        </span>
        </label>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Name</label>
        <div class="col-sm-10">
            <select name="mandant">
                <?php foreach ($this->params['mandants'] as $mandant) { ?>
                    <option value="<?= $mandant->id ?>"><?= $mandant->name . " " . $mandant->firstname ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
    <?= Html::submitButton(\Yii::t('login', 'Ajouter'), ['class' => 'btn btn-primary', 'name' => 'new-item-button']) ?>
    <button class="button">QRcode</button>
    <?php ActiveForm::end() ?>

</div>
<script>

    function toItem(itemId) {
        window.location.href = '<?=Yii::$app->urlManager->createAbsoluteUrl(['site/item']);?>' + '&itemId=' + itemId;
    }


    function encodeImageFileAsURL(element) {
        console.log(element);
        console.log(element.files);
        const file = element.files[0];
        const reader = new FileReader();
        reader.onloadend = function () {
            console.log('RESULT', reader.result);
            document.getElementById('base64').value = reader.result;
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
