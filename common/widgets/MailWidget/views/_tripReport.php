<?php
if (!$this->params['trip']) {
    echo "No such trip";
    return;
}
?>


<div class="container-fluid">
    <div class="page-title-box">
        <div class="row align-items-center">
            <div class="col-sm-12">
                <h4 class="page-title"><?= \Yii::t('frontend', 'DÉPLACEMENT RAPPORT'); ?></h4>
                <p></p>
            </div>
        </div>
    </div>
    <div class="row">
        <?php if ($this->params['trip']->startCoordinate->address_id && $this->params['trip']->stopCoordinate->address_id) {
            ?>
            Un déplacement est terminé.
            <?php
        } else {
            ?>
            Un déplacement est terminé, mais une addresse n'a pas été trouvée.
            <?php
        }
        ?>
    </div>
    <br>
    <div class="row">
        <?= \common\widgets\TripsWidget\TripsWidget::widget(['_mode' => 'standard', '_singleTripId' => $this->params['trip_id']]) ?>
    </div>
    <div class="row">
        <?= \common\widgets\MapWidget\MapWidget::widget() ?>
    </div>