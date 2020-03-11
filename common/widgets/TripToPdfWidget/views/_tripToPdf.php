<?php
$this->registerCssFile(dirname(__FILE__) . "/assets/css/style.css");
?>
<style>

    .table {
        border: 1px solid #dee2e6 !important
    }

    .table td,
    .table th {
        border: 1px solid #dee2e6 !important
    }

    .table th {
        color: inherit;
        border-color: #dee2e6
    }

    body {
        background-repeat: repeat;
        background: #f8f8fa;
        font-family: "Roboto", sans-serif;
        color: #5b626b;
        font-size: 14px;
    }

    h1, h2, h3, h4, h5, h6 {
        margin: 10px 0;
        font-family: "Sarabun", sans-serif;
        font-weight: 600;
    }

    .page-title-box .page-title {
        font-size: 50px;
        margin: 0;
        line-height: 30px;
        font-weight: 700;
    }

    h4 {
        display: block;
        margin-block-start: 1.33em;
        margin-block-end: 1.33em;
        margin-inline-start: 0px;
        margin-inline-end: 0px;
        font-weight: bold;
    }

    .container-fluid {
        width: 100%;
        padding-top: 15px;
        padding-right: 15px;
        padding-left: 15px;
        margin-right: auto;
        margin-left: auto;
    }

    .row {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        margin-right: -15px;
        margin-left: -15px
    }

    .col,
    .col-sm-12 {
        -ms-flex-preferred-size: 0;
        flex-basis: 0;
        -ms-flex-positive: 1;
        flex-grow: 1;
        max-width: 100%
    }

    .align-items-center {
        -ms-flex-align: center !important;
        align-items: center !important
    }

    .float-right {
        float: right !important
    }

</style>
<div class="container-fluid">
    <div class="page-title-box">
        <div class="row align-items-center">

            <div class="col-sm-12">
                <h4 class="page-title"><?= \Yii::t('frontend', 'EXPORT DES DÉPLACEMENTS'); ?></h4>
                <p></p>
            </div>
        </div>
    </div>
    <br>
    <div class="float-right">
        <div class="row">
            <div class="col float-right">
                <?= strtoupper($this->params['user']->name) . " " . $this->params['user']->givenname ?>
            </div>
        </div>
        <div class="row">
            <div class="col float-right">
                Du : <?= $this->params['from'] ?>
                <br>
                Au : <?= $this->params['to'] ?>
            </div>
        </div>
    </div>
    <br>
    <hr>
    <h4><?= \Yii::t('frontend', 'DÉPLACEMENTS'); ?></h4>
    <table class="table">
        <thead>
        <tr>
            <th><?php echo \Yii::t('frontend', 'DATE'); ?></th>
            <th><?php echo \Yii::t('frontend', 'DÉPART'); ?></th>
            <th><?php echo \Yii::t('frontend', "DESTINATION"); ?></th>
            <th><?php echo \Yii::t('frontend', 'DURÉE'); ?></th>
            <th><?php echo \Yii::t('frontend', 'DISTANCE'); ?></th>
            <th><?php echo \Yii::t('frontend', 'VEHICLE'); ?></th>
            <th><?php echo \Yii::t('frontend', 'COMMENTAIRES'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        $duration = 0;
        $distance = 0;
        foreach ($this->params['trips'] as $trip) {
            $duration += $trip->duration;
            $distance += $trip->distance;
            ?>
            <tr>
                <td>
                    <?= gmdate("y-d-m", $trip->start_date_time); ?>
                </td>
                <td>
                    <?= isset($trip->startCoordinate->address) ? $trip->startCoordinate->address->label : 'Incomplet' ?>
                </td>
                <td>
                    <?= isset($trip->stopCoordinate->address) ? $trip->stopCoordinate->address->label : 'Incomplet' ?>
                </td>
                <td>
                    <?= $trip->duration . ' minutes' ?>
                </td>
                <td>
                    <?= $trip->distance . ' KM' ?>
                </td>
                <td>
                    <?= $trip->vehicle ? $trip->vehicle->brand . " " . $trip->vehicle->model : 'Incomplet' ?>
                </td>
                <td>
                    <?= $trip->comments ?>
                </td>
            </tr>

            <?php
        }
        ?>
        </tbody>
    </table>
    <br>
    <hr>
    <h4><?= \Yii::t('frontend', 'RÉSUMÉ'); ?></h4>

    <table class="table">
        <thead>
        <tr>
            <th><?php echo \Yii::t('frontend', 'Déplacements'); ?></th>
            <th><?php echo \Yii::t('frontend', 'Durée'); ?></th>
            <th><?php echo \Yii::t('frontend', 'Distance'); ?></th>
            <th><?php echo \Yii::t('frontend', 'Remboursement'); ?></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><?= count($this->params['trips']) ?></td>
            <td><?= $duration . ' minutes' ?></td>
            <td><?= $distance . ' KM' ?></td>
            <td><?= $this->params['refund'] . ' EUR' ?></td>
        </tr>
        </tbody>
    </table>
</div>
