<?php
$this->registerCssFile("@web/css/veltrix/chartist/css/chartist.min.css");
$this->registerJsFile("@web/js/veltrix/chartist/js/chartist.min.js", ['depends' => 'app\assets\VeltrixAsset']);
$this->registerJsFile("@web/js/veltrix/chartist/js/chartist-plugin-tooltip.min.js", ['depends' => 'app\assets\VeltrixAsset']);
$this->registerJsFile("@web/js/veltrix/pages/dashboard.js", ['depends' => 'app\assets\VeltrixAsset']);

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

?>
<div class="column">
    <div id="container" class="container">
        <?php ActiveForm::begin(['action' => ['site/editsale'], 'id' => 'editSale']) ?>
        <label>
            Sale
            <input type="date" value="<?= $this->params['sale']->date ?>">
        </label>
        <?= Html::submitButton(\Yii::t('login', 'Modifier'), ['class' => 'btn btn-primary', 'name' => 'edit-sale-button']) ?>
        <?php ActiveForm::end() ?>

        <button onclick="deleteSale()">Supprimer</button>
    </div>
    <p>Generation PV de vente :
        <?= Html::a('Génerer', [
            'site/pvvente',
            'saleId' => $this->params['sale']->id,
        ], [
            'class' => 'btn btn-primary',
            'target' => '_blank',
        ]); ?>    </p>
    <p>Liste des objets proposées :
        <button class="btn btn-primary" onclick="addSection()">Ajouter</button>
    </p>
    <div id="steps-containers">

        <?php foreach ($this->params['sale']->saleSteps as $saleStep) { ?>
            <select>
                <?php foreach ($this->params['items'] as $item) { ?>
                    <option
                        <?= $item->id === $saleStep->item_id ? 'selected' : '' ?>value="<?= $item->id ?>"><?= $item->name ?></option>
                <?php } ?>
            </select>
            <table>
                <tr>
                    <td>Description :</td>
                    <td>Estimation :</td>
                </tr>
                <tr>
                    <td id="item-description"> <?= $saleStep->item->description ?></td>
                    <td id="item-description"> <?= $saleStep->item->estimation ?></td>
                </tr>
            </table>
            <select>
                <?php foreach ($this->params['clients'] as $client) { ?>
                    <option value="<?= $client->id ?>"><?= $client->name . ' ' . $client->firstname ?></option>
                <?php } ?>
            </select>
            <p>Adjudication : <?= $item->adjudication ?></p>
        <?php } ?>
    </div>

</div>

<script>
    let sectionIndex = <?= count($this->params['sale']->saleSteps) ?>;

    function addSection() {
        sectionIndex++;
        let optionToValue = [];
        const section = sectionIndex;
        const stepsContainers = document.getElementById('steps-containers');
        const form = document.createElement('form');
        form.action = '/index.php?r=site%2Faddsalestep';
        form.method = 'post';
        stepsContainers.appendChild(form);

        const select = document.createElement('select');
        select.id = 'select' + section;
        select.name = 'item';
        <?php foreach($this->params['items'] as $item) { ?>
        const option<?= $item->id ?> = document.createElement('option');
        option<?=$item->id?>.value = <?=$item->id?>;
        option<?=$item->id?>.id = 'option<?=$item->id?>';
        optionToValue[optionToValue.length] = {
            'id': 'option<?=$item->id?>',
            'description': '<?=$item->description ?>',
            'estimation': '<?=$item->estimation ?>',
        }
        option<?=$item->id?>.innerText = '<?=$item->name?>';
        select.appendChild(option<?=$item->id?>);
        <?php } ?>
        const table = document.createElement('table');
        const header = document.createElement('tr');
        header.insertCell().innerText = 'description';
        header.insertCell().innerText = 'estimation';
        table.appendChild(header);
        <?php if (count($this->params['items']) > 0) { ?>
        const body = document.createElement('tr');
        body.id = 'row' + sectionIndex;
        body.insertCell().innerText = '<?= $this->params['items'][0]->description ?>';
        body.insertCell().innerText = '<?= $this->params['items'][0]->estimation ?>';
        table.appendChild(body);
        <?php } ?>
        form.appendChild(select);
        form.appendChild(table);
        document.getElementById('select' + section).onchange = function () {
            for (var i = 0; i < this.options.length; i++) {
                if (this.options[i].selected) {
                    for (let j = 0; j < optionToValue.length; j++) {
                        const option = optionToValue[j]
                        console.log(option, this.options[i].id);
                        if (option['id'] === this.options[i].id) {
                            body.deleteCell(0);
                            body.deleteCell(0);
                            body.insertCell().innerText = option['description'];
                            body.insertCell().innerText = option['estimation'];
                        }
                    }
                }
            }
        }
        const clientsSelect = document.createElement('select');
        clientsSelect.name = 'client';
        <?php foreach ($this->params['clients'] as $client) { ?>
        const optionClient<?= $client->id ?> = document.createElement('option');
        optionClient<?=$client->id?>.value = <?=$client->id?>;
        optionClient<?=$client->id?>.innerText = '<?=$client->name . ' ' . $client->firstname?>';
        clientsSelect.appendChild(optionClient<?=$client->id?>);
        <?php } ?>

        form.appendChild(clientsSelect);
        const adjudicationInput = document.createElement('input')
        adjudicationInput.type = 'text';
        adjudicationInput.placeholder = 'adjudication';
        adjudicationInput.name = 'adjudication';

        form.appendChild(adjudicationInput);
        const btn = document.createElement('button');
        btn.type = 'submit';
        btn.name = 'sale';
        btn.value = '<?= Yii::$app->request->get('saleId') ?>';
        form.appendChild(btn);
    }
</script>