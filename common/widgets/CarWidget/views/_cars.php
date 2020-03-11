<?php

use yii\web\View;

?>
    <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
           style="border-collapse: collapse; border-spacing: 0; width: 100%;">
        <thead>
        <tr>
            <th></th>
            <th><?= \Yii::t('frontend', 'MARQUE'); ?></th>
            <th><?= \Yii::t('frontend', 'MODEL'); ?></th>
            <th><?= \Yii::t('frontend', "ANNÉE"); ?></th>
            <th><?= \Yii::t('frontend', 'PUISSANCE (CV)'); ?></th>
            <th><?= \Yii::t('frontend', 'MOTEUR'); ?></th>
            <th><?= \Yii::t('frontend', ''); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($this->params['cars'] as $car) {
            ?>
            <tr>
                <td> <?= $car->id ?> </td>
                <td> <?= $car->brand ?> </td>
                <td> <?= $car->model ?> </td>
                <td> <?= $car->year ?> </td>
                <td> <?= $car->power->label ?> </td>
                <td> <?= $car->engine->label ?> </td>
                <td>
                <span class="actionsTrip">
                    <i class="ti-pencil" style="cursor: pointer"
                       onclick='loadUrlModal("Modifier informations", "index.php?r=site/widgetloader&widget=FormDbWidget&table=Vehicle&mode=update&id=" + "<?php echo $car->id ?>")'></i>
                    <i id="sa-delete-car-<?= $car->id ?>" class="ti-trash" style="cursor: pointer"></i>
                </span>
                </td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>


<?php


$script = <<< JS
    initSweetAlertsDelete('###TITLE###', '###TEXT###', '###CONFIRMBUTTONTEXT###', '###CANCELBUTTONTEXT###', '###TITLE2###', '###TEXT2###', 'Vehicle', "sa-delete-car-")
JS;
$script = str_replace("###TITLE###", \Yii::t('frontend', 'CONFIRMATION'), $script);
$script = str_replace("###TEXT###", \Yii::t('frontend', 'Etes-vous sure de supprimer ce véhicules ?'), $script);
$script = str_replace("###CONFIRMBUTTONTEXT###", \Yii::t('frontend', 'Oui, supprimer.'), $script);
$script = str_replace("###CANCELBUTTONTEXT###", \Yii::t('frontend', 'Annuler'), $script);
$script = str_replace("###TITLE2###", \Yii::t('frontend', 'Erreur!'), $script);
$script = str_replace("###TEXT2###", \Yii::t('frontend', "Le vehicule n\'a pas pu être supprimé"), $script);
$this->registerJs($script, View::POS_END);

?>