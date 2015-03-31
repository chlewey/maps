<!DOCTYPE html>
<html>
<head>
<meta charset=utf-8>
<meta name=viewport content="width=device-width, initial-scale=1.0">
<?php
require_once "lib.php";
$title = "Prueba de concepto";
$subtitle = array();
$query = array();
foreach($_POST as $key=>$val) {
	if(!empty($val) && $val!=-1) {
		$query[$key] = $val;
		if(isset($trans[$key])) {
			$subtitle[] = $trans[$key].'="'.$val.'"';
		} elseif($key=='dateini') {
			$subtitle[] = 'desde="'.$val.'"';
		} elseif($key=='datefin') {
			$subtitle[] = 'hasta="'.$val.'"';
		} else {
			$subtitle[] = $key.'="'.$val.'"';
		}
	}
}
$subtitle = empty($subtitle)? 'Inicio': implode(' - ',$subtitle);

?>
<title><?="$title | $subtitle"?></title>
<link rel=stylesheet type="text/css" href="/bs/css/bootstrap.min.css">
<link rel=stylesheet type="text/css" href="my.css">
<style></style>
</head>
<?php
$general = array(
	'date' => array(),
	'intermediario' => array(),
	'municipio' => array(),
	'linea' => array(),
	'programa' => array(),
	'actividad' => array(),
	'tipo' => array(),
	'productor' => array(),
);

function array_add_to(array &$arr,$key,$val,$n=1) {
	if(!isset($arr[$key])) $arr[$key] = array();
	for($i=0;$i<$n;$i++)
		$arr[$key][] = $val;
}

function array_add_to2(array &$arr,$key1,$key2,$val,$n=1) {
	if(!isset($arr[$key1])) $arr[$key1] = array();
	if(!isset($arr[$key1][$key2])) $arr[$key1][$key2] = array();
	for($i=0;$i<$n;$i++)
		$arr[$key1][$key2][] = $val;
}

$res = readcsv('resmap.csv',null,2);
for($i=0;$i<count($res);$i++) {												
	$item = $res[$i];
	$n = $item['Creditos'];
	$depto = $item['Departamento'];
	$code = name2code($depto);
	$y = (int)$item['Año'];
	$m = (int)$item['Mes'];
	array_add_to2($general['date'],$y,$m,$i,$n);
	foreach($trans as $k=>$v) {
		array_add_to($general[$k],$item[$v],$i,$n);
	}
}

function array_to_options(array $array,$def=null,$sep="",$assoc=true) {
	$ops = array();
	if($assoc) {
		if(is_null($def)) {
			$ops[] = '<option value="-1" selected=selected>Todos</option>';
		} elseif(!array_key_exists($array,$def)) {
			$ops[] = '<option value="'.$def.'" selected=selected>'.$def.'</option>';
		}
		foreach($array as $k=>$val) {
			$selected = $k===$def? ' selected=selected': '';
			$ops[] = "<option value=\"$k\"$selected>$val</option>";
		}
		return implode($sep,$ops);
	} else {
		if(is_null($def)) {
			$ops[] = '<option value="-1" selected=selected>Todos</option>';
		} elseif(!in_array($def,$array)) {
			$ops[] = '<option selected=selected>'.$def.'</option>';
		}
		foreach($array as $val) {
			$selected = $val===$def? ' selected=selected': '';
			$ops[] = "<option$selected>$val</option>";
		}
		return implode($sep,$ops);
	}
}
?>
<body>
	<div class=container>
		<div class="center-area">
			<h1><?=$title?></h1>
			<h2><?=$subtitle?></h2>
		</div>
		<form method=post class=form-horizontal role=form>
<?php
$months = ",enero,febrero,marzo,abril,mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre";
$months = explode(',',$months);
$t = "\t\t\t\t\t";
$options = array('<option value="">Todos</option>');
foreach($general['date'] as $year=>$data) {
	foreach($data as $month=>$values) {
		$options[] = "<option value=\"$year-".sprintf('%02d',$month)."\">{$months[$month]} $year</option>";
	}
}
?>
			<div class=form-group>
				<label for=dateini class="control-label col-sm-2">Desde</label>
				<div class="col-sm-10">
					<select name=dateini id=dateini>
						<?=implode("\n$t\t",$options)?>

					</select>
				</div>
			</div>

			<div class=form-group>
				<label for=datefin class="control-label col-sm-2">Hasta</label>
				<div class="col-sm-10">
					<select name=datefin id=datefin>
						<?=implode("\n$t\t",$options)?>

					</select>
				</div>
			</div>
<?php
	foreach($trans as $key=>$val) {
?>

			<div class=form-group>
				<label for=<?=$key?> class="control-label col-sm-2"><?=$val?></label>
				<div class="col-sm-10">
					<select name=<?=$key?> id=<?=$key?>>
						<?php
		$ops=array_keys($general[$key]);
		sort($ops);
		echo array_to_options($ops,null,"\n$t\t",false);
?>

					</select>
				</div>
			</div>
<?php } ?>

			<div class="form-group">        
				<div class="col-sm-offset-2 col-sm-10">
					<input type=submit class="btn btn-primary">
					<input type=reset class="btn btn-default">
				</div>
			</div>
		</form>
		<object id="testSVG" data="colombia.php?<?=htmlentities(http_build_query($query))?>" type="image/svg+xml" width="1700" height="2100" style="margin:1ex auto;background-color:#036;max-width:100%;height:auto">
		<pre>
<?php

print_r($query);
print_r($trans);

?>

		</pre>
	</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="/bs/js/bootstrap.min.js"></script>
</body>
</html>
