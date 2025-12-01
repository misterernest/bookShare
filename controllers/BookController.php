<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Book;


class BookController extends Controller
{
    public function actionAll() {

        $books = Book::find()->all();
        return serialize($books);
    }

    public function actionDetail($id) {

        $book = Book::findOne($id);
        if (empty($book)) {
            // return $this->redirect(['site/index']);
            Yii::$app->session->setFlash('success', 'Book not found.');
            return $this->goHome();
        }

        return $book->toString();
    }
}