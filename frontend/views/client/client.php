<?php
$this->registerCssFile("@web/css/veltrix/chartist/css/chartist.min.css");
$this->registerJsFile("@web/js/veltrix/chartist/js/chartist.min.js", ['depends' => 'app\assets\VeltrixAsset']);
$this->registerJsFile("@web/js/veltrix/chartist/js/chartist-plugin-tooltip.min.js", ['depends' => 'app\assets\VeltrixAsset']);
$this->registerJsFile("@web/js/veltrix/pages/dashboard.js", ['depends' => 'app\assets\VeltrixAsset']);
$this->registerJsFile("@web/js/veltrix/plugins/sweet-alert2/sweetalert2.js", ['depends' => 'app\assets\VeltrixAsset']);
$this->registerCssFile("@web/js/veltrix/plugins/sweet-alert2/sweetalert2.css", ['depends' => 'app\assets\VeltrixAsset']);

use common\widgets\DatePickerWidget\DatePickerWidget;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

ActiveForm::begin(['action' => ['client/edit'], 'id' => 'editClient'])
?>

    <div class="page-title-box">
        <div class="row align-items-center">
            <div class="col-sm-12">
                <h4 class="page-title"><?= \Yii::t('frontend', 'Client'); ?></h4>
                <p></p>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Nom</label>
                <input name="name" class="col-sm-6" type="text" value="<?= $this->params['client']->name ?>"
                       placeholder="">
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Prenom</label>
                <input name="firstname" value="<?= $this->params['client']->firstname ?>" class="col-sm-6 " type="text"
                       placeholder="">
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Adresse</label>
                <input name="address" value="<?= $this->params['client']->address ?>" class="col-sm-6 " type="text"
                       placeholder="">
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Code Postal</label>
                <input name="postal" value="<?= $this->params['client']->postal ?>" class="col-sm-6 " type="text"
                       placeholder="">
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Telephone</label>
                <input name="phone" value="<?= $this->params['client']->phone ?>" class="col-sm-6 " type="text"
                       placeholder="">
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Ville</label>
                <input name="city" value="<?= $this->params['client']->city ?>" class="col-sm-6 " type="text"
                       placeholder="">
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Mail</label>
                <input name="mail" value="<?= $this->params['client']->mail ?>" class="col-sm-6 " type="text"
                       placeholder="">
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Date de naissance</label>
                <div class="col-sm-9">
                    <?=
                    DatePickerWidget::widget([
                        'name' => 'birthdate',
                        'value' => $this->params['client']->birthdate,
                        'template' => '{addon}{input}',
                        'clientOptions' => [
                            'autoclose' => true,
                            'format' => 'dd/mm/yyyy'
                        ]
                    ]);
                    ?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Lieu de naissance</label>
                <input name="birthplace" value="<?= $this->params['client']->birthplace ?>" class="col-sm-6 "
                       type="text"
                       placeholder="">
            </div>
            <input type="hidden" name="clientId" value="<?= $this->params['client']->id ?>">
            <?= Html::submitButton(\Yii::t('login', 'Modifier'), ['class' => 'btn btn-primary', 'name' => 'edit-client-button']) ?>
            <?php ActiveForm::end() ?>
            <?php if (count($this->params['client']->sales) > 0) { ?>
                <button class="btn btn-primary" onclick="cantDelete()" type="button">Supprimer</button>
            <?php } else { ?>
                <?php ActiveForm::begin(['action' => ['client/delete'], 'id' => 'editClient']) ?>
                <?= Html::submitButton(\Yii::t('login', 'Supprimer'), ['class' => 'btn btn-primary', 'name' => 'clientId', 'value' => $this->params['client']->id]) ?>
                <?php ActiveForm::end() ?>
            <?php } ?>


        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h2>Liste des acquisitions</h2>
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
                    <?php foreach ($this->params['client']->sales as $sale) { ?>
                        <?php foreach ($sale->saleSteps as $saleStep) { ?>
                            <tr>
                                <td> <?= $sale->date ?></td>
                                <td> <?= $saleStep->item->name ?></td>
                                <td> <?= $saleStep->item->adjudication ?> </td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                    </tbody>
                </table>
                <?php if (count($this->params['client']->sales) > 0) { ?>
                    <?php ActiveForm::begin(['action' => ['client/facture'], 'id' => 'generateFacture']) ?>
                    <select name="dateSale">
                        <?php foreach ($this->params['salesDate'] as $date) { ?>
                            <option value="<?= $date ?>"><?= $date ?>) ?></option>
                        <?php } ?>
                    </select>

                    <select name="fees">
                        <option value="14.5">14,5%</option>
                        <option value="20">20%</option>
                        <option value="25">25%</option>
                    </select>
                    <?= Html::submitButton(\Yii::t('login', 'Facture'), ['class' => 'btn btn-primary', 'name' => 'clientId', 'value' => $this->params['client']->id]) ?>
                    <?php ActiveForm::end() ?>
                <?php } else { ?>
                    <button class="btn btn-primary" disabled>Facture</button>
                <?php } ?>
            </div>
        </div>
    </div>
<?php

$script = <<<JS
    function cantDelete(){
    Swal.fire(
      'Erreur',
      'Ce client a acquis un item - Suppression impossible. Vous devez supprimer l’acquisition depuis l’onglet « Ventes ».',
    );
    }
JS;

$this->registerJs($script, View::POS_END);