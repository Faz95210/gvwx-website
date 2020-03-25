<?php
$this->registerCssFile("@web/css/veltrix/chartist/css/chartist.min.css");
$this->registerJsFile("@web/js/veltrix/chartist/js/chartist.min.js", ['depends' => 'app\assets\VeltrixAsset']);
$this->registerJsFile("@web/js/veltrix/chartist/js/chartist-plugin-tooltip.min.js", ['depends' => 'app\assets\VeltrixAsset']);
$this->registerJsFile("@web/js/veltrix/pages/dashboard.js", ['depends' => 'app\assets\VeltrixAsset']);

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

?>
<div class="card">
    <div class="card-body">
        <div class="table-container">

            <table class="table is-bordered is-hoverable is-fullwidth">
                <thead>
                <tr>
                    <th>
                        Date
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($this->params['sales'] as $sale) { ?>
                    <tr onclick="toSale('<?= $sale->id ?>')">
                        <td><?= gmdate("d/m/Y", $sale->date) ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>

        <?php ActiveForm::begin(['action' => ['site/newsale'], 'id' => 'addNewSale', 'class' => 'form-group row']) ?>
        <label class="col-sm-2 col-form-label">
            Date :
        </label>
        <input class="col-lg-4 col-form-label" type="text" name="date">
        <?= Html::submitButton(\Yii::t('login', 'Ajouter'), ['class' => 'btn btn-primary col-lg-2 col-lg-offset-2', 'name' => 'new-sale-button']) ?>
        <?php ActiveForm::end() ?>

    </div>
</div>

<script>
    function toSale(saleId) {
        window.location.href = '<?=Yii::$app->urlManager->createAbsoluteUrl(['site/sale']);?>' + '&saleId=' + saleId;
    }
</script>
