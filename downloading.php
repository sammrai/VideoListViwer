<?php 
require "includes/common.php"; 

?>

<!DOCTYPE html>
<html lang="ja">
<?php include("includes/head.php");?>
	<body>
	<?php include("includes/navbar.php");?>

<!-- 	<div class="container">
		<div class="alert alert-danger">
		<strong>Error : </strong> This page is under construction.
		</div>
	</div> -->

	<div class="container">
			<form method="POST" action="">
				<div align="center" class="botton_button">
					<input type="submit" class="btn btn-primary" value="run" name="sub1">
					<input type="submit" class="btn btn-success" value="reflesh" name="sub1">&ensp;&ensp;
					<input type="submit" class="btn btn-danger" value="killall" name="sub1">
					<input type="submit" class="btn btn-danger" value="del_cashe" name="sub1">
				</div>
			</form>

<?php

if (isset($_POST["sub1"])) {
    $kbn = htmlspecialchars($_POST["sub1"], ENT_QUOTES, "UTF-8");


    switch ($kbn) {
        case "run": excutecmd($PY_PATH,'test.py '.$JOB_FILE, $DEBUG_FLAG_); break;
        case "stop":
	    	$arg2 = filter_input(INPUT_POST, 'arg2');
	    	excutecmd($PY_PATH,'kill.py'." ".$JOB_FILE." -p ".$arg2, $DEBUG_FLAG_);
	    	break;
        case "reflesh":
	        excutecmd($PY_PATH,'kill.py'." ".$JOB_FILE." -r",$DEBUG_FLAG_);
	        break;
        case "killall":
	        excutecmd($PY_PATH,'kill.py'." ".$JOB_FILE." -a",$DEBUG_FLAG_);
	        break;
        case "del_cashe":
			$do = unlink($JOB_FILE);
			if($do=="1"){ 
			echo "The file was deleted successfully."; 
			} else { echo "There was an error trying to delete the file."; } 
			break;
			default:  echo "エラー"; exit;

    }
    sleep(1);
}
// $JOB_ARRAY = sort_with_key($JOB_ARRAY,"time")
?>			
			<?php if(isset($JOB_ARRAY)):?>
			<div class="panel panel-default">
				<div class="panel-heading"><h1 class="panel-title">Title list</h1></div>
				<table class="table table-hover ">
					<tbody>
							<?php foreach ($JOB_ARRAY as $key => $val): ?>
								<tr >
								<td><?php echo $key ?></td>
								<td ><?php echo $val["time"] ?></td>
								<td ><?php echo $val["status"] ?></td>
								<td align="right">
									<form method="POST" action="">
									<input type="hidden" value="<?php echo $key ?>" name="arg2">
									<input type="submit" class="btn btn-danger" value="stop" name="sub1">
									</form>
								</td>
								</tr>
						   <?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<?php endif;?>

		</div>
	</div>
	<?php include("includes/footer.php");?>




	</body>
</html>




