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
    

    private function quick($book) {
        $book->title = sprintf("%sffff", $book->title);
        return $book;
    }

    public function actionBooks($file) {
        $f = fopen($file, "r");
        while(!feof($f)) {
            $data = fgetcsv($f);
            if (!empty($data[1]) && !empty($data{1})) {
                $book = new Book;
                $book->title = $data[1];
                $book->author = $data[2];
                $book = $this->quick($book);
                printf("%s\n", $book->toString());
            }
        }
        fclose($f);
        return ExitCode::OK;
    }
}