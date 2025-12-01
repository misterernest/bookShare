<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Author;


class AuthorController extends Controller
{
    public function actionAll($search = null) {
        if ($search != null) {
            $authors = Author::find()
                ->where(['like', 'name', $search])
                ->all();
            return serialize($authors);
        }

        $authors = Author::find()->all();
        return serialize($authors);
    }

    public function actionDetail($id) {

        $author = Author::findOne($id);
        if (empty($author)) {
            // return $this->redirect(['site/index']);
            Yii::$app->session->setFlash('warning', 'Author not found.');
            return $this->redirect(['author/all']);
        }

        return $this->render('detail', ['author' => $author]);
    }
}