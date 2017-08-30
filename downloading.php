<?php 
require "includes/common.php"; 

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
		<!-- <div class="alert alert-danger"> -->
		

		<?php
// file name: test.php
function excute($fullPath){
      // 'C:\Windows\Microsoft.NET\Framework\v4.0.30319\csc';
    exec($fullPath, $outpara);
    echo '<PRE>';
    echo ($fullPath);
    echo "<br>";
    print_r($outpara);
    echo '<PRE>';
}

excute($fullPath =$PY_PATH.' test.py')

?>


		<!-- </div> -->
	</div>


	<?php include("includes/footer.php");?>




	</body>
</html>