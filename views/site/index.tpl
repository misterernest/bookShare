{use class="Yii"}
{use class="yii\helpers\Html"}
<h1>Site index</h1>

{if Yii::$app->user->isGuest}
hola invitado, {Html::a('login', ['site/login'])} 👋👋
{else}
hola {Yii::$app->user->identity->username} 👋👋
{/if}

<p>Hay {$book_count} libros en el sistema</p>
<p>{Html::a('Crear libro', ['book/new'])}</p>