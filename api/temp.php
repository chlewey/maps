<?php

function href($key="") {
	$base = "/maps/";
	return $key? "$base$key.php": $base;
}

function title2key($title,$alt='') {
	if(!$title) return $alt;
	$words = explode(' ',$title);
	return strtolower($words[0]);
}

function ahref($title,$key=False) {
	if($key===False) $key=title2key($title);
	return '<a href="'.href($key).'">'.$title.'</a>';
}
function lihref($title,$key=False,$compare='') {
	if($key===False) $key=title2key($title);
	return '<li'.($key==$compare?' class="active"':'').'>'.ahref($title,$key).'</li>';
}

function activeif($a,$b) {
	return $a==$b? ' class="active"': '';
}

function write_heading($title,$script=Null,$key="") {
	$project = "Google Maps API Sample";
	$pagetitle = $title? "$title @ $project": $project;
	$headtitle = $title? $title: $project;
	if(!$key) $key=title2key($title);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?=$pagetitle?></title>
	<link rel="stylesheet" href="/bs/css/bootstrap.min.css">
	<link href="my.css" rel="stylesheet">
	<script src="http://maps.googleapis.com/maps/api/js"></script>
<?php
if($script) {
	echo "<script>\n";
	if(substr($script,-3)=='.js') {
		echo include $script;
	}
	elseif(substr($script,-4,3)=='.km') {
		include "mapscript.php";
	}
	echo "</script>\n";
}
?>
</head>

<body>
	<nav class="navbar navbar-inverse navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="<?=href()?>"><?=$project?></a>
			</div>
			<div id="navbar" class="collapse navbar-collapse">
				<ul class="nav navbar-nav">
					<?=lihref('Home','',$key)?>
					
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Maps <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="<?=href('map')?>?map=COL_adm0">Colombia Country</a></li>
							<li><a href="<?=href('map')?>?map=COL_adm1">Colombia Department</a></li>
							<li><a href="<?=href('map')?>?map=COL_adm2">Colombia Municipalities</a></li>
						</ul>
					</li>
					<?=lihref('About',false,$key)?>

					<?=lihref('Contact',false,$key)?>

				</ul>
			</div><!--/.nav-collapse -->
		</div>
	</nav>

	<div class="container">

		<div class="center-area">
			<h1><?=$headtitle?></h1>
<?php
}

function write_footing() {
?>
		</div>

	</div><!-- /.container -->

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script src="/bs/js/bootstrap.min.js"></script>
</body>
</html> 
<?php
}
?>
