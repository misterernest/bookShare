{use class="Yii"}
{use class="yii\helpers\Html"}
<h1>Site index</h1>

{if Yii::$app->user->isGuest}
    hola invitado, {Html::a('login', ['site/login'])} 👋👋
{else}
{assign "user" Yii::$app->user->identity}
    <p>hola {$user->username} 👋👋</p>
    <p>You have voted {$user->votesCount} times and the average is {$user->votesAvg}.</p>
{/if}

<p>There are {Html::a("{$book_count} books", ['book/all'])} and 
    {Html::a("{$author_count} authors", ['author/all'])}
</p>
{if !Yii::$app->user->isGuest}
    <p>{Html::a('Create a book', ['book/new'])}</p>
    <p>{Html::a('Add new Author', ['author/new'])}</p>
{/if}