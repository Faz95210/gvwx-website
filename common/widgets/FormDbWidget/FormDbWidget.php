<?php

namespace common\widgets\FormDbWidget;

use common\models\Power;
use yii\base\Widget;

class FormDbWidget extends Widget {

    public $table;

    // insert | update
    public $mode = "insert";
    public $id = -1;

    public function init() {
        parent::init();
    }

    public function run() {
        FormDbWidgetAssets::register($this->getView());
        if ($this->table == null) {
            echo "Incorrect table";
            return;
        }
        $klass = "\\common\\models\\" . $this->table;
        if (!class_exists($klass)) {
            echo "Incorrect table";
            return;
        }
        $model = null;

        if ($this->mode === 'update' && $this->id !== -1) {
            $this->view->params['update'] = $this->id;
            $model = $klass::find()->where(['id' => $this->id])->one();
        } else {
            $model = new $klass();
        }
        $rules = $this->formatRules($model->rules());
        if ($rules === -1) {
            echo "Can't generate form";
            return;
        }
        $this->view->params['values'] = null;
        if ($this->mode === 'update' && $this->id !== -1) {
            $this->view->params['values'] = $this->getValuesForId($model, $rules);
            $rules['id'] = [];
        }

        $this->view->params['table'] = $this->table;
        $this->view->params['rules'] = $rules;
        return $this->render('_formDb', []);
    }

    private function formatRules($rules) {
        $return_value = [];

        foreach ($rules as $ruleByFields) {
            $rule = $ruleByFields[1];
            foreach ($ruleByFields[0] as $field) {
                if ($field === 'status' || $field === 'user_id' || $field === 'created_at' || $field === 'updated_at') continue;
                $return_value[$field][] = $rule;

                if (strpos($field, "_id")) {
                    $className = '\\common\\models\\' . str_replace("_id", "", $field);
                    if (class_exists($className)) {
                        $ids = $className::find()->all();
                        $this->view->params[$field] = $ids;
                    } else if ($rule == "exist") {
                        return -1;
                    }
                }
            }
        }

        return $return_value;
    }

    private function getValuesForId($model, array $rules) {
        $return_value = ['id' => $this->id];
        foreach (array_keys($rules) as $field) {
            $return_value[$field] = $model->$field;
        }

        return $return_value;
    }

}