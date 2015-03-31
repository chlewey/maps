<?php
ob_start();
echo '<?xml version="1.0" encoding="UTF-8" standalone="no"?>';
?>
<svg id="colombia" height="2100" width="1700" xmlns:svg="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" version="1.1" onload="init(evt)">
	<script id="svgpan" xlink:href="SVGPan.js" />
	<script id="funcs" type="application/ecmascript"><![CDATA[
var slabel;
var xlinkns;
function init(evt) {
	if ( window.svgDocument == null ) {
		svgDoc = evt.target.ownerDocument;
	}
	slabel = svgDoc.getElementById('slabel');
	xlinkns = "http://www.w3.org/1999/xlink";
}
function showdata(name) {
	svgDoc.getElementById('slabel').setAttributeNS(xlinkns,"href",name);
}
]]></script>
	<defs id="defs">
		<path style="opacity:0.75;fill:white;stroke:black" d="m0,0 25,40v85a25,25 0 0 0 25,25h170a25,25 0 0 0 25,-25v-75a25,25 0 0 0 -25,-25h-170z" id="label" />
<?php
require_once "lib.php";
$c = readcsv('deptos.csv',null,null,1);
foreach($c as $k=>$l) {
	$r = file_get_contents($fn = $l['uname'].'.path.2');
	$p = explode("\n",$r);
	$u = array_pop($p);
	if(!empty($u)) array_push($p,$u);
	$d = 'M'.implode('zM',$p).'z';
	$c[$k]['count'] = 0;
	$c[$k]['valor'] = 0.0;
?>
		<path id="<?=$k?>" d="<?=$d?>" />
<?php
}
$res = readcsv('resmap.csv',null,2);
$count = 0;
$valor = 0.0;
$maxn = 0;
$maxval = 0.0;
foreach($res as $item) {
	$depto = $item['Departamento'];
	$code = name2code($depto);
	$y = (int)$item['Año'];
	$m = (int)$item['Mes'];
	$date = sprintf('%04d-%02d',$y,$m);
	if(isset($_GET['dateini']) && $date<$_GET['dateini']) continue;
	if(isset($_GET['datefin']) && $date>$_GET['datefin']) continue;
	$continue = false;
	foreach($trans as $k1=>$k2) {
		if(isset($_GET[$k1]) && $item[$k2]!=$_GET[$k1]) $continue=true;
	}
	if($continue) continue;
	$n = (int)$item['Creditos'];
	$val = (float)str_replace(',','',$item['Valor Desembolsado']);
	$pq = $c[$code]['count']+=$n;
	$pv = $c[$code]['valor']+=$val;
	if($pq>$maxn) $maxn = $pq;
	if($pv>$maxval) $maxval = $pv;
	$count+=$n;
	$valor+=$val;
}
foreach($c as $k=>$l) {
?>
		<g id="lab-<?=$k?>" transform="translate(<?=$l['midx']?>,<?=$l['midy']?>)">
			<use xlink:href="#label" />
			<text xml:space="preserve" style="font-size:15px;text-align:center;text-anchor:middle;font-family:Sans" x="135" y="50"><tspan x="135" y="50"><?=$l['name']?></tspan></text>
			<text xml:space="preserve" style="font-size:14px;font-family:Sans" x="30" y="70"><tspan x="30" y="70">Créditos:</tspan></text>
			<text xml:space="preserve" style="font-size:14px;font-family:Sans" x="98" y="70"><tspan x="98" y="70"><?=number_format($l['count'],0,',',' ')?></tspan></text>
			<text xml:space="preserve" style="font-size:14px;font-family:Sans" x="30" y="90"><tspan x="30" y="90">Valor:  $</tspan></text>
			<text xml:space="preserve" style="font-size:14px;font-family:Sans" x="98" y="90"><tspan x="98" y="90"><?=number_format($l['valor'],2,',',' ')?></tspan></text>
		</g>
<?php
}
$kk = array_keys($c);
?>
		<g id="lab-todo" transform="translate(850,1050)">
			<use xlink:href="#label" />
			<text xml:space="preserve" style="font-size:16px;text-align:center;text-anchor:middle;font-family:Sans" x="135" y="50"><tspan x="135" y="50">Colombia</tspan></text>
			<text xml:space="preserve" style="font-size:14px;font-family:Sans" x="30" y="70"><tspan x="30" y="70">Créditos:</tspan></text>
			<text xml:space="preserve" style="font-size:14px;font-family:Sans" x="98" y="70"><tspan x="98" y="70"><?=number_format($count),0,',',' '?></tspan></text>
			<text xml:space="preserve" style="font-size:14px;font-family:Sans" x="30" y="90"><tspan x="30" y="90">Valor:  $</tspan></text>
			<text xml:space="preserve" style="font-size:14px;font-family:Sans" x="98" y="90"><tspan x="98" y="90"><?=number_format($valor,2,',',' ')?></tspan></text>
		</g>
	</defs>
	<metadata id="meta" />
	<g id="viewport">
		<rect x="0" y="0" width="1700" height="2300" style="fill:#39c" onmouseover="showdata('#lab-todo')" />
<?php

$mln = log($maxn);
$mlv = log($maxval);
foreach($c as $k=>$v) {
	if($v['count']==0 && $v['valor']==0.0) {
		$r = $g = $b = 204;
	} else {
		$dn = log($v['count'])/$mln;
		$dv = log($v['valor'])/$mlv;
		$r = round(11+2*$dn+2*$dv);
		$g = round(11*(1.0-$dn));
		$b = round(11*(1.0-$dv));
	}
	$color = sprintf("#%1x%1x%1x",$r,$g,$b);
?>
		<use xlink:href="#<?=$k?>" style="fill:<?=$color?>;stroke:black;stroke-width:0.3px" onmouseover="showdata('#lab-<?=$k?>')" />
<?php
	$hue += $cstep;
}

?>
		<use id="slabel" xlink:href="#lab-todo" />
	</g>
</svg>
<?php
$svg = ob_get_clean();
$svgz = gzencode($svg,9);
header('Content-type: image/svg+xml');
header('Content-encoding: gzip');
echo $svgz;

$fn = 'colombia.'.base64_encode(time());
file_put_contents("$fn.svg",$svg);
file_put_contents("$fn.svgz",$svgz);
?>
