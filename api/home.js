function initialize() {
  var mapProp = {
    center:new google.maps.LatLng(4.5,-74.0),
    zoom:5,
    mapTypeId:google.maps.MapTypeId.ROADMAP
  };
  var map=new google.maps.Map(document.getElementById("googleMap"),mapProp);
  
  var ctaLayer = new google.maps.KmlLayer({
    url: 'http://test.chlewey.net/maps/COL_adm1.kmz'
  });
  ctaLayer.setMap(map);

}
google.maps.event.addDomListener(window, 'load', initialize);
