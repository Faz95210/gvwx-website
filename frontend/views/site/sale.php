<?php
$this->registerCssFile("@web/css/veltrix/chartist/css/chartist.min.css");
$this->registerJsFile("@web/js/veltrix/chartist/js/chartist.min.js", ['depends' => 'app\assets\VeltrixAsset']);
$this->registerJsFile("@web/js/veltrix/chartist/js/chartist-plugin-tooltip.min.js", ['depends' => 'app\assets\VeltrixAsset']);
$this->registerJsFile("@web/js/veltrix/pages/dashboard.js", ['depends' => 'app\assets\VeltrixAsset']);

use common\widgets\SaleStepWidget\SaleStepWidget;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

?>
    <div class="card">
        <div class="card-body">
            <?php ActiveForm::begin(['action' => ['site/editsale'], 'id' => 'editSale']) ?>
            <input type="hidden" name="saleId" value=" <?= $this->params['sale']->id ?>">
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">
                    Sale
                </label>
                <input class="col-sm-2" type="date" name="dateSale"
                       value="<?= gmdate('d/m/Y', $this->params['sale']->date) ?>">
            </div>
            <?php ActiveForm::end() ?>
            <div>

                <?php ActiveForm::begin(['action' => ['site/deletesale']]) ?>
                <?= Html::submitButton(\Yii::t('login', 'Modifier'), ['form' => 'editSale', 'class' => ' btn btn-primary ', 'name' => 'edit-sale-button', 'value' => $this->params['sale']->id]) ?>
                <button type="submit" class=" btn btn-danger" name="saleId" value="<?= $this->params['sale']->id ?>">
                    Supprimer
                </button>
                <?php ActiveForm::end() ?>
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
                console.log(data);
                const container =document.getElementById('steps-containers');
                container.innerHTML += data;
            }
        );
    }
JS;
$script = str_replace('###URL###', Yii::$app->urlManager->createAbsoluteUrl(['site/widgetloader', 'widget' => 'SaleStepWidget', 'saleId' => $this->params['sale']->id]), $script);

$this->registerJs($script, View::POS_END);
?>