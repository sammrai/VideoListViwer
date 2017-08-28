<?php require "includes/common.php"; ?>

<!DOCTYPE html>
<html lang="ja">
<?php include("includes/head.php");?>
	<body>
	<?php include("includes/navbar.php");?>

	<div class="container">

<?php
$save=false;
$ini = parse_ini_file($config_file);
if (!is_empty($_POST)){
	$ini= array_merge($ini,$_POST);
	$fp = fopen($config_file, 'w');
	foreach ($ini as $k => $i) fputs($fp, "$k=$i\n");
	fclose($fp);
	$save=true;

}

?>
<div class="container">
	<?php if ($save) : ?>
		<div class="alert alert-success">
		<strong>
		<i class="glyphicon glyphicon-ok"></i> Success! :
		</strong> Settings saved.
		</div>
	<?php endif; ?>

	<form method="post" action="">

		<?php foreach($ini as $key => $value): ?>
			<div class="input-group" style="padding: 0.5%;">
				<span class="input-group-addon"> <?php echo $key ?> </span>
				<input type="text" class="form-control" value="<?php echo $value ?>" name= <?php echo $key ?> >
			</div> 
		<?php endforeach; ?>
		<nav align="center" style="margin: 1%;">
			<input type="submit" class="btn btn-primary" value="Save">
		</nav>


	</form>

</div>


	<?php include("includes/footer.php");?>
	</body>
</html>