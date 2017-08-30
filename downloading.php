<?php 
require "includes/common.php"; 
// file name: test.php



function excutecmd($pypath, $scriptpath, $echo=false){
	$fullPath =$pypath.' '.$scriptpath;
	if (PHP_OS=="Darwin") $fullPath .=" 2>&1 &";

	error_reporting(E_ALL);
	$handle = popen($fullPath, 'r');

	if ($echo){
		// echo "<pre>";
		$read = stream_get_contents($handle);
		// echo $read;
		// pclose($handle);
		// echo "</pre>";
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
// file_put_contents("hoge.dat", $json_encode_hoge);
// $dataArray = json_decode(file_get_contents("hoge.dat"));
// print_r($dataArray)

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
					<input type="submit" class="btn btn-danger" value="stop" name="sub1">
				</nav>
			</form>

<?php
if (isset($_POST["sub1"])) {
    $kbn = htmlspecialchars($_POST["sub1"], ENT_QUOTES, "UTF-8");
    switch ($kbn) {
        case "run": excutecmd($PY_PATH,'test.py'); break;
        case "stop":echo excutecmd($PY_PATH,'kill.py',$echo=true); break;
        default:  echo "エラー"; exit;
    }
}
?>

	</div>


	<?php include("includes/footer.php");?>




	</body>
</html>




