<?php
namespace app\models;

use yii\db\ActiveRecord;

class Book extends ActiveRecord
{

    public static function tableName()
    {
        return 'books';
    }

    public function getId() {
        return $this->book_id;
    }

    public function toString() {
        return sprintf("(%d) %s", $this->id, strtoupper($this->title));
    }
}