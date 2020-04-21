<?php


namespace console\controllers;


use common\controllers\MailController;
use common\models\Events;
use common\models\UserEvent;
use yii\console\Controller;

class OddBehaviorController extends Controller {

    public function actionChecklastevents() {
        $current_time = time();
        $events = Events::find()->all();
        foreach ($events as $event) {
            $userEvents = UserEvent::find()->where(['event_id' => $event->id])->all();
            $mail = new MailController("Pas d'export récent");
            $mail->doHeader([dirname(__FILE__) . "/../../frontend/web/css/veltrix/bootstrap.css"]);
            $mail->setTemplate(dirname(__FILE__) . '/../../frontend/web/templates/template_' . $event->label . '.html');
            foreach ($userEvents as $userEvent) {
                if (strtotime($userEvent->event_time) < strtotime('- ' . $event->time_before_warning)) {
                    $mail->addUser($userEvent->user_id, ['EVENT_TIME' => date('d-m-Y', $userEvent->event_time)]);
                }
            }
            echo $mail->send();
        }
    }
}