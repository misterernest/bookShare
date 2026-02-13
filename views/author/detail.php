<?php
use yii\helpers\Html;

?>


<h1><?=  $author->toString() ?></h1>

<p>the average for each his/her books is: <?= $author->score ?></p>

<h2>Books:</h2>
<ol>
<?php foreach ($author->books as $book): ?>
    <li>
        <?= Html::a($book->title, ['book/detail', 'id' => $book->id]) ?>
        <span> - </span><?= $book->author->getScore() ?>
    </li>
<?php endforeach; ?>
</ol>