{use class="yii\helpers\Html"}
{use class="Yii"}

{title}{$book->title}{/title}
<h1>{$this->title}</h1>

<p>Un libro de {$book->author->name}.</p>

{if Yii::$app->user->identity->hasBook($book->id)}
    <p>You own this book.</p>
    {Html::a('I no longer have this book')}
    // Form to 1 - 5 stars rating [book/score]
{else}
    <p>{Html::a('I have this book',
        ['book/i-own-this-book', 'book_id' => $book->id])}
    </p>
{/if}
