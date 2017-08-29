<?php 
require "includes/common.php"; 

function get_json($jsonpath){
	if (!file_exists($jsonpath))throw new Exception('JSON file doesn\'t exist. "'.$jsonpath.'"');
	$json = file_get_contents($jsonpath);
	$json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
	return json_decode($json,true);
}

try{
	$jsonpath = $DL_DIR."/blog-entry-117403/urls.json";
	$obj = get_json($jsonpath);
} catch (Exception $e) {
    $err = $e->getMessage();
}


?>

<!DOCTYPE html>
<html lang="ja">
<?php include("includes/head.php");?>
	<body>
	<?php include("includes/navbar.php");?>


	<div class="container">
		<?php if (isset($err)) : ?>
			<div class="alert alert-danger">
			<strong>Error : </strong> This page is under construction.
			</div>
		<?php else:?>
		

		<h2><?php echo $obj["title"]?> <small>aa</small></h2>
		<div class="panel panel-default">
			<div class="panel-heading"><h1 class="panel-title">Infomation</h1></div>
	<ul class="list-group">
		<li class="list-group-item">
		<strong>description: </strong>
		<?php echo $obj["description"]?>
		</li>
		<li class="list-group-item">
		<table>
		<?php 

		// $dscription_list=array("episode_num","air_date");
		$arr = array("episode_num","air_date","last_update", "homepage","distributer");
		foreach ($arr as $value) {
			echo "<tr>";
			echo "<td width='60%'><strong>".$value.": </strong></td>"."<td >".$obj[$value]."</td>";
			echo "</tr>";

		}
		?>
		</table>
		</li>
	</ul>
		</div>
		
		<div class="panel panel-default">
			<div class="panel-heading"><h1 class="panel-title">Title list</h1></div>
			<table class="table table-hover ">
				<tbody>
						<?php foreach ($obj["episodes"] as $key => $val): ?>
							<tr data-toggle="collapse" data-target='#<?php echo "accordion".$val["episode_ind"] ?>' class="clickable">
							<td># <?php echo $val["episode_ind"] ?></td>
							<td ><?php echo $val["name"] ?></td>
							<td align="right">

								<div class="btn-group" >
								<a href="#" class="btn btn-default btn-sm">DL</a>
								<a href="#" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
								<span class="caret"></span>
								</a>
								<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
								<li><a tabindex="-1" href="#">Action</a></li>
								<li><a tabindex="-1" href="#">Another action</a></li>
								<li class="divider"></li>
								<li><a tabindex="-1" href="#">Separated link</a></li>
								</ul>
								</div>

							</td>

							</tr>

							<!-- <tr>
								<td colspan="2" class="hiddenRow" style="padding: 0;">
								<div id='<?php echo "accordion".$val["episode_ind"] ?>' class="collapse">
								
								<div class="panel-body">
									<?php foreach ($val["urls"] as $key => $url): ?>
										<a href=" <?php echo $url ?>"> <?php echo $url ?> </a><br>			
									<?php endforeach; ?>
								
								</div>
								</div>
								</td>
							</tr> -->
						
					   <?php endforeach; ?>
				</tbody>
			</table>
		</div>

			</div>
		<?php endif; ?>

	</div>


	<?php include("includes/footer.php");?>
	</body>
</html>