<?php 
require "includes/common.php"; 

function write_php_ini($array, $file){
    $res = array();
    // print_r( $array);
    foreach($array as $key => $val)
    {
        if(is_array($val))
        {
            $res[] = "[$key]";
            foreach($val as $skey => $sval) $res[] = "$skey = ".(is_numeric($sval) ? $sval : '"'.$sval.'"');
            $res[] = "";
        }
        else $res[] = "$key = ".(is_numeric($val) ? $val : '"'.$val.'"');
    }
    
    safefilerewrite($file, implode("\r\n", $res));
}

function safefilerewrite($fileName, $dataToSave){    
	if ($fp = fopen($fileName, 'w'))
    {
        $startTime = microtime(TRUE);
        do
        {            $canWrite = flock($fp, LOCK_EX);
           // If lock not obtained sleep for 0 - 100 milliseconds, to avoid collision and CPU load
           if(!$canWrite) usleep(round(rand(0, 100)*1000));
        } while ((!$canWrite)and((microtime(TRUE)-$startTime) < 5));

        //file was locked so now we can store information
        if ($canWrite)
        {            fwrite($fp, $dataToSave);
            flock($fp, LOCK_UN);
        }
        fclose($fp);
    }

}

$save=false;
$ini = parse_ini_file($config_file, true);


if (!is_empty($_POST)){
	$ini= array_merge($ini,$_POST);
	write_php_ini($ini, $config_file);
	$save=true;
}


?>
<!DOCTYPE html>
<html lang="ja">
<?php include("includes/head.php");?>
<body>
	<?php include("includes/navbar.php");?>
	
	<div class="container">
		<?php if ($save) : ?>
			<div class="alert alert-success alert-dismissible fade in">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
				<strong>
				<i class="glyphicon glyphicon-ok"></i> Success! :
				</strong> Settings saved.
			</div>
		<?php endif; ?>

		<div class="setting_page">
			<form method="post" action="">

				<?php foreach($ini as $section => $item): ?>
					<h3> <?php echo $section ?> </h3>
					<?php foreach($item as $key => $value): ?>
						<div class="input-group" style="padding: 0.2em;">
							<span class="input-group-addon"> <?php echo $key ?> </span>
							<input type="<?php echo is_numeric($value) ? "number":"text" ?>" class="form-control" value="<?php echo $value ?>"
							 name= <?php echo sprintf("%s[%s]",$section,$key) ?> >
						</div> 
					<?php endforeach; ?>
				<?php endforeach; ?>
					<nav align="center" class="botton_button">
						<input type="submit" class="btn btn-primary" value="Save">
					</nav>
			</form>
		</div>
		<h3> Debug message</h3>
			<?php echo excutecmd($PY_PATH,"",true);?>
	</div>

	<?php include("includes/footer.php");?>
</body>
</html>