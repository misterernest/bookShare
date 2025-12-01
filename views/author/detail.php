<?php
use yii\helpers\Html;

?>


<h1><?=  $author->toString() ?></h1>
<h2>Books:</h2>
<ol>
<?php foreach ($author->books as $book): ?>
    <li><?= Html::a($book->title, ['book/detail', 'id' => $book->id]) ?></li>
<?php endforeach; ?>
</ol>