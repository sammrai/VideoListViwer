<?php 

function sqlite_open($location) 
{ 
    $handle = new SQLite3($location); 
    return $handle; 
} 

function getCurrentPage()
{
    return (int) filter_input(INPUT_GET, 'page');
}

function asarray($str){
	return explode(';', $str);
	// try{return explode('[;]', $str);
	// }catch(Exception $e){
	// 	return $str;
	// }
}
	

function getobj($db_dir, $page = 0, $limit = null,  array $order = null){
	#get database controller
	$con=sqlite_open($db_dir);

	#cuont all records
	$record_num = $con->query("SELECT count(*) as count FROM titles")->fetchArray()["count"];

	#set default sql request
	$sql = "SELECT *  FROM titles";

	#オーダーが指定されている時
    if (!is_null($order)) {
        $orderToken = [];
        foreach ($order as $field => $value) {
            $orderToken[] = sprintf('%s %s', $field, $value);
        }
        $orderStr = implode(', ', $orderToken);
        $sql .= " ORDER BY {$orderStr}";
    }

    #limit数が指定されている時
    if (!is_null($limit)) {
        $offset = $page * $limit;
        $sql .= sprintf(' LIMIT %d OFFSET %d', $limit, $offset);
    }

    $items = [];
	$result = $con->query($sql);
	while($row=$result->fetchArray()){
		$items[] = $row;
	}

	return [ "obj" => $items, "recnum" => $record_num];

}

function pagination($recnum, $page, $limit){
	$count = $recnum;

    //レコード総数がゼロのときは何も出力しない
    if (0 === $count) {
        return '';
    }

    //現在表示中のページ番号（ゼロスタート）
    $intCurrentPage = getCurrentPage();

    //ページの最大数
    $intMaxpage = ceil($count / $limit);

    //現在ページの前後３ページを出力
    $intStartpage = (2 < $intCurrentPage) ? $intCurrentPage - 3 : 0;
    $intEndpage = (($intStartpage + 7) < $intMaxpage) ? $intStartpage + 7 : $intMaxpage;

    //url組み立て
    $urlparams = filter_input_array(INPUT_GET);

    //表示中のページが先頭ではない時
    if (0 < $intCurrentPage) {
        $urlparams['page'] = $intCurrentPage - 1;
        $items[] = sprintf('<li><a href="?%s">%s</a></li>'
            , http_build_query($urlparams)
            , '<'
        );
    }

    for ($i = $intStartpage; $i < $intEndpage; $i++) {
        $urlparams['page'] = $i;
        $items[] = sprintf('<li%s><a href="?%s">%s</a></li>'
            , ($intCurrentPage == $i) ? ' class="active"' : ''
            , http_build_query($urlparams)
            , $i + 1
        );
    }

    //表示中のページが最後ではない時
    if ($intCurrentPage < $intMaxpage and $limit<$recnum and $intCurrentPage!=$intMaxpage-1) {
        $urlparams['page'] = $intCurrentPage + 1;
        $items[] = sprintf('<li><a href="?%s">%s</a></li>'
            , http_build_query($urlparams)
            , '>'
        );
    }

    return sprintf('<div class="pagination">%s</div>', implode(PHP_EOL, $items));
}

ini_set('display_errors',3000);
error_reporting(E_ALL);

$db_dir = "json2sqldata/database.db";
$page = getCurrentPage();
$limit = 30;
$order = null;#['title' => 'asc', 'tag' => 'asc' ];

$ret = getobj($db_dir, $page, $limit, $order);
$obj = $ret["obj"];
$recnum = $ret["recnum"];

 ?>

<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Bootstrapの基本テンプレート</title>
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
		<nav class="navbar navbar-inverse">
		  <div class="container-fluid">
		    <div class="navbar-header">
		      <a class="navbar-brand" href="#">VideoListViwer</a>
		    </div>
		    <ul class="nav navbar-nav">
		      <li class="active"><a href="#">Home</a></li>
		      <!-- <li><a href="#">Downloaded <span class="badge ">26</span></a></li> -->
		      <!-- <li valign="center"><a href="#"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span></a></li> -->

		    </ul>
		    <ul class="nav navbar-nav navbar-right">
			  <li>
			  	<form class="navbar-form navbar-left">
				  <div class="input-group">
				    <input type="text" class="form-control" placeholder="Search">
				    <div class="input-group-btn">
				      <button class="btn btn-default" type="submit">
				        <i class="glyphicon glyphicon-search"></i>
				      </button>
				    </div>
				  </div>
				</form>
				</li>
		    </ul>
		  </div>
		</nav>
		<div class="container">
			<div class="alert alert-success">
			<strong>Success!</strong> Indicates a successful or positive action.
		</div>
		<div>

		<dir class="container">
			<h2>Title list</h2>
				  <!-- <p>The .table-hover class enables a hover state on table rows:</p>             -->
				<table class="table table-hover ">
					  <thead>
					    <tr>
					      <th>Title</th>
					      <th>
					      <a href="https://www.google.co.jp">tag</a>
					      </th>
					      <th>Air date</th>
					      <th>downloaded</th>
					    </tr>
					  </thead>
					  <tbody>
						<?php 
						// $jsonpath = "title.json";
						// // echo file_exists($jsonpath);
						// $json = file_get_contents($jsonpath);
						// $json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
						// $obj = json_decode($json,true);
						// $obj = $obj;
						$taglist = [
								    "drama" => "label-primary",
								    "anime" => "label-success",
								    "variery" => "label-warning",];

						foreach ($obj as $key => $val){
							echo "<tr>";

							echo '<td width="70%">'.$val["title"]."</td>";
					
							echo "<td>";
							foreach (asarray($val["tag"]) as $res){
								echo '<span class="label '. $taglist[$res] .'">'.$res.'</span>&nbsp;';}
							echo "</td>";

							echo "<td>".$val["day"]."</td>";
							echo '<td align="center"><span class="badge ">'.$val["story_num"]."</span></td>";
							echo "</tr>";
						}
					   ?>



	  				  </tbody>
				</table>
				<nav align="center">
					<ul class="pagination">
					<?= pagination($recnum, $page, $limit); ?>
					</ul>
				</nav>

		</dir>


	</div>
<!-- <p>今日は：<?php date_default_timezone_set('Asia/Tokyo'); echo date(‘Y年m月d日’); ?>です。</p>
 -->	</body>
</html>