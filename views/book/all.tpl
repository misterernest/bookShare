{use class="yii\helpers\Html"}

<h1>Todos los libros {$title}</h1>
<ul>
    {foreach $books as $book}
        <li>
            {Html::a($book->toString(), 
                ['book/detail', 'id'=> $book->id])
            }
        </li>
    {/foreach}