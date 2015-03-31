<?php
header('Content-type: text/plain');

require_once "lib.php";

$kml = simplexml_load_file('../deptosdc.kml');
$c = readcsv('deptos.csv',null,null,1);
$deptos = array_keys($c);
for($i=0;!is_null($x = $kml->Document->Placemark[$i]);$i++) {
	$name = $x->name;
	$fn = name2fn($name);
	$code = fn2code($fn); 
	$fn = $c[$code]['uname'].'.path';

	$f = fopen($fn,'w');
	for($j=0;!is_null($y = $x->MultiGeometry->Polygon[$j]);$j++) {
		$z = trim((string)($y->outerBoundaryIs->LinearRing->coordinates));
		$w = preg_split("{\s+}u",$z);
		array_walk($w,function(&$u,$i){
			$p = explode(',',$u);
			$x = round((82+$p[0])*111.1);
			$y = round(1600-$p[1]*111.1);
			$u = "$x,$y";
		});
		$p = implode(' ',$w).chr(10);
		fwrite($f,$p);
	}
	fclose($f);
	echo "$code: $j path".($j!=1?'s':'').".\n";
}

$res = readcsv('resmap.csv',null,2);
foreach($res as $r) {
	$depto = $r['Departamento'];
	$fn = name2fn($depto);
	$code = fn2code($fn);
	if(!in_array($code,$deptos))
		echo "$code,$fn,$depto\n";
}

?>
