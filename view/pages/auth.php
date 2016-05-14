<style>
article
{
width:300pt;
}
</style>
<?php global $set ?>
<form action="/<?php echo $set['site']['url']['base'];?>?p=auth" method="post">
<p class="maintext"> Введіть номер своєї картки</p>
<p><input type="text" name="login" placeholder="Номер карти"></p>
<p class="maintext">Введіть пароль</p>
<p><input type="password" name="password" placeholder="Пароль"></p>
<p><input type="submit" value="Увійти"></p>
</form>