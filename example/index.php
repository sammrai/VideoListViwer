<!DOCTYPE html>
<HTML>
<HEAD>
<META http-equiv="Content-Type" content="text/html; charset=utf-8" />
<TITLE>サンプル</TITLE>
</HEAD>
</BODY>
 
<?php
  $cmd = (isset($_POST['cmd']))? $_POST['cmd'] : 0;
  if($cmd > 0){
    if(is_uploaded_file($_FILES['upfile']['tmp_name'])){
      move_uploaded_file($_FILES['upfile']['tmp_name'], 'tmp.csv');
      exec("python csv_calc.py tmp.csv $cmd", $resp, $code);
      echo $resp[0];
      unlink('tmp.csv');
    }
  }
?>
 
<hr />
<form enctype="multipart/form-data" action="./" method=post>
<input type=file name=upfile><br />
<select name=cmd>
  <option value=1>合計値
  <option value=2>平均値
  <option value=3>最大値
  <option value=4>最小値
</select>
<input type=submit value="計算実行">
</form>
 
</BODY>
</HTML>