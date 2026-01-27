<?php

namespace app\controllers;

use Yii;
use yii\web\controller;
use app\models\User;
use Exception;

class UserController extends Controller {
    
    public function actionNew() {
        if(!Yii::$app->user->isGuest){
            //logged in users should not be able to create new users
            Yii::$app->session->setFlash('warning', 'You are already logged in.');
            return $this->goHome();
        }
        $user = new User();
        if ($user->load(Yii::$app->request->post())) {
            //there are some data on post for me and we need to validate and save
            if ($user->validate()) {
                if ($user->save()) {
                    Yii::$app->session->setFlash('success', 'User created successfully.');
                    return $this->redirect(['site/login']);
                } else {
                    throw new Exception('Failed to save user.');
                    return;
                }
            }
            $user->password = '';
            $user->password_repeat = '';
        }
        return $this->render('new.tpl', ['user' => $user]);
    }
}