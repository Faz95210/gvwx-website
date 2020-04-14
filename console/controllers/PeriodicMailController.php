<?php

namespace console\controllers;

use common\controllers\MailController;
use Yii;
use yii\helpers\Url;
use yii\console\Controller;

/**
 * Test controller
 */
class PeriodicMailController extends Controller {

    public function actionWeekly() {
        $mail = new MailController("Rapport Hebdomadaire");
        $day = date('w');
        $period_start = strtotime('-' . $day . ' days');
        $period_end = strtotime('+' . (6 - $day) . ' days');
        $period = [
            'label' => 'Hebdomadaire',
            'from' => ($period_start),
            'to' => ($period_end),
        ];
        $mail->toAllUsers('weekly');
        return $this->sendPeriodicMail($mail, $period);
    }

    public function actionMonthly() {
        $mail = new MailController("Rapport Mensuel");
        $month = date('m');
        $period_start = strtotime("$month/01/" . date('Y') . ' 00:00:00');
        $period_end = strtotime($month . "/" . cal_days_in_month(CAL_GREGORIAN, $month, date("y")) . "/" . date("Y") . " 23:59:59");
        $period = [
            'label' => 'Mensuel',
            'from' => ($period_start),
            'to' => ($period_end),
        ];
        $mail->toAllUsers('monthly');
        return $this->sendPeriodicMail($mail, $period);
    }


    public function actionYearly() {
        $mail = new MailController("Rapport Annuel");
        $year = date('Y');
        $period_start = strtotime("01/01/$year");
        $period_end = strtotime("12/31/$year");
        $period = [
            'label' => 'Annuel',
            'from' => ($period_start),
            'to' => ($period_end),
        ];
        $mail->toAllUsers('yearly');
        return $this->sendPeriodicMail($mail, $period);
    }


    private function sendPeriodicMail(&$mail, $period) {
        $mail->write("
        <div class=\"container-fluid\">
            <div class=\"page-title-box\">
                <div class=\"row align-items-center\">
                    <div class=\"col-sm-12\">
                        <h3>Rapport " . $period['label'] . "</h3>
                    </div>
                </div>
        ");
        $mail->write("
            <div class=\"row align-items-center\">
                <div class=\"col-sm-12\">
                    <p>Bonjour, ##USER-givenname## ##USER-name##, voici le rapport " . $period['label'] . " de vos déplacements.</p>
                </div>
            </div>", true);
        $mail->write('##ARRAY-Trip->=:created_at:' . $period['from'] . ';<=:created_at:' . $period['to'] . '-=gmdate|"Y/m/d"|$start_date_time;Date Départ:startCoordinate.address.label;Addresse Départ:stopCoordinate.address.label;Addresse Destination:duration;Durée:distance;Distance:=sprintf|%s %s|$vehicle.brand|$vehicle.model;Vehicule##', true);
        $mail->write("</div>
        </div>");
//        $mail->write(TripsWidget::widget(['_mode'=>'light', '_filters' => 'currentYear']));
        return $mail->send();
    }
}