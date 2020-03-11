<?php

use yii\web\View; ?>
    <div class="container-fluid">
    <div class="page-title-box">
        <div class="row align-items-center">
            <div class="col-sm-12">
                <h4 class="page-title"><?= \Yii::t('frontend', 'BARÈMES DE CALCUL DES IK'); ?></h4>
                <p></p>
            </div>
        </div>
    </div>
    <!--
                        <div class="row">
                            <div class="col-12">
                                <div class="float-right d-none d-md-block">
                                    <span class="actionsTrip"><h3><?php echo \Yii::t('frontend', 'Ajouter un véhicule'); ?></h3></span>
                                </div>
                            </div>
                        </div>
-->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <!--<h4 class="mt-0 header-title">Default Datatable</h4>
                    <p class="text-muted m-b-30">DataTables has most features enabled by
                        default, so all you need to do to use it with your own tables is to call
                        the construction function: <code>$().DataTable();</code>.
                    </p>-->
                    <span class="actionsTrip"
                          onclick='loadUrlModal("Ajoute scaleIK", "index.php?r=site/widgetloader&widget=FormDbWidget&table=ScaleIK");'><i
                                class="ti-plus" style="cursor: pointer" id="sa-new-scaleIK"></i></span>
                    <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
                           style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th></th>
                            <th><?php echo \Yii::t('frontend', 'VEHICULE'); ?></th>
                            <th><?php echo \Yii::t('frontend', 'PUISSANCE'); ?></th>
                            <th><?php echo \Yii::t('frontend', "JUSQU'À 5000Km"); ?></th>
                            <th><?php echo \Yii::t('frontend', "DE 5000km À 20000Km"); ?></th>
                            <th><?php echo \Yii::t('frontend', 'AU-DELA DE 20000Km'); ?></th>
                            <th><?php echo \Yii::t('frontend', 'ANNÉE'); ?></th>
                            <th><?php echo \Yii::t('frontend', ''); ?></th>
                        </tr>
                        </thead>


                        <tbody>
                        <?php
                        foreach ($this->params['scaleiks'] as $scaleik) {
                            ?>
                            <tr>
                                <td><?php echo $scaleik['id']; ?></td>
                                <td><?php echo $scaleik['vehicle']; ?></td>
                                <td><?php echo $scaleik['power']; ?></td>
                                <td><?php echo '(d x ' . $scaleik['raw_data']->coeffBellow5k . ') + ' . $scaleik['raw_data']->extraBellow5k; ?></td>
                                <td><?php echo '(d x ' . $scaleik['raw_data']->coeffBellow20k . ') + ' . $scaleik['raw_data']->extraBellow20k; ?></td>
                                <td><?php echo '(d x ' . $scaleik['raw_data']->coeffAbove20k . ') + ' . $scaleik['raw_data']->extraAbove20k; ?></td>
                                <td><?php echo $scaleik['year']; ?></td>
                                <td>
                                    <span class="actionsTrip">
                                        <i class="ti-pencil" style="cursor: pointer"
                                           onclick='loadUrlModal("Modifier informations", "index.php?r=site/widgetloader&widget=FormDbWidget&table=ScaleIK&mode=update&id=" + "<?php echo $scaleik['id'] ?>")'></i>
                                        <i id="sa-delete-scaleik-<?php echo $scaleik['id']; ?>" class="ti-trash"
                                           style="cursor: pointer"></i></span></td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

<?php

$script = <<< JS
    initSweetAlertsDelete('###TITLE###', '###TEXT###', '###CONFIRMBUTTONTEXT###', '###CANCELBUTTONTEXT###', '###TITLE2###', '###TEXT2###', 'ScaleIK', "sa-delete-scaleik-")
JS;
$script = str_replace("###TITLE###", \Yii::t('frontend', 'CONFIRMATION'), $script);
$script = str_replace("###TEXT###", \Yii::t('frontend', 'Etes-vous sure de supprimer ce barème ?'), $script);
$script = str_replace("###CONFIRMBUTTONTEXT###", \Yii::t('frontend', 'Oui, supprimer.'), $script);
$script = str_replace("###CANCELBUTTONTEXT###", \Yii::t('frontend', 'Annuler'), $script);
$script = str_replace("###TITLE2###", \Yii::t('frontend', 'Erreur!'), $script);
$script = str_replace("###TEXT2###", \Yii::t('frontend', "Le barème n\'a pas pu être supprimé"), $script);
$this->registerJs($script, View::POS_END);
?>