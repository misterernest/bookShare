<?php 

namespace app\commands;

use yii\console\Controller;
use app\models\User;
use yii\console\ExitCode;

class UserController extends Controller
{
    public function actionNew($username, $password)
    {
        $user = New User();
        $user->username = $username;
        $user->password = $password;
        if ($user->save()) {
            printf("User %s created successfully.\n", $user->username);
        } else {
            printf("Failed to create user %s.\n", $user->username);
        }
    }

    public function actionCheckPassword($username, $password)
    {
        $user = User::findOne(['username' => $username]);
        if (!empty($user)) {
            if ($user->password === $user->hidePassword($password)) {
                printf("Password for user %s is correct.\n", $username);
                return ExitCode::OK;
            }
        }
        printf('nel\n');
        return ExitCode::OK;
    }

    public function actionSetPassword($username, $password)
    {
        $user = User::findOne(['username' => $username]);
        if (!empty($user)) {
            $user->password = $user->hidePassword(
                $password
            );
            if ($user->save()) {
                printf("Password for user %s updated successfully.\n", $username);
            } else {
                printf("Failed to update password for user %s.\n", $username);
            }
        } else {
            printf("User %s not found.\n", $username);
        }

    }
}
