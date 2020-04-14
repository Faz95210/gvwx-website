<?php


namespace common\controllers;


use common\models\ScaleIK;
use common\models\Trip;
use common\models\User;
use PHPExcel_IOFactory;
use PHPExcel_Style_Border;
use Yii;

class ExcelController {

    private $template_path;
    private $reference = [];
    private $objPHPExcel;
    private $sheet;
    public $xlsData;

    public function __construct($template_path, $filters) {
        $this->objPHPExcel = PHPExcel_IOFactory::load($template_path);
        $this->sheet = $this->objPHPExcel->getActiveSheet();
    }

    public function setReference($reference) {
        $this->reference = $reference;
    }

    private function setCell($cell, $content, $type) {
        $cell->setValue($content);
        switch ($type) {
            case "TOTAL" :
                $cell->getStyle()->applyFromArray([
                    'font' => [
                        'bold' => true
                    ],
                    'borders' => [
                        'allborders' => [
                            'style' => PHPExcel_Style_Border::BORDER_THICK,
                            'color' => array(
                                'rgb' => '000000'
                            )
                        ]
                    ]
                ]);
            case "INFO" :
                $cell->getStyle()->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);
                break;
            case "DATA" :
                $cell->getStyle()->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array(
                                'rgb' => '000000'
                            )
                        ],
                        'left' => [
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array(
                                'rgb' => '000000'
                            )
                        ],
                        'right' => [
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array(
                                'rgb' => '000000'
                            )
                        ]
                    ]
                ]);
                break;
        }
    }

    public function doInfoHeader(&$filters) {
        $user = User::find()->where(['id' => Yii::$app->user->id])->one();
        $this->setCell($this->sheet->getCell($this->reference['name']), $user->name, 'INFO');
        $this->setCell($this->sheet->getCell($this->reference['firstname']), $user->givenname, 'INFO');

        switch ($filters['tag']) {
            case "year":
                $this->setCell($this->sheet->getCell($this->reference['from']), '01/01/' . date('y'), 'INFO');
                $this->setCell($this->sheet->getCell($this->reference['to']), '31/12/' . date('y'), 'INFO');
                break;
            case "month":
                $this->setCell($this->sheet->getCell($this->reference['from']), '01/' . date('m') . '/' . date('y'), 'INFO');
                $this->setCell($this->sheet->getCell($this->reference['to']), cal_days_in_month(CAL_GREGORIAN, date('m'), date("y")) . '/' . date('m') . '/' . date('y'), 'INFO');
                break;
        }
    }

    private function sortByVehicle($raw_trips) {
        $return_value = [];
        foreach ($raw_trips as $raw_trip) {
            if ($raw_trip->vehicle) {
                $return_value[$raw_trip->vehicle->brand . ' ' . $raw_trip->vehicle->model][] = $raw_trip;
            } else {
                $return_value["Vehicule Non Renseigné"][] = $raw_trip;
            }
        }
        return $return_value;
    }

    private function doHeader($index) {
        $this->setCell($this->sheet->getCell($this->reference['date'] . ($this->reference['startData'] + $index)), 'Date', 'TOTAL');
        $this->setCell($this->sheet->getCell($this->reference['depart'] . ($this->reference['startData'] + $index)), 'Depart', 'TOTAL');
        $this->setCell($this->sheet->getCell($this->reference['arrivee'] . ($this->reference['startData'] + $index)), 'Arrivee', 'TOTAL');
        $this->setCell($this->sheet->getCell($this->reference['duree'] . ($this->reference['startData'] + $index)), 'Duree', 'TOTAL');
        $this->setCell($this->sheet->getCell($this->reference['distance'] . ($this->reference['startData'] + $index)), 'Distance', 'TOTAL');
//        $this->setCell($this->sheet->getCell($this->reference['vehicle'] . ($this->reference['startData'] + $index)), 'Vehicle', 'TOTAL');
        $this->setCell($this->sheet->getCell($this->reference['commentaire'] . ($this->reference['startData'] + $index)), 'Commentaire', 'TOTAL');
    }

    public function write() {
        $objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel2007');
        ob_start();
        $objWriter->save('php://output');
        $this->xlsData = ob_get_contents();
        ob_end_clean();
    }

    public function fillTrips(&$filters) {
        $raw_trips = Trip::getTripsFilteredByDate($filters)->all();

        $trips_by_vehicle = $this->sortByVehicle($raw_trips);

        $start = $this->reference['startData'];
        $index = 1;
        foreach ($trips_by_vehicle as $vehicle_name => $trips) {
            $table_start_index = $index;
            $this->setCell($this->sheet->getCell('A' . ($this->reference['startData'] + $index)), $vehicle_name, 'INFO');
            $scaleIk = null;
            if ($vehicle_name !== 'Vehicule Non Renseigné') {
                $scaleIk = ScaleIK::find()->where(['power_id' => $trips[0]->vehicle->power_id])->one();
            }
            $index += 1;
            $this->doHeader($index);
            $index += 1;
            foreach ($trips as $raw_trip) {
                $this->setCell($this->sheet->getCell($this->reference['date'] . ($this->reference['startData'] + $index)), gmdate("Y-m-d", $raw_trip->start_date_time), 'DATA');
                $this->setCell($this->sheet->getCell($this->reference['depart'] . ($this->reference['startData'] + $index)), isset($raw_trip->startCoordinate->address) ? $raw_trip->startCoordinate->address->label : 'incomplet', 'DATA');
                $this->setCell($this->sheet->getCell($this->reference['arrivee'] . ($this->reference['startData'] + $index)), isset($raw_trip->stopCoordinate->address) ? $raw_trip->stopCoordinate->address->label : 'incomplet', 'DATA');
                $this->setCell($this->sheet->getCell($this->reference['duree'] . ($this->reference['startData'] + $index)), $raw_trip->duration, 'DATA');
                $this->setCell($this->sheet->getCell($this->reference['distance'] . ($this->reference['startData'] + $index)), $raw_trip->distance, 'DATA');
//                $this->setCell($sheet->getCell($reference['vehicle'].($reference['startData'] + $index)), $raw_trip->vehicle ? $raw_trip->vehicle->brand . " " . $raw_trip->vehicle->model : 'non renseigné', 'DATA');
                $this->setCell($this->sheet->getCell($this->reference['commentaire'] . ($this->reference['startData'] + $index)), $raw_trip->comments, 'DATA');
                $index++;
            }
            $this->setCell($this->sheet->getCell($this->reference['date'] . ($this->reference['startData'] + $index)), "TOTAUX", 'TOTAL');
            $this->setCell($this->sheet->getCell($this->reference['depart'] . ($this->reference['startData'] + $index)), "", 'TOTAL');
            $this->setCell($this->sheet->getCell($this->reference['arrivee'] . ($this->reference['startData'] + $index)), "", 'TOTAL');
//            $this->setCell($sheet->getCell($reference['vehicle'] . ($reference['startData'] + $index)), "", 'TOTAL');
            $this->setCell($this->sheet->getCell($this->reference['duree'] . ($this->reference['startData'] + $index)),
                "=SUM(" . $this->reference['duree'] . ($this->reference['startData'] + $table_start_index) . ':' . $this->reference['duree'] . ($this->reference['startData'] + $index - 1) . ') & " min"', 'TOTAL');
            $this->setCell($this->sheet->getCell($this->reference['distance'] . ($this->reference['startData'] + $index)),
                "=SUM(" . $this->reference['distance'] . ($this->reference['startData'] + $table_start_index) . ':' . $this->reference['distance'] . ($this->reference['startData'] + $index - 1) . ') & " KM"', 'TOTAL');
            $formula = '';
            if ($scaleIk) {
                $totalDistance = str_replace(' KM', '', $this->sheet->getCell($this->reference['distance'] . ($this->reference['startData'] + $index))->getCalculatedValue());
                if ($totalDistance < 5000) {
                    $formula = "=((SUBSTITUTE(" . $this->reference['distance'] . ($this->reference['startData'] + $index) . ",\" KM\", \"\",1) * " . $scaleIk->coeffBellow5k . ") + " . $scaleIk->extraBellow5k . ")";
                } else if ($totalDistance < 20000) {
                    $formula = "=((SUBSTITUTE(" . $this->reference['distance'] . ($this->reference['startData'] + $index) . ",\" KM\", \"\",1) * " . $scaleIk->coeffBellow20k . ") + " . $scaleIk->extraBellow20k . ")";
                } else {
                    $formula = "=((SUBSTITUTE(" . $this->reference['distance'] . ($this->reference['startData'] + $index) . ",\" KM\", \"\",1) * " . $scaleIk->coeffAbove20k . ") + " . $scaleIk->extraAbove5k . ")";
                }
                $formula .= ' & " EUR"';
            }
            $this->setCell($this->sheet->getCell($this->reference['commentaire'] . ($this->reference['startData'] + $index)), $formula, 'TOTAL');

            $index += 2;
        }
//        foreach($raw_trips as $raw_trip){
//            $this->setCell($sheet->getCell($reference['date'].($reference['startData'] + $index)), gmdate("Y-m-d", $raw_trip->start_date_time), 'DATA');
//            $this->setCell($sheet->getCell($reference['depart'].($reference['startData'] + $index)), isset($raw_trip->startCoordinate->address) ? $raw_trip->startCoordinate->address->label : 'incomplet', 'DATA');
//            $this->setCell($sheet->getCell($reference['arrivee'].($reference['startData'] + $index)), isset($raw_trip->stopCoordinate->address) ? $raw_trip->stopCoordinate->address->label : 'incomplet', 'DATA');
//            $this->setCell($sheet->getCell($reference['duree'].($reference['startData'] + $index)), $raw_trip->duration, 'DATA');
//            $this->setCell($sheet->getCell($reference['distance'].($reference['startData'] + $index)), $raw_trip->distance, 'DATA');
//            $this->setCell($sheet->getCell($reference['vehicle'].($reference['startData'] + $index)), $raw_trip->vehicle ? $raw_trip->vehicle->brand . " " . $raw_trip->vehicle->model : 'non renseigné', 'DATA');
//            $this->setCell($sheet->getCell($reference['commentaire'].($reference['startData'] + $index)), $raw_trip->comments, 'DATA');
//            $index++;
//        }
//        $index++;
//
//        $this->setCell($sheet->getCell($reference['date'].($reference['startData'] + $index)), "TOTAUX", 'TOTAL');
//        $this->setCell($sheet->getCell($reference['depart'].($reference['startData'] + $index)), "", 'TOTAL');
//        $this->setCell($sheet->getCell($reference['arrivee'].($reference['startData'] + $index)), "", 'TOTAL');
//        $this->setCell($sheet->getCell($reference['vehicle'].($reference['startData'] + $index)), "", 'TOTAL');
//        $this->setCell($sheet->getCell($reference['duree'].($reference['startData'] + $index)),
//            "=SUM(" .$reference['duree'].$reference['startData'].':'.$reference['duree'].($reference['startData'] + $index -1).') & " min"', 'TOTAL');
//        $this->setCell($sheet->getCell($reference['distance'].($reference['startData'] + $index)),
//            "=SUM(" .$reference['distance'].$reference['startData'].':'.$reference['distance'].($reference['startData'] + $index -1).') & " KM"', 'TOTAL');
//        $this->setCell($sheet->getCell($reference['commentaire'].($reference['startData'] + $index)), "", 'TOTAL');
////        Yii::$app->response->setDownloadHeaders($filename);

    }
}