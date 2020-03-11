<?php
$this->registerCssFile("@web/css/veltrix/chartist/css/chartist.min.css");
$this->registerJsFile("@web/js/veltrix/chartist/js/chartist.min.js", ['depends' => 'app\assets\VeltrixAsset']);
$this->registerJsFile("@web/js/veltrix/chartist/js/chartist-plugin-tooltip.min.js", ['depends' => 'app\assets\VeltrixAsset']);
$this->registerJsFile("@web/js/veltrix/pages/dashboard.js", ['depends' => 'app\assets\VeltrixAsset']);

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<?php ActiveForm::begin(['action' => ['site/edititem']]) ?>
<input type="hidden" id="itemId" value="<?= $this->params['item']->id ?>">
<label>Nom :
    <input name="name" type="text" value="<?= $this->params['item']->name ?>">
</label><br>
<label>Description :
    <input name="description" type="text" value="<?= $this->params['item']->description ?>">
</label><br>
<label>Estimation :
    <input name"estimation" type="number" value="<?= $this->params['item']->estimation ?>">
</label><br>
<label>Photo
    <img name="picture" width="50px" height="50px" src="<?= $this->params['item']->picture ?>">
    <input type="hidden" value='<?= $this->params['item']->picture ?>' name='picture'/>
</label><br>
<label>QR
    <img name="qr" src="<?= $this->params['item']->qrcode ?>">
    <input type="hidden" value='<?= $this->params['item']->qrcode ?>' name='qr'/>
</label><br>
<label>Mandant
    <input name="mandantId" type="number" value="<?= $this->params['item']->mandant_id ?>">
</label><br>
<label>Vente
    <input name="date" type="date"
           value="<?= $this->params['item']->sale != null ? gmdate('d/m/Y', $this->params['item']->sale->date) : '' ?>">
</label><br>
<label>Client
    <input name="client" type="text"
           value="<?= $this->params['item']->sale != null && $this->params['item']->client != null ? $this->params['item']->client->name . ' ' . $this->params['item']->client->firstname : '' ?>">
</label><br>
<label>Adjudication
    <input name="adjudication" type="number" value="<?= $this->params['item']->adjudication ?>">
</label>
<br>
<?= Html::submitButton(\Yii::t('login', 'Modifier'), ['class' => 'btn btn-primary', 'name' => 'itemId', 'value' => $this->params['item']->id]) ?>
<?php ActiveForm::end() ?>

<?php ActiveForm::begin(['action' => ['site/deleteitem']]) ?>
<?= Html::submitButton(\Yii::t('login', 'Supprimer'), ['class' => 'btn btn-primary', 'name' => 'itemId', 'value' => $this->params['item']->id]) ?>
<?php ActiveForm::end() ?>
