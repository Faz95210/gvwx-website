<?php
$this->registerCssFile("@web/css/veltrix/chartist/css/chartist.min.css");
$this->registerJsFile("@web/js/veltrix/chartist/js/chartist.min.js", ['depends' => 'app\assets\VeltrixAsset']);
$this->registerJsFile("@web/js/veltrix/chartist/js/chartist-plugin-tooltip.min.js", ['depends' => 'app\assets\VeltrixAsset']);
$this->registerJsFile("@web/js/veltrix/pages/dashboard.js", ['depends' => 'app\assets\VeltrixAsset']);
$this->registerJsFile("@web/js/veltrix/plugins/sweet-alert2/sweetalert2.js", ['depends' => 'app\assets\VeltrixAsset']);
$this->registerCssFile("@web/js/veltrix/plugins/sweet-alert2/sweetalert2.css", ['depends' => 'app\assets\VeltrixAsset']);

use common\widgets\SaleStepWidget\SaleStepWidget;
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
            <?php ActiveForm::begin(['action' => ['site/editsale'], 'id' => 'editSale']) ?>
            <input type="hidden" name="saleId" value=" <?= $this->params['sale']->id ?>">
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">
                    Date
                </label>
                <?= Html::input('date', 'dateSale', date('Y-m-d', $this->params['sale']->date), ['class' => 'col-sm-2']) ?>
            </div>
            <?php ActiveForm::end() ?>
            <div>

                <?php ActiveForm::begin(['action' => ['site/deletesale']]) ?>
                <?= Html::submitButton(\Yii::t('login', 'Modifier'), ['form' => 'editSale', 'class' => ' btn btn-primary ', 'name' => 'edit-sale-button', 'value' => $this->params['sale']->id]) ?>
                <?php if (count($this->params['sale']->saleSteps) <= 0) { ?>
                    <button type="submit" class="btn btn-danger" name="saleId" value="<?= $this->params['sale']->id ?>">
                        Supprimer
                    </button>
                <?php } else { ?>
                    <button class="btn btn-danger" onclick="cantDelete()" type="button">Supprimer
                    </button>
                <?php } ?>
                <?php ActiveForm::end() ?>

            </div>

            <p>Generation PV de vente :

                <?php ActiveForm::begin(['action' => ['site/pvvente']]) ?>
                <input type="hidden" name="saleId" value="<?= $this->params['sale']->id ?>"/>
                <select name="fees">
                    <option value="14.5">14,50%</option>
                    <option value="20">20%</option>
                    <option value="25">25%</option>
                </select>
                <?= Html::submitButton(\Yii::t('login', 'Generer'), ['class' => ' btn btn-primary']) ?>
                <?php ActiveForm::end() ?>
            </p>
            <p>Liste des objets proposées :
                <button type="button" class="btn btn-primary" onclick="addSection()">Ajouter</button>
            </p>
        </div>
    </div>
    <div id="steps-containers">
        <?php foreach ($this->params['sale']->saleSteps as $saleStep) {
            echo SaleStepWidget::widget(['stepId' => $saleStep->id, 'saleId' => $this->params['sale']->id]);
        } ?>
    </div>

<?php
$script = <<<JS
    function addSection(){
        $.get('###URL###').done(
            function (data){
                if (data == -1) {
                     Swal.fire(
          'Erreur',
          'Aucun item disponible à la vente. Ajoutez en via la page items',
        );
                } else {
                    const container = document.getElementById('steps-containers');
                    container.innerHTML += data;
                }
            }
        );
    }
    
     function onChangeItem(e, id) {
        console.log(e.parentEl);
        const option = e.options[e.selectedIndex];
        const desc = option.getAttribute('description');
        const estimation = option.getAttribute('estimation');
        const date_mandat = option.getAttribute('date_mandat');
        document.getElementById('item-description' + id).innerText = desc;
        document.getElementById('item-estimation' + id).innerText = estimation;
        document.getElementById('item-date_mandat' + id).innerText = date_mandat;
    }

    function saveSaleStep(id) {
        const form = document.getElementById('form' + id);
        const params = {
            adjudication: form.elements["adjudication"].value,
            adjudicataire_number: form.elements["adjudicataire_number"].value,
            itemId: form.elements["itemId"].value,
            clientId: form.elements["clientId"].value,
            lotNumber: form.elements["lotNumber"].value,
            saleId: '###SALE_ID###',
        };
        const url = '###URL2###';
        console.log(url);
        $.post(url
            , params).done(function (data) {
            console.log(data);
            if (data > 0) {
                document.getElementById('edit-step' + id).hidden = false;
                document.getElementById('edit-step' + id).value = data;
                document.getElementById('remove-step' + id).hidden = false;
                document.getElementById('remove-step' + id).value = data;
                document.getElementById('add-step' + id).hidden = true;
            }
        })
    }
        function cantDelete(){
        Swal.fire(
          'Erreur',
          'Cette vente contient un ou plusieurs items - Suppression impossible. Vous devez supprimer le ou les items',
        );
    }

JS;

$script = str_replace('###URL###', Yii::$app->urlManager->createAbsoluteUrl(['site/widgetloader', 'widget' => 'SaleStepWidget', 'saleId' => $this->params['sale']->id]), $script);
$script = str_replace('###URL2###', Yii::$app->urlManager->createAbsoluteUrl(['site/addsalestep', 'widget' => 'SaleStepWidget']), $script);
$script = str_replace('###SALE_ID###', Yii::$app->request->get('saleId'), $script);

$this->registerJs($script, View::POS_END);
?>