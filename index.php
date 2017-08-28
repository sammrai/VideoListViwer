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

function getKeySearchQuery()
{
    $s_word = filter_input(INPUT_GET, 's_word');
	$s_key = filter_input(INPUT_GET, 's_key');

	if (empty($s_word)){
		return null;
	}else{
	    return [$s_key => $s_word];
	}
    
}

function getOrderQuery()
{	$sort = filter_input(INPUT_GET, 'sort');
	$order = filter_input(INPUT_GET, 'order');

	if (empty($sort)){
		return null;
	}else{
	    return [$sort => $order];
	}
    
}

function asarray($str){
	return explode(';', $str);
}
	

function getobj($con, $page = 0, $limit = null, array $order = null, $searchword=null){
	#cuont all records
	$record_num = $con->query("SELECT count(*) as count FROM titles")->fetchArray()["count"];

	#set default sql request
	$sql = "SELECT *  FROM titles";
	
	#検索ワードが指定されている時
    if (!is_null($searchword)) {
        foreach ((array)($searchword) as $s_key => $s_word) {
            $sql .= sprintf(' WHERE %s LIKE "%%%s%%"',$s_key, $s_word);
        }
    }

	#オーダーが指定されている時
    if (!is_null($order)) {
        $orderToken = [];
        foreach ($order as $field => $value) {
            $orderToken[] = sprintf('%s %s', $field, $value);
        }
        $orderStr = implode(', ', $orderToken);
        $sql .= " ORDER BY {$orderStr}";
    }

    // #limit数が指定されている時
    // if (!is_null($limit)) {
    //     $offset = $page * $limit;
    //     $sql .= sprintf(' LIMIT %d OFFSET %d', $limit, $offset);
    // }

    $items = [];
	$result = $con->query($sql);
	while($row=$result->fetchArray()){
		$items[] = $row;
	}

	return [ "obj" => $items, "recnum" => count($items), "recnum_all" => $record_num];

}

function pagination($recnum, $page, $limit){
    //レコード総数がゼロのときは何も出力しない
    if (0 === $recnum) {
        return '';
    }

    //現在表示中のページ番号（ゼロスタート）
    $intCurrentPage = getCurrentPage();

    //ページの最大数
    $intMaxpage = ceil($recnum / $limit);

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

function get_sorttag($tag, $name = null){
	if (!isset($name)){$name = $tag;}
	$order=filter_input(INPUT_GET, 'order');

	if (empty($order) or $order=="desc"){
		$order = "asc";
	}else{
		$order = "desc";
	}

	$searchword_=getKeySearchQuery();
    if (!is_null($searchword_)) {
        foreach ((array)($searchword_) as $s_key => $s_word) {
	        $searchword_ = "s_key=".$s_key."&"."s_word=".$s_word."&";
        }
    }

	echo sprintf('<a href="?%ssort=%s&order=%s">%s</a>',$searchword_,$tag,$order,$name);
}


function get_tag_query($s_key, $s_word){
	$tag_arr = [$s_key => $s_word];
    $query = "?s_key=".$s_key."&"."s_word=".$s_word."&";
    return $query;
}

try{

	ini_set('display_errors',3000);
	error_reporting(E_ALL);

	$db_dir = "json2sqldata/database.db";
	$con =sqlite_open($db_dir);
	$page = getCurrentPage();
	$limit = 15;
	$order = getOrderQuery();
	$searchword = getKeySearchQuery();

	$ret = getobj($con, $page, $limit, $order, $searchword);

} catch (Exception $e) {
    $err = $e->getMessage();
}

 ?>
<!DOCTYPE html>
<html lang="ja">
	<head>
		<title>Bootstrap Example </title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	</head>
	<body>
		<nav class="navbar navbar-default banner">
			<div class="container-fluid">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
				<span class="sr-only">Toggle navigation </span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="."><i class="glyphicon glyphicon-align-justify"></i> VideoListViwer</a>
			</div>
			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<form class="navbar-form navbar-right" method="get" action="." >
				<div class="input-group">
				<input type="hidden" name="s_key", value="title">
				<input type="text" class="form-control" placeholder="Search" name="s_word" value=<?php echo (array_key_exists("title",(array)getKeySearchQuery()))?getKeySearchQuery()["title"] :"" ; ?> >
				<div class="input-group-btn">
				<button class="btn btn-default" type="submit">
					<i class="glyphicon glyphicon-search"></i>
				</button>
				</ul>
				</div>
				</div>
			</form>
			<ul class="nav navbar-nav navbar-left navbar-nav-primary">
				<li class="active"><a href=".">Home </a></li>
				<li ><a href="#">Setting</a></li>
				<li><a href="#">Downloading <span class="badge ">2 </span></a></li>
			</ul>
			</div>
			<!-- /.navbar-collapse -->
			</div>
			<!-- /.container-fluid -->
		</nav>

		<div class="container">
			<?php if (isset($err)) : ?>
			<div class="alert alert-danger">
			<strong>Database Error : </strong>Database file not found.
			<?php else : ?>
			<div class="alert alert-success">
			<strong>
				<i class="glyphicon glyphicon-ok"></i>Database connection :
			</strong><? echo sprintf("%s",$ret["recnum_all"]) ?> valid titles were found. 
		</div>

		<dir class="container">
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
						$i=0;
						foreach ($ret["obj"] as $key => $val){
							if($i==$limit){break;}
							echo "<tr>";

							echo '<td width="60%">'.$val["title"]."</td>";
					
							echo '<td>';
							foreach (asarray($val["tag"]) as $res){
								echo sprintf('<a class="label %s" href=%s>%s</a>&nbsp;',$taglist[$res],get_tag_query("tag",$res),$res);}
							echo "</td>";

							echo "<td>".$val["air_date"]."</td>";
							echo '<td align="center"><span class="badge ">'.$val["downloaded"]."</span></td>";
							echo "</tr>";
							$i++;
						}
					   ?>
				</tbody>
			</table>
			<nav align="center">
				<ul class="pagination">
					<?php endif; ?><?= pagination($ret["recnum"], $page, $limit); ?> 
				</ul>
			</nav>
		</dir>

		<dir class="container">
			<footer align="center">
			<p class="text-muted"><a href="https://github.com/shun-sa/VideoListViwer">Github</a></p>
			</footer>
		</dir>
	</body>
</html>