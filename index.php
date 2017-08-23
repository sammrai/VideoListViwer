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
		      <li><a href="#">Downloaded <span class="badge ">26</span></a></li>
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
					      <a href="https://www.google.co.jp"></a>
					      </th>
					      <th>Air date</th>
					      <th>Ep</th>
					    </tr>
					  </thead>
					  <tbody>
						<?php 
						$jsonpath = "title.json";
						echo file_exists($jsonUrl);
						$json = file_get_contents($jsonpath);
						$json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
						$obj = json_decode($json,true);
						$obj = $obj["titles"];
						$taglist = [
								    "drama" => "label-primary",
								    "anime" => "label-success",
								    "variery" => "label-warning",];

						foreach ($obj as $key => $val){
							echo "<tr>";

							echo '<td width="70%">'.$val["title"]."</td>";
					
							echo "<td>";
							foreach ($val["tag"] as $res){
								echo '<span class="label '. $taglist[$res] .'">'.$res.'</span>&nbsp;';}
							echo "</td>";

							echo "<td>".$val["day"]."</td>";
							echo '<td><span class="badge ">'.$val["story_num"]."</span></td>";
							echo "</tr>";
						}
					   ?>



	  				  </tbody>
					</table>
		
		</dir>


	</div>
<!-- <p>今日は：<?php date_default_timezone_set('Asia/Tokyo'); echo date(‘Y年m月d日’); ?>です。</p>
 -->	</body>
</html>