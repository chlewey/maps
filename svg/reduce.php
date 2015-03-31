<?php
header('Content-type: text/plain');
require_once "lib.php";

function tocord($a) {
	return explode(',',$a);
}

function is_medial($a,$b,$c) {
	$a = tocord($a);
	$b = tocord($b);
	$c = tocord($c);
	return ($c[1]-$b[1])*($b[0]-$a[0]) == ($c[0]-$b[0])*($b[1]-$a[1]);
}

$c = readcsv('deptos.csv',null,null,1);
foreach($c as $k=>$l) {
	$r = file_get_contents($fn = $l['uname'].'.path');
	$f = fopen("$fn.2","w");
	$p = explode("\n",$r);
	$i = 0;
	foreach($p as $q) {
		$l = array();
		if(empty($q)) continue;
		$o = explode(" ",$q);
		$u = count($o);
		$h = array_shift($o);
		$l[] = $h;
		$m = array_shift($o);
		while(!empty($o)) {
			$n = array_shift($o);
			if(is_medial($h,$m,$n)) {
				$m = $n;
			} else {
				$l[] = $m;
				$h = $m;
				$m = $n;
			}
		}
		$l[] = $m;
		$v = count($l);
		if($v>3)
			fwrite($f, implode(' ',$l).chr(10) );
		echo "$k ($i): $u points => $v points.\n";
		$i++;
	}
	fclose($f);
}
?>
