<?php
if ((strpos($script,'/'))!==false) {
	$mapfile=$script;
} else {
	$mapfile='http://test.chlewey.net/maps/'.$script;
}
?>

function initialize() {
  var mapProp = {
    center:new google.maps.LatLng(4.5,-74.0),
    zoom:5,
    mapTypeId:google.maps.MapTypeId.ROADMAP
  };
  var map=new google.maps.Map(document.getElementById("googleMap"),mapProp);
  
  var ctaLayer = new google.maps.KmlLayer({
    url: '<?=$mapfile?>'
  });
  ctaLayer.setMap(map);

}
google.maps.event.addDomListener(window, 'load', initialize);
<?php ?>
