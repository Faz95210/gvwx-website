<?php

use yii\helpers\Html;
use yii\web\View;

if ($this->params['mode'] === 'standard') {
    ?>
    <div class="row">
        <div class="col-12">
            <div class="float-right d-none d-md-block">
                <span class="actionsTrip btn btn-default"
                      id="export"><?= Html::a('<h3>' . \Yii::t('login', 'Telecharger rapport') . '</h3>', ['']) ?></h3></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <!--<h4 class="mt-0 header-title">Default Datatable</h4>
                    <p class="text-muted m-b-30">DataTables has most features enabled by
                        default, so all you need to do to use it with your own tables is to call
                        the construction function: <code>$().DataTable();</code>.
                    </p>-->

                    <div class="row">
                        <label>
                            Afficher
                            <select name="length" id="trip-table-length">
                                <option value="10" <?php echo $this->params['limit'] == 10 ? 'selected' : '' ?> >10
                                </option>
                                <option value="25" <?php echo $this->params['limit'] == 25 ? 'selected' : '' ?> >25
                                </option>
                                <option value="50" <?php echo $this->params['limit'] == 50 ? 'selected' : '' ?> >50
                                </option>
                                <option value="100" <?php echo $this->params['limit'] == 100 ? 'selected' : '' ?> >100
                                </option>
                            </select>
                            Elements
                        </label>
                    </div>
                    <table id="trip-table" class="display table table-bordered nowrap dt-responsive"
                           style="border-collapse: collapse; border-spacing: 0; width: 100%;">

                        <thead>
                        <tr>
                            <th></th>
                            <th><?php echo \Yii::t('frontend', 'DÉPART'); ?></th>
                            <th><?php echo \Yii::t('frontend', 'LIEU DE DÉPART'); ?></th>
                            <th><?php echo \Yii::t('frontend', "LIEU D'ARRIVÉE"); ?></th>
                            <th><?php echo \Yii::t('frontend', 'ARRIVÉE'); ?></th>
                            <th><?php echo \Yii::t('frontend', 'DURÉE'); ?></th>
                            <th><?php echo \Yii::t('frontend', 'DISTANCE'); ?></th>
                            <th><?php echo \Yii::t('frontend', 'VEHICLE'); ?></th>
                            <!--                            <th>-->
                            <?php //echo \Yii::t('frontend', 'REMBOURSEMENT'); ?><!--</th>-->
                            <th><?php echo \Yii::t('frontend', 'COMMENTAIRES'); ?></th>
                            <th><?php //echo \Yii::t('frontend', ''); ?></th>
                        </tr>
                        </thead>


                        <tbody>
                        <?php
                        foreach ($this->params['trips'] as $trip) {
                            echo "<tr>";
                            echo "<td>" . $trip['id'] . "</td>";
                            echo "<td>" . $trip['startDateTime'] . "</td>";
                            if (isset($trip['startAddress'])) {
                                echo "<td>" . $trip['startAddress'] . "</td>";
                            } else {
                                echo "<td><span onclick='loadUrlModal(\"Addresse incomplete\", \"index.php?r=site/widgetloader&widget=IncompleteAddressWidget&coordinate_id=" . $trip['start_coordinate_id'] . "&light=1\");' style='cursor: pointer' data-toggle=\"tooltip\" data-placement=\"top\" title=\"Incomplete addresse. Click to fix it.\"> <i class='ti-help'></i><br></span></td>";
                            }

                            if (isset($trip['stopAddress'])) {
                                echo "<td>" . $trip['stopAddress'] . "</td>";
                            } else {
                                echo "<td><span onclick='loadUrlModal(\"Adresse incomplete\", \"index.php?r=site/widgetloader&widget=IncompleteAddressWidget&coordinate_id=" . $trip['stop_coordinate_id'] . "&light=1\");' style='cursor: pointer' data-toggle=\"tooltip\" data-placement=\"top\" title=\"Incomplete addresse. Click to fix it.\"> Incomplete Addresse<br></span></td>";
                            }
                            echo "<td>" . $trip['stopDateTime'] . "</td>";
                            echo "<td>" . $trip['duration'] . "</td>";
                            echo "<td>" . $trip['distance'] . "</td>";
                            echo "<td>" . $trip['vehicle'] . "</td>";
//                            echo "<td>".$trip['refund']."</td>";
                            echo "<td>" . $trip['comments'] . "</td>";
                            echo "<td><span class='actionsTrip'>";
                            echo "<i class='ti-location-pin' style='cursor: pointer' onclick='loadUrlModal(\"testOnCLick\",\"index.php?r=site/rawmap&job=kml&kml=" . $trip['kml'] . "\")'></i>";
                            echo "<i class='ti-pencil' style='cursor: pointer' onclick='loadUrlModal(\"testOnCLick\", \"http://ikar.api.dev/widgetloader?widgetName=map\")'></i> <i id='sa-delete-trip' class='ti-trash' style='cursor: pointer'></i></span></td>";
                            echo "</span></td>";
                            echo "</tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-sm-6 col-md-5">
                            <div class="dataTables_info" id="datatable-buttons_info" role="status" aria-live="polite">
                                Affichage de
                                l'élement <?php echo (($this->params['page'] - 1) * $this->params['limit']) + 1 ?>
                                à <?php echo($this->params['count'] < $this->params['page'] * $this->params['limit'] ? $this->params['count'] : $this->params['limit'] * $this->params['page']); ?>
                                sur <?php echo $this->params['count']; ?> éléments
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-5">
                            <ul class="pagination">
                                <li class="paginate_button page-item previous <?php echo $this->params['page'] <= 1 ? 'disabled' : '' ?>">
                                    <a href="#" aria-controls="datatable-buttons" data-dt-idx="0" tabindex="0"
                                       class="page-link">Précédent</a>
                                </li>
                                <?php
                                $pageNumber = 1;
                                for ($i = 0; $i < $this->params['count']; $i += $this->params['limit']) {
                                    echo '<li class="paginate_button page-item ' . ($pageNumber == $this->params['page'] ? 'active' : '') . '">';
                                    echo '<a href="#" aria-controls="datatable-buttons" data-dt-idx="' . $pageNumber . '" tabindex="' . ($pageNumber - 1) . '" class="page-link">' . $pageNumber . '</a>';
                                    echo '</li>';
                                    $pageNumber++;
                                }
                                ?>
                                <li class="paginate_button page-item next <?php echo $this->params['page'] == $pageNumber - 1 ? 'disabled' : '' ?>">
                                    <a href="#" aria-controls="datatable-buttons"
                                       data-dt-idx="<?php echo $pageNumber ?>" tabindex="0"
                                       class="page-link">Suivant</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
} else if ($this->params['mode'] === 'light') {
    ?>
    <div class="card">
        <div class="card-body">
            <h4 class="mt-0 header-title mb-4"><?= \Yii::t('frontend', 'Récent déplacements'); ?></h4>
            <ol class="activity-feed">
                <?php
                foreach ($this->params['trips'] as $trip) {
                    $startDateTime = $trip['startDateTime'];
//                    $categoryColor = $trip->categoryColor;
                    $destination = isset($trip['stopAddress']) ? $trip['stopAddress'] : "Non renseigné";
                    echo "<li class='feed-item'>";
                    echo "<div class='feed-item-list'>";
                    echo "<span class='date'>$startDateTime</span>";
                    echo "<span class='activity-text'>" . \Yii::t('frontend', $destination) . "</span>";
                    echo "</div>";
                    echo "</li>";
                }
                ?>
            </ol>
            <div class="text-center">
                <?= Html::a('<button class="btn btn-primary">Voir la suite</button> ', ['site/trips'], ['class' => 'waves-effect']) ?>
            </div>
        </div>
    </div>
    <?php
} else if ($this->params['mode'] === 'single') {
    ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap"
                           style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th><?php echo \Yii::t('frontend', 'DÉPART'); ?></th>
                            <th><?php echo \Yii::t('frontend', 'LIEU DE DÉPART'); ?></th>
                            <th><?php echo \Yii::t('frontend', "LIEU D'ARRIVÉE"); ?></th>
                            <th><?php echo \Yii::t('frontend', 'ARRIVÉE'); ?></th>
                            <th><?php echo \Yii::t('frontend', 'DURÉE'); ?></th>
                            <th><?php echo \Yii::t('frontend', 'DISTANCE'); ?></th>
                            <th><?php echo \Yii::t('frontend', 'VEHICLE'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $trip = $this->params['trips'][0];
                        //                            $bgcolor = '#FFFFFF';
                        $startAddress = null;
                        if ($trip->startCoordinate->address) {
                            $startAddress = $trip->startCoordinate->address->label . " <br>" . $trip->startCoordinate->address->address1 . " " . $trip->startCoordinate->address->address2 . " " . $trip->startCoordinate->address->postcode . " " . $trip->startCoordinate->address->city;
                        }
                        $kml = $trip->kml_file;
                        $comments = $trip->comments;
                        $stopAddress = null;
                        if ($trip->stopCoordinate->address) {
                            $stopAddress = $trip->stopCoordinate->address->label . " <br>" . $trip->stopCoordinate->address->address1 . " " . $trip->stopCoordinate->address->address2 . " " . $trip->stopCoordinate->address->postcode . " " . $trip->stopCoordinate->address->city;
                        }
                        $vehicle = $trip->vehicle->brand . " " . $trip->vehicle->model;
                        $startDateTime = gmdate("Y-m-d h:i:s", $trip->start_date_time);
                        $stopDateTime = gmdate("Y-m-d h:i:s", $trip->stop_date_time);
                        $duration = $trip->duration;
                        $distance = $trip->distance;
                        //                            $possibilities = $trip->possibilities;
                        //                            echo "<tr bgcolor='$bgcolor'>";
                        echo "<tr>";
                        echo "<td>$startDateTime</td>";
                        //if ($trip->startCoordinate->address->label) {
                        if ($startAddress) {
                            echo "<td>" . $startAddress . "</td>";
                        } else {
                            echo "<td><span onclick='loadUrlModal(\"Addresse incomplete\", \"index.php?r=site/widgetloader&widget=IncompleteAddressWidget&coordinate_id=" . $trip->start_coordinate_id . "&light=1\");' style='cursor: pointer' data-toggle=\"tooltip\" data-placement=\"top\" title=\"Incomplete addresse. Click to fix it.\"> <i class='ti-help'></i><br>" . $startAddress . "</span></td>";
                        }
                        if ($stopAddress) { //if ($trip->stopCoordinate->address->label) {
                            echo "<td>" . $stopAddress . "</td>";
                        } else {
                            echo "<td><span onclick='loadUrlModal(\"Addresse incomplete\", \"index.php?r=site/widgetloader&widget=IncompleteAddressWidget&coordinate_id=" . $trip->stop_coordinate_id . "&light=1\");' style='cursor: pointer' data-toggle=\"tooltip\" data-placement=\"top\" title=\"Incomplete addresse. Click to fix it.\"> <i class='ti-help'></i><br>" . $stopAddress . "</span></td>";
                        }
                        echo "<td>$stopDateTime</td>";
                        echo "<td>$duration</td>";
                        echo "<td>$distance</td>";
                        echo "<td>$vehicle</td>";
                        echo "</tr>";
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php
}

$script = <<<JS

    function reload(limit, page) {
        const url = new URL(window.location.href);
        url.searchParams.set('limit',limit);
        url.searchParams.set('page',page);
        window.location.href = url.href;
    }

    $(document).ready(function() {   
        //
        // function onExportSuccess(data){
        //     console.log(data);
        // }
        //
        $('#export a').on('click', function(e){
            e.preventDefault();
            // $.get(
            //     'index.php?r=site/triptoexcel&tag=year&value='+(new Date().getUTCFullYear()),
            //     function(data) {
            //       console.log(data);
            //       data.blob()
            //     },
            //     'file'
            // );
            $.ajax({
                type:'GET',
                url:'index.php?r=site/triptoexcel&tag=year&value='+(new Date().getUTCFullYear()),
                data: {},
                dataType:'json'
            }).done(function(data){
                let a = $("<a>");
                a.attr("href",data.file);
                $("body").append(a);
                a.attr("download",data.filename);
                a[0].click();
                a.remove();
            });
//            window.location.href = 'index.php?r=site/triptoexcel&tag=year&value='+(new Date().getUTCFullYear());
        });
       
        $('#trip-table')
            .DataTable({
                "searching" : false,
                "paging":   false,
                "responsive": true,
                // "ordering": false,
                "info":     false
            });
        
        $('#trip-table-length').change(function () {
            reload(this.value, $('.page-item.active a').html());
        });
        
        $('.paginate_button a').click(function() {
            if (this.innerHTML === "Précédent") {
                reload($('#trip-table-length').val(), Number($('.page-item.active a').html()) - 1);             
            }
            else if (this.innerHTML === "Suivant") {
                reload($('#trip-table-length').val(),  Number($('.page-item.active a').html()) + 1);             
            }
            else {
                reload($('#trip-table-length').val(), this.innerHTML);             
            }
        });
    } );
   
JS;
$this->registerJs($script, View::POS_END);
