<?php
header('Content-type: image/svg+xml');
ob_start();
?><?xml version="1.0" encoding="UTF-8" standalone="no"?>
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
function showitem(item) {
	svgDoc.getElementById(item).setAttributeNS(null,"opacity","1");
}
function hideitem(item) {
	svgDoc.getElementById(item).setAttributeNS(null,"opacity","0");
}
function showdata(name) {
	svgDoc.getElementById('slabel').setAttributeNS(xlinkns,"href",name);
}
]]></script>
	<defs id="defs">
		<path style="opacity:0.75;fill:white;stroke:black" d="m0,0 25,40v85a25,25 0 0 0 25,25h100a25,25 0 0 0 25,-25v-75a25,25 0 0 0 -25,-25h-100z" id="label" />
<?php

$xml = simplexml_load_file('../COL_adm1.kml');
$depto = array();
$minmax = array();
$med = array();
for($i=0;!is_null($x = $xml->Document->Placemark[$i]);$i++) {
	$depto[$i+1] = $x->name;
	$ix = sprintf('%02d',$i+1);
	$id = "depto$ix";
	echo "\t\t<path id=\"$id\" d=\"";
	$glx=10000;
	$gly=10000;
	$ggx=-1000;
	$ggy=-1000;
	for($j=0;!is_null($y = $x->MultiGeometry->Polygon[$j]);$j++) {
		$z = trim((string)($y->outerBoundaryIs->LinearRing->coordinates));
		$w = preg_split("{\s+}u",$z);
		array_walk($w,function(&$u,$i){
			global $glx,$gly,$ggx,$ggy;
			$p = explode(',',$u);
			$x = round((82+$p[0])*111.1);
			$y = round(1600-$p[1]*111.1);
			if($x<$glx) $glx=$x;
			if($y<$gly) $gly=$y;
			if($x>$ggx) $ggx=$x;
			if($y>$ggy) $ggy=$y;
			$u = "$x,$y";
		});
		echo "M".implode(' ',$w)."z";
		$minmax[$i+1] = array($glx,$gly,$ggx,$ggy);
		$med[$i+1] = array(($glx+$ggx)/2,($gly+$ggy)/2);
	}
	echo "\" />\n";
	?>
		<g id="label<?=$ix?>" transform="translate(<?=round(($glx+$ggx)/2)?>,<?=round(($gly+$ggy)/2)?>)">
			<use xlink:href="#label" />
			<text xml:space="preserve" style="font-size:15px;text-align:center;text-anchor:middle;font-family:Sans" x="100" y="50"><tspan x="100" y="50"><?=$x->name?></tspan></text>
			<text xml:space="preserve" style="font-size:14px;text-align:center;text-anchor:middle;font-family:Sans" x="100" y="70"><tspan x="100" y="70">Valor 1</tspan></text>
			<text xml:space="preserve" style="font-size:14px;text-align:center;text-anchor:middle;font-family:Sans" x="100" y="90"><tspan x="100" y="90">Valor 2</tspan></text>
		</g>
<?php
}
$n=$i;
?>
		<g id="label00" transform="translate(850,1050)">
			<use xlink:href="#label" />
			<text xml:space="preserve" style="font-size:16px;text-align:center;text-anchor:middle;font-family:Sans" x="100" y="50"><tspan x="100" y="50">Colombia</tspan></text>
			<text xml:space="preserve" style="font-size:14px;text-align:center;text-anchor:middle;font-family:Sans" x="100" y="70"><tspan x="100" y="70">Valor 1</tspan></text>
			<text xml:space="preserve" style="font-size:14px;text-align:center;text-anchor:middle;font-family:Sans" x="100" y="90"><tspan x="100" y="90">Valor 2</tspan></text>
		</g>
	</defs>
	<metadata id="meta" />
	<g id="viewport">
		<rect x="0" y="0" width="1700" height="2300" style="fill:#39c" onmouseover="showdata('#label00')" />
<?php
for($i=1;$i<=$n;$i++) {
	$ix = sprintf('%02d',$i);
?>
		<rect x="<?=$minmax[$i][0]?>" y="<?=$minmax[$i][1]?>" width="<?=$minmax[$i][2]-$minmax[$i][0]?>" height="<?=$minmax[$i][3]-$minmax[$i][1]?>" opacity="0" onmouseover="showdata('#label<?=$ix?>')" />
<?php
}

$cstep = 2.9999/$n;
$hue = 0;
$cfmt = array('#%02x%02x%02x','#%3$02x%1$02x%2$02x','#%2$02x%3$02x%1$02x','#%02x%02x%02x');
#$cfmt = array('rgba(%d,%d,%d,1)','rgba(%3$d,%1$d,%2$d,1)','rgba(%2$d,%3$d,%1$d,1)','rgba(%d,%d,%d,1)');
for($i=1;$i<=$n;$i++) {
	$o = floor($hue);
	$u = $hue-$o;
	$r = 255;
	$g = floor(510.999*$u);
	$b = 0;
	if($g>255) {
		$r = 510-$g;
		$g = 255;
	}
	$color = sprintf($cfmt[$o],$r,$g,$b);
	$ix = sprintf('%02d',$i);
?>
		<use xlink:href="#depto<?=$ix?>" style="fill:<?=$color?>;stroke:black" onmouseover="showdata('#label<?=$ix?>')" />
<?php
	$hue += $cstep;
}
?>
		<use id="slabel" xlink:href="#label00" />
	</g>
</svg>
<?php
$svg = ob_get_clean();
$svgz = gzencode($svg,9);
header('Content-encoding: gzip');
echo $svgz;

$fn = 'colombia.'.base64_encode(time());
file_put_contents("$fn.svg",$svg);
file_put_contents("$fn.svgz",$svgz);
?>
