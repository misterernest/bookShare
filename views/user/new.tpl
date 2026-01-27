{use class="yii\widgets\ActiveForm" type="block"}
<h1>Create new user</h1>
{ActiveForm id="new-user" assign="form"}
    {$form->field($user, 'username')}
    {$form->field($user, 'email')}
    {$form->field($user, 'password')->passwordInput()}
    {$form->field($user, 'password_repeat')}
    {$form->field($user, 'bio')->textarea(['rows' => 4])}
    <input type="submit" value="Create User" class="btn btn-primary"/>
{/ActiveForm}
