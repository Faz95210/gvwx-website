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
            <h4 class="page-title"><?= \Yii::t('frontend', 'Ventes'); ?></h4>
            <p></p>
        </div>
    </div>
</div>
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
                        <td><?= $sale->date ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>

        <?php ActiveForm::begin(['action' => ['sale/new'], 'id' => 'addNewSale', 'class' => 'form-group row']) ?>
        <label class="col-sm-2 col-form-label">
            Date :
        </label>

        <div class="col-sm-6">
            <?= DatePickerWidget::widget([
                'name' => 'date',
                'value' => $this->params['item']->date_mandat,
                'template' => '{addon}{input}',
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'dd/mm/yyyy'
                ]
            ]); ?>
        </div>
        <?= Html::submitButton(\Yii::t('login', 'Ajouter'), ['class' => 'btn btn-primary col-sm-2 col-lg-offset-2', 'name' => 'new-sale-button']) ?>
        <?php ActiveForm::end() ?>

    </div>
</div>

<script>
    function toSale(saleId) {
        window.location.href = '<?=Yii::$app->urlManager->createAbsoluteUrl(['sale/get']);?>' + '&saleId=' + saleId;
    }
</script>
