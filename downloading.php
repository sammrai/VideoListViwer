<?php 
require "includes/common.php"; 

function excutecmd($cmdpath, $scriptpath, $echo=false){
	$fullPath =$cmdpath.' '.$scriptpath;
	if (PHP_OS=="Darwin") $fullPath .=" 2>&1 &";
	if (PHP_OS=="WINNT") $fullPath = "start /B "."$fullPath"." 2>&1";

	error_reporting(E_ALL);
	$handle = popen($fullPath, 'r');

	if ($echo){
		echo "<pre>";
		echo $fullPath."<br>";
		$read = stream_get_contents($handle);
		echo $read;
		pclose($handle);
		echo "</pre>";
	    return $read;
	}
}


?>

<!DOCTYPE html>
<html lang="ja">
<?php include("includes/head.php");?>
	<body>
	<?php include("includes/navbar.php");?>

	<div class="container">
		<div class="alert alert-danger">
		<strong>Error : </strong> This page is under construction.
		</div>
	</div>

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
$DEBUG_FLAG_=true;

if (isset($_POST["sub1"])) {
    $kbn = htmlspecialchars($_POST["sub1"], ENT_QUOTES, "UTF-8");


    switch ($kbn) {
        case "run": excutecmd($PY_PATH,'test.py', $DEBUG_FLAG_); break;
        case "stop":
	    	$arg1 = "jobpid";
	    	$arg2 = filter_input(INPUT_POST, 'arg2');
	    	excutecmd($PY_PATH,'kill.py'." ".$arg1." -p ".$arg2, $DEBUG_FLAG_);
	    	break;
        case "reflesh":
	        $arg1 = "jobpid";
	        excutecmd($PY_PATH,'kill.py'." ".$arg1." -r",$DEBUG_FLAG_);
	        break;
        case "killall":
	        $arg1 = "jobpid";
	        excutecmd($PY_PATH,'kill.py'." ".$arg1." -a",$DEBUG_FLAG_);
	        break;
        case "del_cashe":
	        $arg1 = "jobpid";
	        unlink($arg1);
	        break;
        default:  echo "エラー"; exit;

    }
    sleep(1);
}
if (file_exists("jobpid"))$dataArray = json_decode(file_get_contents("jobpid"),true);
// $dataArray = sort_with_key($dataArray,"time")
?>			
			<?php if(isset($dataArray)):?>
			<div class="panel panel-default">
				<div class="panel-heading"><h1 class="panel-title">Title list</h1></div>
				<table class="table table-hover ">
					<tbody>
							<?php foreach ($dataArray as $key => $val): ?>
								<tr >
								<td><?php echo $key ?></td>
								<td ><?php echo $val["time"] ?></td>
								<td ><?php echo $val["flag"] ?></td>
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




