<?php 
require "includes/common.php"; 
// file name: test.php



function excutecmd($pypath, $scriptpath, $echo=false){
	$fullPath =$pypath.' '.$scriptpath;
	if (PHP_OS=="Darwin") $fullPath .=" 2>&1 &";

	error_reporting(E_ALL);
	$handle = popen($fullPath, 'r');

	if ($echo){
		echo "<pre>";
		$read = stream_get_contents($handle);
		echo $read;
		pclose($handle);
		echo "</pre>";
	    return $read;
	}
}
// $pid="aa";

// $dataArray=array(15,35,64);
// file_put_contents("hoge.dat", serialize($dataArray));
// $dataArray = unserialize(file_get_contents("hoge.dat"));
// print_r($dataArray)
// $hoge = ['a','b','c'];
// $json_encode_hoge = json_encode($hoge);
// // print_r($json_encode_hoge);
// file_put_contents("jobpid", $json_encode_hoge);


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
				<nav align="center" class="botton_button">
					<input type="submit" class="btn btn-primary" value="run" name="sub1">
					<input type="submit" class="btn btn-default" value="reflesh" name="sub1">
				</nav>
			</form>

<?php
if (isset($_POST["sub1"])) {
    $kbn = htmlspecialchars($_POST["sub1"], ENT_QUOTES, "UTF-8");


    switch ($kbn) {
        case "run": excutecmd($PY_PATH,'test.py'); break;
        case "stop":
	    	$arg1 = "jobpid";
	    	$arg2 = filter_input(INPUT_POST, 'arg2');
	    	excutecmd($PY_PATH,'kill.py'." ".$arg1." ".$arg2);
	    	break;
        case "reflesh":
	        $arg1 = "jobpid";
	        excutecmd($PY_PATH,'kill.py'." ".$arg1);
	        break;
        default:  echo "エラー"; exit;

    }
    sleep(1);
}
$dataArray = json_decode(file_get_contents("jobpid"),true);
// $dataArray = sort_with_key($dataArray,"time")
?>			
			<?php if($dataArray):?>
			<div class="panel panel-default">
				<div class="panel-heading"><h1 class="panel-title">Title list</h1></div>
				<table class="table table-hover ">
					<tbody>
							<?php foreach ($dataArray as $key => $val): ?>
								<tr data-toggle="collapse" data-target='#<?php echo "accordion".$val["episode_ind"] ?>' class="clickable">
								<td><?php echo $key ?></td>
								<td ><?php echo $val["time"] ?></td>
								<td ><?php echo $val["flag"] ?></td>
								<td align="right">
									<form method="POST" action="">
									<div class="btn-group" >
									<input type="hidden" value="<?php echo $key ?>" name="arg2">
									<input type="submit" class="btn btn-danger" value="stop" name="sub1">
									</div>
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




