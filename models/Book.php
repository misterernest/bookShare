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
        return sprintf("(%d) %s - %s",
            $this->id,
            $this->title,
            $this->author->name
        );
    }

    public function getAuthor() {
        return $this->hasOne(Author::class, ['author_id' => 'author_id'])
        ->one();
    }
}