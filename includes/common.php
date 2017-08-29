<?php 

function sqlite_open($location){ 
	#ファイル存在確認
	if (!file_exists($location)){
		throw new Exception('Database file doesn\'t exist. "'.$location.'"');}

	#データベース接続
    $handle = new SQLite3($location); 
    try {
	    $handle->enableExceptions(true);
	} catch (Exception $e) {
	    $err = $e->getMessage();
	}
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

function getOrderQuery(){	
	$sort = filter_input(INPUT_GET, 'sort');
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

	return [ "obj" => array_slice($items, $page * $limit, $limit), "recnum" => count($items), "recnum_all" => $record_num];

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


function get_script_name(){
	$script_dir = ($_SERVER["REQUEST_URI"]);
	$script_name =  explode("/",$script_dir);
	$script_name = (end($script_name));
	return $script_name;
}

function is_empty($var){
	$judge = array_filter($var);
	if(empty($judge)){
	return true;
	}else{
	return false;
	}
}


ini_set('display_errors',3000);
error_reporting(E_ALL);
$config_file ="config.ini";
$ini_array = parse_ini_file($config_file);
$DL_DIR=$ini_array["download_dir"];

?>

