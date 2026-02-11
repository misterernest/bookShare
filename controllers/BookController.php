<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Book;
use app\models\UserBook;
use app\models\BookScore;

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
        $bookScore = new BookScore();
        $bookScore->book_id = $book->id;
        return $this->render('detail.tpl', [
            'book' => $book, 
            'book_score' => $bookScore
        ]);
    }

    public function actionScore() {
        $bookScore = new BookScore();
        if ($bookScore->load(Yii::$app->request->post())) {
            $bookScore->user_id = Yii::$app->user->identity->id;
            if ($bookScore->validate()) {
                if ($bookScore->save()) {
                    Yii::$app->session->setFlash('success', 'Book scored successfully.');
                    return $this->redirect(['book/detail', 'id' => $bookScore->book_id]);
                }
            }
        }
        return $this->redirect(['book/all', ]);
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

    public function actionIOwnThisBook($book_id) {
        if (Yii::$app->user->isGuest) {
            //only logged in users should be able to create new books
            return $this->goHome();
        }
        $userBook = new UserBook;
        $userBook->user_id = Yii::$app->user->identity->id;
        $userBook->book_id = $book_id;
        $userBook->save();
        Yii::$app->session->setFlash('success', 'Book added to your collection.');
        return $this->redirect(['book/detail', 'id' => $book_id]);
    }
}