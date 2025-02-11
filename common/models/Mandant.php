<?php


namespace common\models;


use yii\db\ActiveRecord;

/**
 * User model
 *
 * @property integer $id
 * @property string $name
 * @property string $firstname
 * @property string $address
 * @property string $postal
 * @property string $city
 * @property string $phone
 * @property string $mail
 * @property string $birthdate
 * @property string $birthplace
 * @property integer $user_id
 *
 */
class Mandant extends ActiveRecord {

    public $items = [];
    public $soldSomething = false;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'mandant';
    }

    public function beforeDelete() {
        foreach (MandantFile::findAll(['mandant_id' => $this->id]) as $file) {
            $file->delete();
        }
        foreach (Item::findAll(['mandant_id' => $this->id]) as $item) {
            $item->delete();
        }

        return parent::beforeDelete(); // TODO: Change the autogenerated stub
    }

    public function getItems() {
        $this->items = Item::findAll(['mandant_id' => $this->id]);
        foreach ($this->items as $item) {
            $item->getSale();
            if ($item->sale !== null) {
                $this->soldSomething = true;
            }
        }
    }

    public static function newMandant(array $post) {
        $mandant = new Mandant([
            "name" => $post['name'],
            "firstname" => $post['firstname'],
            "birthdate" => $post['birthdate'],
            "birthplace" => $post['birthplace'],
            "address" => $post['address'],
            "city" => $post['city'],
            "postal" => $post['postal'],
            "phone" => $post['phone'],
            "mail" => $post['mail'],
            "user_id" => \Yii::$app->user->id,
        ]);
        return $mandant->save();
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['name', 'firstname'], 'required']
        ];
    }
}