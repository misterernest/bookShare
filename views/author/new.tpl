{use class="yii\widgets\ActiveForm" type="block"}
{use class="app\models\Author"}

<h1>New Author</h1>

{ActiveForm id="new-author" assign="form"}
    {$form->field($author, 'name')}
    {$form->field($author, 'nationality')
        ->dropDownList(Author::getNationalities())}
    <input type="submit" value="save" class="btn btn-primary">
{/ActiveForm}