<?php 
require "includes/common.php"; 

?>

<!DOCTYPE html>
<html lang="ja">
<?php include("includes/head.php");?>
	<body>
	<?php include("includes/navbar.php");?>

	<div class="container">

<?php
 $conf_file = $config_file; // 書き込み可になっていること。予め空ファイルを作成するのが吉
 $ini = parse_ini_file($conf_file);
 if ($_POST['limit']) $ini['limit'] = $_POST['limit'];
 $fp = fopen($conf_file, 'w');
 foreach ($ini as $k => $i) fputs($fp, "$k=$i\n");
 fclose($fp);
?>

<form method="post" action="">
  YourAge <input type="number" name="limit" value="<?php print $ini['limit'] ?>"><br>
  <input type="submit" value="保存">
</form>


	</div>



	<?php include("includes/footer.php");?>
	</body>
</html>