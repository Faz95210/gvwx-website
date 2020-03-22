<?php

namespace common\widgets\SaleStepWidget;

use common\models\Address;
use common\models\Client;
use common\models\Coordinate;
use common\models\Item;
use common\models\Sale;
use common\models\SaleStep;
use yii\base\Widget;

class SaleStepWidget extends Widget {

    public $stepId = -1;
    public $saleId = -1;
    private $newOne;

    public function init() {
        parent::init();
    }

    public function run() {
        // Register AssetBundle

        if ($this->stepId != -1) {
            $step = SaleStep::findOne(['id' => $this->stepId]);
            $step->getSale();
            $step->getItem();
            $step->getClient();
            $this->view->params['step'] = $step;
        } else {
            $this->view->params['step'] = null;
        }
        $this->view->params['saleId'] = $this->saleId;
        $this->view->params['items'] = Item::findAll(['user_id' => \Yii::$app->user->getId()]);
        $this->view->params['clients'] = Client::findAll(['user_id' => \Yii::$app->user->getId()]);

        SaleStepWidgetAssets::register($this->getView());
        return $this->render('_saleStep');
    }
}