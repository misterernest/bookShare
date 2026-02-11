{use class="yii\helpers\Html"}
{use class="yii\widgets\ActiveForm" type="block"}
{use class="Yii"}

{title}{$book->title}{/title}
<h1>{$this->title}</h1>

<p>Un libro de {$book->author->name}.</p>

{if Yii::$app->user->identity->hasBook($book->id)}
    <p>You own this book.</p>
    {Html::a('I no longer have this book')}
    {ActiveForm id="new-score" assign="form" action=['book/score'] }
        {$form->field($book_score, 'score')
            ->dropDownList([
                1 =>'⭐',
                2 =>'⭐⭐',
                3 =>'⭐⭐⭐',
                4 =>'⭐⭐⭐⭐',
                5 =>'⭐⭐⭐⭐⭐'
        ])}
        {$form->field($book_score, 'book_id')
            ->hiddenInput(['value' => $book->id])
            ->label(false)}
            <input type="submit" value="Score this book" class="btn btn-primary">
    {/ActiveForm}
{else}
    <p>{Html::a('I have this book',
        ['book/i-own-this-book', 'book_id' => $book->id])}
    </p>
{/if}
