<?php namespace ikar\tripparser;

use common\models\Address;
use common\models\RawCoordinates;
use Yii;
use common\models\Trip;
use DOMDocument;
use DateTime;
use DateInterval;
use vlaim\fileupload\FileUpload;


class TripParserException extends \Exception
{

}

class TripParser
{
    private $_data;
    private $_length;
    private $_pos;
    private $_infos;
    private $_locations;
    private $_snappedLocations;
    private $_valid;
    private $_kml;
    private $_kmlPath;

    const TRIP_BIN  = 0xFFFFFFFF;
    const DATE_BIN  = 0xFFFFFFFE;
    const TIME_BIN  = 0xFFFFFFFD;
    const ID_BIN    = 0xFFFFFFFC;
    const PERSO_BIN = 0xFFFFFFFB;
    const PRO_BIN   = 0xFFFFFFFA;

    public function __construct($data = [])
    {
        $this->_data = $data;
        $this->_length = strlen($data);
        $this->_pos = 0;

        $this->_infos = array(
            "startDate" => "",
            "startTime" => "",
            "stopDate"  => "",
            "stopTime"  => "",
            "startLat"  => 0,
            "startLng"  => 0,
            "stopLat"   => 0,
            "stopLng"   => 0,
            "mode"      => "",
            "duration"  => 0,
            "distance"  => 0
        );

        $this->_locations = array();
        $this->_snappedLocations = array();
        $this->_valid = false;
    }

    public function getDuration() {
        return $this->_infos["duration"];
    }

    public function getDistance() {
        return $this->_infos["distance"];
    }

    public function getStartDateTime() {
        $start = DateTime::createFromFormat('Y/n/j H:i', $this->_infos["startDate"]." ".$this->_infos["startTime"]);
        return $start->getTimestamp();
    }

    public function getStopDateTime() {
        $stop = DateTime::createFromFormat('Y/n/j H:i', $this->_infos["stopDate"]." ".$this->_infos["stopTime"]);
        return $stop->getTimestamp();
    }

    public function getStartCoordinates()
    {
        return array("lat" => $this->_infos["startLat"], "lng" => $this->_infos["startLng"]);
    }

    public function getStopCoordinates()
    {
        return array("lat" => $this->_infos["stopLat"], "lng" => $this->_infos["stopLng"]);
    }

    public function getPath()
    {
        return $this->_kmlPath;
    }

    public function isValid() {
        return $this->_valid;
    }

    private function read32b() {
        if (($this->_pos+4) <= $this->_length) {
            $tmp = substr($this->_data, $this->_pos, 4); // Extrat 32bits
            $tmp = unpack("Nval", $tmp);
            $this->_pos += 4;
            return $tmp["val"];
        } else {
            throw new TripParserException("Read out of buffer size");
        }
    }

    private function read16b() {
        if (($this->_pos+2) <= $this->_length) {
            $tmp = substr($this->_data, $this->_pos, 2); // Extrat 16bits
            $tmp = unpack("nval", $tmp);
            $this->_pos += 2;
            return $tmp["val"];
        } else {
            throw new TripParserException("Read out of buffer size");
        }
    }

    private function read8b() {
        if (($this->_pos) <= $this->_length) {
            $tmp = substr($this->_data, $this->_pos, 1); // Extrat 8bits
            $tmp = unpack("Cval", $tmp);
            $this->_pos += 1;
            return $tmp["val"];
        } else {
            throw new TripParserException("Read out of buffer size");
        }
    }

    private function readDate() {
        if (($this->_pos+4) <= $this->_length) {
            $tmp = substr($this->_data, $this->_pos, 4); // Extrat 32bits
            $this->_pos += 4;
            return unpack('nyy/Cm/Cd', $tmp);
        } else {
            throw new TripParserException("Read out of buffer size");
        }
    }

