<?php

namespace app\commands;

use app\models\Book;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * Implmements data import commands.
 */
class ErnestoController extends Controller
{ 
    /**
     * Imports book data from a CSV file.
     */
    public function actionBooks($file) {
        $f = fopen($file, "r");
        while(!feof($f)) {
            $data = fgetcsv($f);
            if (!empty($data[1]) && !empty($data{2})) {
                $book = new Book;
                $book->title = $data[1];
                $book->author_id = 1;
                $book->save();
                printf("%s\n", $book->toString());
            }
        }
        fclose($f);
        return ExitCode::OK;
    }
}