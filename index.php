<?php 
require "includes/common.php"; 

try{
	$db_dir = "json2sqldata/database.db";
	$con =sqlite_open($db_dir);
	$page = getCurrentPage();
	$limit = $ini_array["limit"];
	$order = getOrderQuery();
	$searchword = getKeySearchQuery();
	$ret = getobj($con, $page, $limit, $order, $searchword);
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
				<strong>Error : </strong>
				<?php echo $err; ?></div>
			<?php else : ?>
				<div class="alert alert-success">
				<strong>
				<i class="glyphicon glyphicon-ok"></i> Success! :
				</strong><?php echo sprintf("%s",$ret["recnum_all"]) ?> valid titles were found. 
				</div>

			<div class="container">
				<h2>Title list </h2>
				<!-- <p>The .table-hover class enables a hover state on table rows:</p>             -->
				<table class="table table-hover ">
					<thead>
						<tr>
							<th><?= get_sorttag("title"); ?> </th>
							<th><?= get_sorttag("tag"); ?> </th>
							<th><?= get_sorttag("air_date"); ?> </th>
							<th><?= get_sorttag("downloaded"); ?> </th>
						</tr>
					</thead>
					<tbody>
						<?php 
							$taglist = [
									    "drama" => "label-primary",
									    "anime" => "label-success",
									    "variery" => "label-warning",];
							foreach ($ret["obj"] as $key => $val){
								echo "<tr>";

								echo '<td width="60%">'.$val["title"]."</td>";
						
								echo '<td>';
								foreach (asarray($val["tag"]) as $res){
									echo sprintf('<a class="label %s" href=%s>%s</a>&nbsp;',$taglist[$res],get_tag_query("tag",$res),$res);}
								echo "</td>";

								echo "<td>".$val["air_date"]."</td>";
								echo '<td align="center"><span class="badge ">'.$val["downloaded"]."</span></td>";
								echo "</tr>";
							}
						   ?>
					</tbody>
				</table>
				<nav align="center">
					<ul class="pagination">
						<?php endif; ?><?= pagination($ret["recnum"], $page, $limit); ?> 
					</ul>
				</nav>
			</div>
		</div>

 	<?php include("includes/footer.php");?>
	</body>
</html>