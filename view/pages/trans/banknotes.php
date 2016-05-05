<?php foreach( $data as $k => $v ){
	echo '<a href="/'.$url['base'].'?p=trans/withdraw&s='.$k.'">'.($v?'<b>'.$k.'</b>':$k).'</a><br>';
}
?>
Другая сумма (кратная 10)
<form action="/<?php echo $url['base'];?>?p=trans/withdraw" method="POST">
  <input type="text" name="s" value=""><br><br>
  <input type="submit" value="Подтвердить">
</form>