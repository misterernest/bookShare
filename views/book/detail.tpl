{use class="yii\helpers\Html"}
{use class="yii\widgets\ActiveForm" type="block"}
{use class="Yii"}

{title}{$book->title}{/title}
<h1>{$this->title}</h1>

<p>
    A book from 
    {Html::a($book->author->name, ['author/detail', id => $book->author->id])}.
</p>

<p>The average score is {$book->getAverageScore()}.</p>
{assign "user" Yii::$app->user->identity}

{if $user->hasBook($book->id)}
    {if $user->hasVotedFor($book->id)}
        <p>You already scored this book. Your vote is {$user->getVoteForBook($book->id)->score}</p>
    {else}
        <p>You own this book.</p>
        {Html::a('I no longer have this book', '#')}
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
    {/if}    
{else}
    <p>{Html::a('I have this book',
        ['book/i-own-this-book', 'book_id' => $book->id])}
    </p>
{/if}
