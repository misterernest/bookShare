{use class='yii\helpers\Html'}
<h1>Every Author</h1>

<ol>
    {foreach $authors as $author}
        <li> {Html::a($author->name, ['author/detail', 'id' => $author->id])} </li>
    {/foreach}  
</ol>