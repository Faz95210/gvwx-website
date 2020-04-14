<?php
$this->registerCssFile("@web/css/veltrix/chartist/css/chartist.min.css");
$this->registerJsFile("@web/js/veltrix/chartist/js/chartist.min.js", ['depends' => 'app\assets\VeltrixAsset']);
$this->registerJsFile("@web/js/veltrix/chartist/js/chartist-plugin-tooltip.min.js", ['depends' => 'app\assets\VeltrixAsset']);
$this->registerJsFile("@web/js/veltrix/pages/dashboard.js", ['depends' => 'app\assets\VeltrixAsset']);
$this->registerJsFile("@web/js/veltrix/plugins/sweet-alert2/sweetalert2.js", ['depends' => 'app\assets\VeltrixAsset']);
$this->registerCssFile("@web/js/veltrix/plugins/sweet-alert2/sweetalert2.css", ['depends' => 'app\assets\VeltrixAsset']);

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

?>

    <div class="page-title-box">
        <div class="row align-items-center">
            <div class="col-sm-12">
                <h4 class="page-title"><?= \Yii::t('frontend', 'Mandant'); ?></h4>
                <p></p>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <?php ActiveForm::begin(['action' => ['site/editmandant'], 'id' => 'editForm']) ?>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Nom</label>
                <input name="name" class="col-sm-9 col-form-label form-control" type="text"
                       value="<?= $this->params['mandant']->name ?>"
                       placeholder="">
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Prenom</label>
                <input name="firstname" value="<?= $this->params['mandant']->firstname ?>"
                       class="col-sm-9 col-form-label form-control" type="text"
                       placeholder="">
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Adresse</label>
                <input name="address" value="<?= $this->params['mandant']->address ?>"
                       class="col-sm-9 col-form-label form-control" type="text"
                       placeholder="">
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Code Postal</label>
                <input name="postal" value="<?= $this->params['mandant']->postal ?>"
                       class="col-sm-9 col-form-label form-control" type="text"
                       placeholder="">
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Phone</label>
                <input name="phone" value="<?= $this->params['mandant']->phone ?>"
                       class="col-sm-9 col-form-label form-control" type="text"
                       placeholder="">
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">City</label>
                <input name="city" value="<?= $this->params['mandant']->city ?>"
                       class="col-sm-9 col-form-label form-control" type="text"
                       placeholder="">
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Mail</label>
                <input name="mail" value="<?= $this->params['mandant']->mail ?>"
                       class="col-sm-9 col-form-label form-control" type="text"
                       placeholder="">
            </div>
            <input type="hidden" name="mandantId" value="<?= $this->params['mandant']->id ?>">
            <?php ActiveForm::end() ?>
            <div class="row">
                <?= Html::submitButton(\Yii::t('login', 'Modifier'), ['class' => 'btn btn-primary col-sm-offset-2', 'form' => 'editForm', 'name' => 'mandantId', 'value' => $this->params['mandant']->id]) ?>

                <?php if (!$this->params['mandant']->soldSomething) { ?>
                    <?php ActiveForm::begin(['id' => 'removeForm', 'action' => ['site/deletemandant']]) ?>
                    <?= Html::submitButton(\Yii::t('login', 'Supprimer'), ['form' => 'removeForm', 'class' => 'btn btn-primary col-sm-offset-2', 'name' => 'deleteMandant', 'value' => $this->params['mandant']->id]) ?>
                    <?php ActiveForm::end() ?>
                <?php } else { ?>
                    <div>
                        <button class="btn btn-primary col-sm-offset-2" onclick="cantDelete()">Supprimer</button>
                    </div>
                <?php } ?>
            </div>

        </div>
    </div>
    <div class="card">
        <div class="card-body">

            <h2 class="card-title">Liste des objets mandatés :</h2>
            <div class="table-container">
                <table class="table is-bordered is-hoverable is-fullwidth">
                    <thead>
                    <tr>
                        <th>Date :</th>
                        <th>Nom :</th>
                        <th>Montant :</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($this->params['mandant']->items as $item) { ?>
                        <tr>
                            <td> <?= $item->sale->date ? gmdate('d/m/Y', $item->sale->date) : '' ?> </td>
                            <td> <?= $item->name ?></td>
                            <td> <?= $item->adjudication ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>

                <?php ActiveForm::begin(['action' => ['site/facturemandant']]) ?>
                <select name="dateSale">
                    <?php foreach ($this->params['salesDate'] as $date) { ?>
                        <option value="<?= $date ?>"><?= gmdate('d/m/Y', $date) ?></option>
                    <?php } ?>
                </select>
                <select name="fees">
                    <option value="14.5">14,5%</option>
                    <option value="20">20%</option>
                    <option value="25">25%</option>
                </select>
                <?= Html::submitButton(\Yii::t('login', 'Facture'), ['class' => 'btn btn-primary', 'name' => 'mandantId', 'value' => $this->params['mandant']->id]) ?>
                <?php ActiveForm::end() ?>
            </div>
        </div>
    </div>
<?php
$script = <<<JS

    function cantDelete(){
        Swal.fire(
          'Erreur',
          'Ce mandant a vendu un item - Suppression impossible. Vous devez supprimer l’acquisition depuis l’onglet « Ventes »',
        );
    }

JS;

$this->registerJs($script, View::POS_END);
