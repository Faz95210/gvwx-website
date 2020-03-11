<?php

use yii\widgets\ActiveForm;

if ($this->params['light']) {
    ?>

    <div class='row'>
        <div class='col-12'>
            <div class='card'>
                <div class='card-body'>
                    <div class='row'>
                        <div class='col-12'>
                            <div class='card'>
                                <div class='card-body'>
                                    <h3>Anciennes adresses</h3>
                                    <table id='datatable-buttons' class='table table-bordered dt-responsive nowrap'
                                           style='border-collapse: collapse; border-spacing: 0; width: 100%;'>

                                        <thead>
                                        <tr>

                                            <th><?php echo \Yii::t('frontend', 'LABEL'); ?></th>
                                            <th><?php echo \Yii::t('frontend', 'ADDRESSE'); ?></th>
                                            <th><?php echo \Yii::t('frontend', 'VILLE'); ?></th>
                                            <th><?php echo \Yii::t('frontend', 'CODE POSTAL'); ?></th>
                                            <th><?php echo \Yii::t('frontend', 'PAYS'); ?></th>
                                            <th><?php echo \Yii::t('frontend', 'DISTANCE'); ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $index = 0;
                                        foreach ($this->params['closest_addresses'] as $address) {
                                            echo "<tr id='row_$index'>";
                                            echo "<td id='td-label-$index'>" . $address['label'] . '</td>';
                                            echo "<td id='td-address-$index'>" . $address['address'] . '</tdtd-label-1>';
                                            echo "<td id='td-city-$index'>" . $address['city'] . '</td>';
                                            echo "<td id='td-cp-$index'>" . $address['postcode'] . '</td>';
                                            echo "<td id='td-country-$index'>" . $address['country'] . '</td>';
                                            echo "<td id='td-distance-$index'>" . $address['distance'] . '</td>';
                                            echo "</tr>";
                                            $index++;
                                        }
                                        ?>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                    <h3>Nouvelle addresse</h3>
                    <?php $form = ActiveForm::begin(['action' => ['address/completeaddress'], 'id' => 'completeAddresse']); ?>
                    <?php
                    echo '<label>Label</label><input required type="text" name="address_label" placeholder="LABEL" id="address_label"/>';
                    echo '<label>Adresse</label><input required type="text" name="address_address" value="' . $this->params['coordinate_address'] . '" id="address_address"/>';
                    echo '<label>Ville</label><input required type="text" name="address_city" value="' . $this->params['coordinate_city'] . '" id="address_city">';
                    echo '<label>Code Postal</label><input required type="number" name="address_cp" value="' . $this->params['coordinate_CP'] . '" id="address_cp">';
                    echo '<label>Pays</label><input required type="text" name="address_country" value="' . $this->params['coordinate_country'] . '" id="address_country">';
                    echo '<input type="hidden" name="coordinate_id" value="' . $this->params['coordinate_id'] . '" id="address_country">';
                    ?>
                    <button class="btn btn-primary">Valider</button>
                    <?php ActiveForm::end(); ?>

                </div>
            </div>
        </div>
    </div>
    <?php
    if ($this->params['closest_addresses']) {
        ?>


        <?php
    }
    ?>
    <script>
        const rows = document.querySelectorAll("[id^='row_']");
        for (let i = 0; i < rows.length; i++) {
            rows[i].addEventListener('click', function (e) {
                document.getElementById('address_label').value = document.getElementById('td-label-' + i).innerHTML;
                document.getElementById('address_city').value = document.getElementById('td-city-' + i).innerHTML;
                document.getElementById('address_address').value = document.getElementById('td-address-' + i).innerHTML;
                document.getElementById('address_cp').value = document.getElementById('td-cp-' + i).innerHTML;
                document.getElementById('address_country').value = document.getElementById('td-country-' + i).innerHTML;
            });
        }

        function onSuccess(data) {
            console.log("great sucess", data["error"]);
            if (data["error"] == false) {
                location.reload(true);
            }
        }
    </script>
    <?php
}
?>