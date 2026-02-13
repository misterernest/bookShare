<?php

namespace app\models;

use yii\db\ActiveRecord;

class Author extends ActiveRecord
{
    public static $nationalities = [
        'mx' => 'Mexico',
        'us' => 'United States',
        'ca' => 'Canadian',
        'co' => 'Colombia',
        'pe' => 'Peru',
        'ar' => 'Argentina',
        'es' => 'Spain',
        'de' => 'Germany',
        'uk' => 'United Kingdom',
        'gr' => 'Greece',
        'it' => 'Italy',
        'fr' => 'France',
        'ie' => 'Ireland',
    ];

    public function rules() {
        return [
            ['name', 'required'],
            ['name', 'filter', 'filter' => function($value) {
                $value = trim($value);
                $value = ucwords($value);
                return $value;
            }],
            ['name', 'string', 'length' => [4, 100]],
            ['nationality', 'filter', 'filter' => function($value) {
                if($value == '--') {
                    $value = null;
                }
                return $value;
            }],
            ['nationality', 'string', 'length' => [2, 2]],
        ];
    }

    public static function tableName()
    {
        return 'authors';
    }

    public function getId() {
        return $this->author_id;
    }

    public function toString() {
        return sprintf("%s (%s)", $this->name, count($this->books));
    }

    public function getBooks() {
        return $this
            ->hasMany(Book::class, ['author_id' => 'author_id'])
            ->all();
    }

    public static function getAuthorList() {
        $authors = self::find()->orderBy('name')->all();
        foreach ($authors as $author) {
            $list[$author->author_id] = $author->name;
        }
        return $list;
    }

    public static function getNationalities() {
        asort(self::$nationalities);
        return array_merge(
            ['--' => 'Select Nationality'],
            self::$nationalities
        );
    }
}