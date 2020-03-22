<?php

use yii\widgets\ActiveForm;

$step = $this->params['step'];
$id = time();
?>

<div class="card">
    <div class="card-body">
        <?php ActiveForm::begin(['action' => ['site/editsalestep'], 'id' => "form$id"]) ?>
        <div class="form-group row">
            <label class="col-sm-2">Item :</label>
            <select onchange="onChangeItem(this, <?= $id ?>)" name="itemId" class="form-control col-sm-8">

                <?php foreach ($this->params['items'] as $item) { ?>
                    <option <?= ($step !== null && $item->id === $step->item_id) ? 'selected' : '' ?>
                            value="<?= $item->id ?>" description="<?= $item->description ?>"
                            estimation="<?= $item->estimation ?>">
                        <?= $item->name ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <table class="table table-bordered table-hoverable">
            <thead class="thead-default">
            <tr>
                <td>Description :</td>
                <td>Estimation :</td>
            </tr>
            <thead>
            <tbody>
            <tr>
                <td id="item-description<?= $id ?>"> <?= $step !== null ? $step->item->description : $this->params['items'][0]->description ?></td>
                <td id="item-estimation<?= $id ?>"> <?= $step !== null ? $step->item->estimation : $this->params['items'][0]->estimation ?></td>
            </tr>
            </tbody>
        </table>
        <div class="form-group row">
            <label class="col-sm-2"> Client : </label>
            <select name="clientId" class="form-control col-sm-8">
                <?php foreach ($this->params['clients'] as $client) { ?>
                    <option <?= ($step !== null && $client->id === $step->client->id) ? 'selected' : '' ?>
                            value="<?= $client->id ?>"> <?= $client->name . ' ' . $client->firstname ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-sm-2">
                Adjudication :
            </label>
            <input class="form-control col-sm-8" type="number"
                   value="<?= $step !== null ? $step->item->adjudication : 0 ?>"
                   name="adjudication">

        </div>
        <button <?= $step == null ? 'hidden' : '' ?> id="edit-step<?= $id ?>" type="submit" class="btn btn-primary"
                                                     name="saleStepEdit" value="<?= $step->id ?>">Modifier
        </button>
        <button <?= $step == null ? 'hidden' : '' ?> id="remove-step<?= $id ?>" type="submit" class="btn btn-danger"
                                                     name="saleStepDelete" value="<?= $step->id ?>">Supprimer
        </button>
        <button <?= $step != null ? 'hidden' : '' ?> id="add-step<?= $id ?>" type="button"
                                                     onclick="saveSaleStep(<?= $id ?>)"
                                                     class="btn btn-success" name="saleStepNew"
                                                     value="<?= $step->id ?>">Ajouter
        </button>
        <?php
        ActiveForm::end();
        ?>
    </div>
</div>

<script>
    function onChangeItem(e, id) {
        console.log(e.parentEl);
        const option = e.options[e.selectedIndex];
        const desc = option.getAttribute('description');
        const estimation = option.getAttribute('estimation');
        document.getElementById('item-description' + id).innerText = desc;
        document.getElementById('item-estimation' + id).innerText = estimation;
    }

    function saveSaleStep(id) {
        const form = document.getElementById('form' + id);
        const params = {
            adjudication: form.elements["adjudication"].value,
            itemId: form.elements["itemId"].value,
            clientId: form.elements["clientId"].value,
            saleId:<?=$this->params['saleId'] ?>,
        }
        console.log();
        console.log(form.elements["itemId"].value);
        console.log(form.elements["clientId"].value);
        console.log(form.elements["clientId"].value);
        $.post('<?= Yii::$app->urlManager->createAbsoluteUrl(['site/addsalestep', 'widget' => 'SaleStepWidget']) ?>'
            , params).done(function (data) {
            console.log(data);
            if (data > 0) {
                document.getElementById('edit-step' + id).hidden = false;
                document.getElementById('edit-step' + id).value = data;
                document.getElementById('remove-step' + id).hidden = false;
                document.getElementById('remove-step' + id).value = data;
                document.getElementById('add-step' + id).hidden = false;
            }
        })
    }
</script>