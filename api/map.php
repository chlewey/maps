<?php
include "temp.php";
$map=$_GET['map'];
if(!$map) $map='COL_adm0';
write_heading($map,"$map.kmz");
?>
	<div id="googleMap" style="width:600px;height:600px;"></div>
<?php
write_footing();
?>
