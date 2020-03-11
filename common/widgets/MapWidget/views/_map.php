<?php

use common\models\Coordinate;
use yii\web\View;
use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;

if (isset($this->params['error'])) {
    ?>
    <script>alert(<?php echo json_encode($this->params['error']); ?>)</script>
    <?php
}
?>
    <style>
        .map {
            height: 400px;
            width: 100%;
        }
    </style>

    <div id="mapId" style="height: 700px"
         class="leaflet-container leaflet-safari leaflet-fade-anim leaflet-grab leaflet-touch-drag">
        <div class="leaflet-bottom leaflet-left">
            <!--            <button  class="leaflet-control btn btn-primary" onclick="previous()" ><i class="ti-back-left"></i></button>-->
        </div>
    </div>
    <!--    <div id="olMap" class="map"></div>-->

    <!--<button class=" btn btn-primary" onclick="selectKML()">Load KML</button>-->

<?php

if ($this->params['job'] === 'kml') {
    ?>
    <input id='fileChooser' type="file" multiple onchange="parseKML(this.files)">
    <?php
}

$script = "";
/*
 * START KML
 */
if ($this->params['job'] === 'kml' && $this->params['btoa']) {
    $script = <<<JS
  let s3KML = '##S3KML##';
  handleKMLContent(decodeURIComponent(s3KML.replace(/\+/g, ' ')));
  s3KML = '';
JS;
    $script = str_replace("##S3KML##", urlencode($this->params["kml"]), $script);
    // $script = str_replace("##RESOURCEPATH##",  $this->params["bundle"]->sourcePath, $script);
} /*
 * END KML
 * START TRACKING
 */
else if ($this->params['job'] === 'tracking') {
    $lastlatlng = null;
    $rtTracking = array();
    for ($i = 0; $i < count($this->params['tracking']); $i++) {
        $tracking = $this->params['tracking'][$i];
        $rtTracking[] = [
            'id' => $tracking->id,
            'lat' => $tracking->lat,
            'lng' => $tracking->lng,
            'device_id' => $tracking->device_id,
            'timestamp' => $tracking->updated_at,
        ];
    }
    $script .= <<<JS
    initTrackingMap(##TRACKINGS##, '##BTOA##', '##LAST_TRACKING_ID##');
JS;
    $script = str_replace("##LAST_TRACKING_ID##", $this->params['last_tracking'], $script);
    $script = str_replace("##TRACKINGS##", json_encode($rtTracking), $script);
    $script = str_replace("##BTOA##", $this->params["btoa"], $script);
}
/*
 * STOP KML
 */
$this->registerJs($script, View::POS_END);