    private function readTime() {
        if (($this->_pos+2) <= $this->_length) {
            $tmp = substr($this->_data, $this->_pos, 2); // Extrat 16bits
            $this->_pos += 2;
            return unpack('Ch/Cm', $tmp);
        } else {
            throw new TripParserException("Read out of buffer size");
        }
    }

    private function signedint32($value) {
        $i = (int)$value;
        if (PHP_INT_SIZE > 4)   // e.g. php 64bit
            if($i & 0x80000000) // is negative
                return $i - 0x100000000;
        return $i;
    }

    private function readLoc() {
        if (($this->_pos+4) <= $this->_length) {
            $tmp = substr($this->_data, $this->_pos, 4); // Extrat 32bits
            $tmp = unpack("Nval", $tmp);
            $this->_pos += 4;
            return $this->signedint32($tmp["val"]) / 1000000;
        } else {
            throw new TripParserException("Read out of buffer size");
        }
    }

    private function degreesToRadians($degrees) {
        return $degrees * pi() / 180;
    }

    private function distanceInKmBetweenEarthCoordinates($lat1, $lon1, $lat2, $lon2) {
        $earthRadiusKm = 6371;

        $dLat = $this->degreesToRadians($lat2-$lat1);
        $dLon = $this->degreesToRadians($lon2-$lon1);

        $lat1 = $this->degreesToRadians($lat1);
        $lat2 = $this->degreesToRadians($lat2);

        $a = sin($dLat/2) * sin($dLat/2) +
            sin($dLon/2) * sin($dLon/2) * cos($lat1) * cos($lat2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        return $earthRadiusKm * $c;
    }

    private function validate() {
        if ($this->_infos["startDate"] == "") { return false; }
        if ($this->_infos["startTime"] == "") { return false; }
        if ($this->_infos["stopDate"]  == "") { return false; }
        if ($this->_infos["stopTime"]  == "") { return false; }
        if ($this->_infos["startLat"]  == 0)  { return false; }
        if ($this->_infos["startLng"]  == 0)  { return false; }
        if ($this->_infos["stopLat"]   == 0)  { return false; }
        if ($this->_infos["stopLng"]   == 0)  { return false; }
        if ($this->_infos["mode"]      == "") { return false; }
        if ($this->_infos["duration"]  == 0)  { return false; }
        //if ($this->_infos["distance"]  == 0)  { return false; }

        return true;
    }

    public function parse() {
        $prevLat = 0;
        $prevLng = 0;
        $lat = 0;
        $lng = 0;

        while (($this->_pos+4) <= $this->_length) {
            $tmp = $this->read32b();

            if ($tmp == self::TRIP_BIN) {
                // New trip
            } else if ($tmp == self::DATE_BIN) {
                if (($this->_pos+4) <= $this->_length) {
                    $date = $this->readDate();

                    $tmp = DateTime::createFromFormat('Y/n/j', $date["yy"]."/".$date["m"]."/".$date["d"]);
                    if ($tmp) {
                        if ($this->_infos["startDate"] == "") {
                            $this->_infos["startDate"] = $date["yy"]."/".$date["m"]."/".$date["d"];
                        } else {
                            $this->_infos["stopDate"] = $date["yy"]."/".$date["m"]."/".$date["d"];
                        }
                    }
                } else {
                    break;
                }
            } else if ($tmp == self::TIME_BIN) {
                if (($this->_pos+2) <= $this->_length) {
                    $time = $this->readTime();

                    $tmp = DateTime::createFromFormat('H:i', $time["h"].":".($time["m"] < 10 ? "0" : "").$time["m"]);
                    if ($tmp) {
                        if ($this->_infos["startTime"] == "") {
                            $this->_infos["startTime"] = $time["h"].":".($time["m"] < 10 ? "0" : "").$time["m"];
                        } else {
                            $this->_infos["stopTime"] = $time["h"].":".($time["m"] < 10 ? "0" : "").$time["m"];
                        }
                    }
                } else {
                    break;
                }
            } else if ($tmp == self::PERSO_BIN) {
                // Perso mode
                $this->_infos["mode"] = "perso";
            } else if ($tmp == self::PRO_BIN) {
                // Pro mode
                $this->_infos["mode"] = "pro";
            } else if ($tmp == self::ID_BIN) {
                // Id
            } else {
                $lng = $this->signedint32($tmp) / 1000000;
                if ($this->_infos["startLng"] == 0) {
                    $this->_infos["startLng"] = $lng;
                } else {
                    $this->_infos["stopLng"] = $lng;
                }
                if (($this->_pos+4) <= $this->_length) {
                    $lat = $this->readLoc();

                    if ($this->_infos["startLat"] == 0) {
                        $this->_infos["startLat"] = $lat;
                    } else {
                        $this->_infos["stopLat"] = $lat;
                    }

                    if (($prevLat != 0) && ($prevLng != 0)) {
                        $this->_infos["distance"] += $this->distanceInKmBetweenEarthCoordinates($prevLat, $prevLng, $lat, $lng);
                    }

                    array_push($this->_locations, array("lat" => $lat, "lng" => $lng));

                    $prevLat = $lat;
                    $prevLng = $lng;
                } else {
                    break;
                }
            }
        }

        if (count($this->_locations) < 5) {
            echo "Not enough locations points";
            $this->_valid = false;
            return ;
        }

        $this->_infos["distance"] = round($this->_infos["distance"], 3);

        if ($this->_infos["stopTime"] == "") {
            echo $this->_infos["startDate"]." ".$this->_infos["startTime"]."\n";
            $time = DateTime::createFromFormat('Y/n/j H:i', $this->_infos["startDate"]." ".$this->_infos["startTime"]);
            $time->add(new DateInterval('PT1M'));
            $this->_infos["stopTime"] = $time->format('H:i');
            $this->_infos["stopDate"] = "";
        }

        if ($this->_infos["stopDate"] == "") {
            echo $this->_infos["startDate"]." ".$this->_infos["startTime"]."\n";
            $date = DateTime::createFromFormat('Y/n/j H:i', $this->_infos["startDate"]." ".$this->_infos["startTime"]);
            $date->add(new DateInterval('PT1M'));
            $this->_infos["stopDate"] = $date->format('Y/n/j');
        }


        try {
            $startDate = DateTime::createFromFormat('Y/n/j H:i', $this->_infos["startDate"]." ".$this->_infos["startTime"]);
            $stopDate = DateTime::createFromFormat('Y/n/j H:i', $this->_infos["stopDate"]." ".$this->_infos["stopTime"]);

            $interval = $startDate->diff($stopDate);
            $hours   = $interval->format('%h');
            $minutes = $interval->format('%i');
            $days = $interval->format('%d');

            $this->_infos["duration"] = ($days * 1440 + $hours * 60 + $minutes);
        } catch(Exception $e) {

        }

        $this->_valid = $this->validate();
        if (!$this->_valid) {
            var_dump($this);
        }
    }

    public function process() {
        $this->filterLocations();
        $this->calculDistance();
        $this->snapToRoad();
    }

    private function filterLocations() {
        for ($i=1; $i<(count($this->_locations)-1); $i++) {
            $dist1 = $this->distanceInKmBetweenEarthCoordinates(
                $this->_locations[$i-1]["lat"],
                $this->_locations[$i-1]["lng"],
                $this->_locations[$i]["lat"],
                $this->_locations[$i]["lng"]);
            $dist2 = $this->distanceInKmBetweenEarthCoordinates(
                $this->_locations[$i-1]["lat"],
                $this->_locations[$i-1]["lng"],
                $this->_locations[$i]["lat"],
                $this->_locations[$i]["lng"]);

            if (($dist1 > 1) && ($dist2 > 1)) {
                // Middle value is wrong
                echo "wrong value1: \n";
                var_dump($this->_locations[$i-1]);
                var_dump($this->_locations[$i]);
                var_dump($this->_locations[$i+1]);
                $this->_locations[$i]["lat"] = $this->_locations[$i-1]["lat"];
                $this->_locations[$i]["lng"] = $this->_locations[$i-1]["lng"];
            } else if ($dist1 > 1) {
                // First value is wrong
                echo "wrong value2: \n";
                var_dump($this->_locations[$i-1]);
                var_dump($this->_locations[$i]);
                $this->_locations[$i-1]["lat"] = $this->_locations[$i]["lat"];
                $this->_locations[$i-1]["lng"] = $this->_locations[$i]["lng"];
            } else if ($dist2 > 1) {
                // Second value is wrong
                echo "wrong value3: \n";
                var_dump($this->_locations[$i]);
                var_dump($this->_locations[$i+1]);
                $this->_locations[$i+1]["lat"] = $this->_locations[$i]["lat"];
                $this->_locations[$i+1]["lng"] = $this->_locations[$i]["lng"];
            }
        }
    }

    private function calculDistance() {
        $prevLat = 0;
        $prevLng = 0;
        $this->_infos["distance"] = 0;

        foreach ($this->_locations as $loc) {
            if (($prevLat != 0) && ($prevLng != 0)) {
                $this->_infos["distance"] += $this->distanceInKmBetweenEarthCoordinates(
                    $prevLat,
                    $prevLng,
                    $loc["lat"],
                    $loc["lng"]);
            }

            $prevLat = $loc["lat"];
            $prevLng = $loc["lng"];
        }

        $this->_infos["distance"] = round($this->_infos["distance"], 3);
    }

    private function getAddressDetail($latlng) {
        $url = Yii::$app->params['reverseGeocodeAddress'];

        $url = str_replace('##LAT##', $latlng['lat'], $url);
        $url = str_replace('##LNG##', $latlng['lng'], $url);
        $url = str_replace('##EMAIL##', Yii::$app->params['adminEmail'], $url);

        $res = json_decode(file_get_contents($url, true));
        return $res;
    }

    private function snapToRoad() {
        $url = "http://localhost:5000/nearest/v1/driving/";

        $i = 0;
        foreach ($this->_locations as $latlng){
            if (!$latlng)
                continue;
            $res = json_decode(file_get_contents($url . $latlng['lng'].','.$latlng['lat']), true);
            if ($res && $res['code'] === 'Ok'){
                $this->_snappedLocations[] = [
                    'lng' => $res['waypoints'][0]['location'][0],
                    'lat' => $res['waypoints'][0]['location'][1],
                    'name' => $res['waypoints'][0]['name'],
                ];
            } else  {
//                $return_value[] = 'ko';
            }
            if ($i === 0 || $i === count($this->_locations) - 1) {
                try {
                    $address = $this->getAddressDetail($this->_snappedLocations[$i]);
                    $this->_snappedLocations[$i]['details'] = $address;
                } catch (\ErrorException $ex) {
                    $this->_snappedLocations[$i]['details'] = [];
                }
            }
            $i++;
        }
        echo "Could snap " . count($this->_snappedLocations) . " locations out of " . count($this->_locations).PHP_EOL;
    }

    public function generateKml() {
        $locationSource = $this->_snappedLocations;
        //$locationSource = $this->_snappedLocations;
        $this->_kml = new DOMDocument();
        $this->_kml->load('assets/kml_model.kml');

        $coordinates = $this->_kml->getElementsByTagName('coordinates')->item(0);
        foreach ($locationSource as $loc) {
            $coordinates->textContent .= $loc["lng"].','.$loc["lat"].PHP_EOL;
        }
    }


    public function saveKmlToS3($path, $params) {

        $msg = $this->_kml->saveXML();
        echo $msg."\n";

        try {
            $uploader = new FileUpload(FileUpload::S_S3, $params);

            $uploader->setUploadFolder($path);
            $uploader->uploadFromString("trip.bin", $msg);
        } catch (\Throwable $e) {
            throw new TripParserException("Can't save to S3");
        }

        $this->_kmlPath = $path."trip.bin";
    }

}