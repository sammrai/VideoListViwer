<?php 
require "includes/common.php"; 
// file name: test.php



function excutecmd($pypath, $scriptpath){
	$fullPath =$pypath.' '.$scriptpath;
    exec($fullPath, $outpara);
    echo '<PRE>';
    echo ($fullPath);
    echo "<br>";
    print_r($outpara);
    echo '<PRE>';
    return $outpara;
}
$pid="aa";

if (isset($_POST["sub1"])) {
    $kbn = htmlspecialchars($_POST["sub1"], ENT_QUOTES, "UTF-8");
    switch ($kbn) {
        case "run": excutecmd($PY_PATH,'test.py'); break;
        case "stop": excutecmd($PY_PATH,'kill.py '."$pid"); break;
        default:  echo "エラー"; exit;
    }
}
?>
		


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




	</div>


	<?php include("includes/footer.php");?>




	</body>
</html>
<?php
$cmd = 'Start-Job C:\Users\4046672\AppData\Local\conda\conda\envs\py27\python.exe test.py';

while (@ ob_end_flush()); // end all output buffers if any

$proc = popen($cmd, 'r');
echo '<pre>';
while (!feof($proc))
{
    echo fread($proc, 4096);
    @ flush();
}
echo '</pre>';
?>