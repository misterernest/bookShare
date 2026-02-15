<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\base\Exception;

class User extends ActiveRecord implements \yii\web\IdentityInterface
{
    // public $id;
    // public $username;
    // public $password;
    // public $authKey;
    // public $accessToken;

    public $password_repeat;
    public $email;

    public static function tableName()
    {
        return 'Users';
    }

    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['username', 'filter', 'filter' => function ($value) {
                $value = trim($value);
                $value = strtolower($value);
                return $value;
            }],
            ['bio', 'default'],
            ['username', 'string', 'length' => [3, 100]],
            ['password', 'compare', 'compareAttribute' => 'password_repeat' ],
            ['password_repeat', 'default'],
            ['username', 'unique'],
            ['email', 'email'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'username',
        ];
    }

    public function attributeHints()
    {
        return [
            'username' => 'Choose a unique username',
            'password_repeat' => 'Repeat the password to confirm it',
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        $user = self::findOne($id);
        if(empty($user)){
            return null;
        }
        return $user;

        // return isset(self::$users[$id]) ? new static(self::$users[$id]) : null;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $user = self::findOne(['token' => $token]);
        if(empty($user)){
            return null;
        }
        return $user;

        // foreach (self::$users as $user) {
        //     if ($user['accessToken'] === $token) {
        //         return new static($user);
        //     }
        // }

        // return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        $user = self::find()->where(['username' => $username])->one();
        if (empty($user)) {
            return null;
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->user_id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {

        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        if (!empty($authKey)) {

            return $this->authKey === $authKey;
        }
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $this->hidePassword($password);
    }

    public function hidePassword($password)
    {
        if (empty(getenv('SALT'))) {
            throw new Exception("Environment variable 'salt' is not set.");
        }
        return md5(sprintf('%s-%s-%s', $password, $this->username, getenv('SALT')));
    }

    public function beforeSave($insert)
    {
        if ($insert == true) {
            $this->password = $this->hidePassword($this->password);
        }
        return parent::beforeSave($insert);
    }

    public function hasBook($book_id):bool {
        $userBook = UserBook::find()->where([
            'user_id' => $this->id,
            'book_id' => $book_id,
        ])->all();
        
        return !empty($userBook);
    }

    public function getVotes() {
        return $this->hasMany(BookScore::class, ['user_id' => 'id'])->all();
    }

    public function getVotesCount() {
        return count($this->votes);
    }

    public function getVotesAvg() {
        $i = 0;
        $score = 0;
        foreach ($this->votes as $vote) {
            $i++;
            $score += $vote->score;
        }
        if($i == 0) {
            return "no votes yet";   
        }

        return sprintf("%0.2f", $score / $i);
    }

    public function hasVotedFor($book_id) {
        $bookScore = BookScore::find()
        ->where([
            'book_id' => $book_id,
            'user_id' => $this->id,
        ])
        ->one();
        if(empty($bookScore)) {
            return false;
        }
        return true;
    }

    public function getVoteForBook($book_id) {
        return $this->hasOne(
            BookScore::class, 
            [
                'user_id' => 'id'
            ])->where(['book_id' => $book_id])->one();
    }
}
