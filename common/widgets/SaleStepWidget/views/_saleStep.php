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
                <option></option>
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
                   value="<?= $step !== null ? $step->item->adjudication : '' ?>"
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

