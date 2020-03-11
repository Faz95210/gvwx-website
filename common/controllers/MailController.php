<?php

namespace common\controllers;


use common\models\User;
use common\models\UserSettings;
use Yii;

class MailController {

    private $mail;
    private $body = [];
    private $header = '';
    private $template = null;
    private $users = [];
    private $subject = "";

    public function __construct($title) {
        $this->mail = Yii::$app->mailer->compose();
        $this->mail->setSubject($title);
        $this->mail->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' website']);
    }

    public function setTemplate($template_path, $params = []) {
        $this->template = file_get_contents($template_path);
        if ($this->template) {
            foreach ($params as $needle => $haystring) {
                $this->template = str_replace('###' . $needle . '###', $haystring, $this->template);
            }
        }
    }

    public function toAllUsers($period = null) {
        if ($period == null) {
            $usersByPeriod = $this->users = User::find()->all();
        } else {
            $usersByPeriod = UserSettings::find()->where(['mail_frequency' => $period])->all();
        }
        foreach ($usersByPeriod as $userByPeriod) {
            $this->users[] = [$userByPeriod->user, []];
        }
    }

    public function setUsers($user_ids) {
        foreach ($user_ids as $user_id) {
            $user = $this->getUser($user_id);
            if ($user != null) {
                $this->users[] = $user;
            }
        }
    }

    public function addUser($user_id, $params) {
        $user = $this->getUser($user_id);
        if ($user != null) {
            $this->users[] = [$user, $params];
        }
    }

    private function getUser($user_id) {
        return User::find()->where(['id' => $user_id])->one();
    }

    public function write($text, $needTreatments = false) {
        $this->body[] = [
            'text' => $text,
            'treatment' => $needTreatments];
    }

    public function send() {
        foreach ($this->users as $user) {
            $this->mail->setHtmlBody($this->composeMessage($user));
            $this->mail->setTo($user[0]->email);
            $this->mail->send();
//            return $this->composeMessage($user);
        }
    }

    public function doHeader(array $stylesheets = []) {
        $styleHeader = '';
        $styleHeader .= '<style>';
        foreach ($stylesheets as $stylesheet) {
            $styleHeader .= file_get_contents($stylesheet);
        }
        $styleHeader .= '</style>';
        $this->header = $styleHeader;
//        file_get_contents(__DIR__."/../../frontend/web/css/veltrix/style.css").

    }

    private function composeMessage($user) {
        $body = '';
        $body .= $this->header;
        if ($this->template) {
            $body .= $this->doTreatment($this->template, $user);
        } else {
            foreach ($this->body as $textAndTreatment) {
                if ($textAndTreatment['treatment']) {
                    $body .= $this->doTreatment($textAndTreatment['text'], $user);
                } else {
                    $body .= $textAndTreatment['text'];
                }
            }
        }
        foreach ($user[1] as $needle => $value) {
            $body = str_replace('###' . $needle . '###', $value, $body);
        }
        return $body;
    }

    private function doTreatment($text, $user) {
        $has_match = true;
        while ($has_match) {
            preg_match('/(?:^|[^#])(#{2}[^#]+#{2})/', $text, $output_array);
            $has_match = count($output_array) > 0;
            if (!$has_match) continue;
            $text = str_replace($output_array[1], $this->fillValue($output_array[1], $user), $text);
        }
        return $text;
    }

    private function fillValue($marker, $user) {
        $values = preg_split('/-/', str_replace('#', '', $marker));
        switch ($values[0]) {
            case 'USER' :
                return $this->handleUserValue($user, $values[1]);
                break;
            //MODEL-Table-ID-field
            case 'MODEL' :
                return $this->handleModel($values[1], $values[2], $values[3]);
                break;
            //ARRAY-Table-filters-value1;header1:value2;header2
            case 'ARRAY':
                return $this->handleArray($values[1], $user, $values[2], $values[3]);
                break;
        }
    }

    private function handleModel($modelName, $id, $valueName) {
        $className = '\\common\\models\\' . $modelName;

        $model = $className::find()->where(['id' => $id])->one();
        if ($className != null) {
            return $model->$valueName;
        }
        return "$valueName here";
    }

    private function drawTableHeader($fields) {
        $table = "<table class='table display table-bordered nowrap'>";
        $table .= "<thead>";
        $table .= "<tr>";

        foreach ($fields as $field) {
            $fieldsAndName = preg_split('/;/', $field);
            $table .= "<th>" . (count($fieldsAndName) > 1 ? $fieldsAndName[1] : $fieldsAndName[0]) . "</th>";
        }
        $table .= "</tr>";
        $table .= "</thead>";
        $table .= "<tbody>";
        return $table;
    }

    private function handleFuncArgs($params, $data) {
        $return_value = [];
        foreach ($params as $param) {
            if (substr($param, 0, 1) == '$') {
                $property = str_replace('$', '', $param);
                $special_field = preg_split('/[.]/', $property);
                if (count($special_field) == 1) {
                    $return_value[] = $data->$property;
                } else if (count($special_field) == 2) {
                    $one = $special_field[0];
                    $two = $special_field[1];
                    if ($data->$one && $data->$one->$two)
                        $return_value[] = $data->$one->$two;
                    else return -1;
                }
            } else {
                $return_value[] = $param;
            }
        }
        return $return_value;
    }

    private function handleFunction(&$raw_func, &$data) {
        $one = $raw_func[0];
        $one = str_replace('=', '', str_replace('>', '->', $one));
        $params = preg_split('/[|]/', $one);
        $func = $params[0];
        unset($params[0]);
        $args = $this->handleFuncArgs($params, $data);
        if ($args == -1)
            return '';
        return str_replace('"', '', call_user_func_array($func, $args));

    }

    private function handleArray($modelName, $user, $raw_filters, $raw_fields) {
        $datas = $this->getDatas($modelName, $user->id, $raw_filters);
        $fields = preg_split('/:/', $raw_fields);
        $table = $this->drawTableHeader($fields);
        foreach ($datas as $data) {
            $table .= "<tr>";
            foreach ($fields as $field) {
                $fieldsAndName = preg_split('/;/', $field);
                $table .= "<td>";
                $special_field = preg_split('/[.]/', $fieldsAndName[0]);
                if (substr($field, 0, 1) == '=') {
                    $table .= $this->handleFunction($fieldsAndName, $data);
                } else if (count($special_field) == 1) {
                    $one = $special_field[0];
                    $table .= $data->$one;
                } else if (count($special_field) == 2) {
                    $one = $special_field[0];
                    $two = $special_field[1];
                    $table .= $data->$one->$two;
                } else if (count($special_field) == 3) {
                    $one = $special_field[0];
                    $two = $special_field[1];
                    $three = $special_field[2];
                    if ($data->$one && $data->$one->$two && $data->$one->$two->$three) {
                        $table .= $data->$one->$two->$three;
                    }
                }
                $table .= "</td>";
            }
            $table .= "</tr>";
        }
        $table .= "</tbody>";
        $table .= "</table>";
        return $table;
    }

    private function getDatas($modelName, $user_id, $raw_filters) {
        $className = '\\common\\models\\' . $modelName;
        $query = $className::find()->where(['=', 1, 1]);
        if ($raw_filters)
            $this->handleArrayFilters($query, $raw_filters);
        if ($user_id)
            $query->andWhere(['user_id' => $user_id]);
        return $query->all();
    }

    private function handleArrayFilters(&$query, &$raw_filters) {
        $filters = preg_split('/;/', $raw_filters);
//        $return_value = [];
        foreach ($filters as $raw_filter) {
            $filter = preg_split('/:/', $raw_filter);
            if ($filter[0] === 'from') {
                $query->andWhere(['>=', 'created_at', strtotime($filter[1])]);
            } else if ($filter[0] == 'to') {
                $query->andWhere(['<=', 'created_at', strtotime($filter[1])]);
            } else {
                $query->andWhere($filter);
            }
        }
//        return $return_value;
    }

    private function handleUserValue($user, $value) {
        if ($user[0]->$value) {
            return $user[0]->$value;
        }
        return "";
    }
}