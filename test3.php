<!DOCTYPE html>
<html>
<head>
<script
src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDY0kkJiTPVd2U7aTOAwhc9ySH6oHxOIYM&sensor=false">
</script>

<script>
var myCenter=new google.maps.LatLng(40.668156, 22.930170);
var map="";

function addMarker(location){
	var marker=new google.maps.Marker({
		position:location,
		draggable:true,
		animation:google.maps.Animation.BOUNCE
	  });
	 
	//add infowindow
	var	infowindow=new google.maps.InfoWindow({
		content: 'Latitude: ' + marker.getPosition().lat() + '<br>Longitude: ' + marker.getPosition().lng()
	});
	
	//add Listeners
	google.maps.event.addListener(marker, "click", function () { map.panTo( marker.getPosition() );  });
	google.maps.event.addListener(marker, "mouseover", function () {
		infowindow.content='Latitude: ' + this.getPosition().lat() + '<br>Longitude: ' + this.getPosition().lng();
		infowindow.open(map,this);   });
	google.maps.event.addListener(marker, "mouseout", function () {infowindow.close(map,this);   });
	
	
	marker.setMap(map);	
}

function initialize(){
	 var styles = [
	{
		stylers: [
		{ hue: "#333" },
		{ saturation: -10 }
	  ]
	},{
		featureType: "road.arterial",
		elementType: "geometry",
		stylers: [
			{ hue: "#00ffee" },
			{ saturation: 50 }
		]
		},{
		featureType: "all",
		stylers: [
			{ hue: "#00ffee" },
			{ saturation: -50 }
		]
		},
	{
		featureType: "road",
		elementType: "geometry",
		stylers: [
			{ lightness: 100 },
			{ visibility: "simplified" }
		]
	},{
		
		elementType: "labels",
		stylers: [
			{ visibility: "off" }
		]
	}];
  // var terrain = new google.maps.StyledMapType(styles, 
		// {name: "Terrain"}); 
	var styledMap = new google.maps.StyledMapType(styles, 
		{name: "Styled Map"});
	
	var mapProp = {
	  center:myCenter,
	  zoom:10,
	  mapTypeControlOptions: {
			mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style']
		}
	  
	};

	map=new google.maps.Map(document.getElementById("googleMap"),mapProp);
	map.mapTypes.set('map_style', styledMap);
	map.setMapTypeId('map_style');
	
	google.maps.event.addListener(map, 'click', function(event){addMarker(event.latLng);});
}

google.maps.event.addDomListener(window, 'load', initialize);
</script>
</head>

<body>
<div id="googleMap" style="width:500px;height:380px;"></div>
</body>
</html>
