<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Book;


class BookController extends Controller
{
    public function actionAll() {
        $books = Book::find()->all();
        return $this->render('all.tpl', ['books' => $books, 'title' => '3']);
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

    public function actionNew() {
        if (Yii::$app->user->isGuest) {
            //only logged in users should be able to create new books
            return $this->goHome();
        }

        $book = new Book();
        if ($book->load(Yii::$app->request->post())) {
            //there are some data on post for me and we need to validate and save
            if ($book->validate()) {
                if ($book->save()) {
                    Yii::$app->session->setFlash('success', 'Book created successfully.');
                    return $this->redirect(['book/all']);
                } else {
                    throw new \Exception('Failed to save book.');
                    return;
                }
            }
        }
        return $this->render('form.tpl', ['book' => $book]);
    }
}