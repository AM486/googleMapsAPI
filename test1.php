<!DOCTYPE html>
<html>
<head>
<script
src="http://maps.googleapis.com/maps/api/js?key=AIzaSyAs6cUX2rQLHB6NJlCM40WVrtoafsftUFU&sensor=false">
</script>
<style>
body{
	font-family: courier;
	
}

#toolbar {	
	width:800px;
	height: 30px;
	border: 1px solid gray;
	border-radius: 10px;
	background-color: lavender ;
	padding-bottom:5px;	
	margin-bottom:5px;	
}

td  {						
	border-right: 1px solid lightgray ;
	border-radius: 10px;
	padding-left: 5px;
	width:50px
	display:inline;
} 

td:hover{
	background-color: silver  ;
}
</style>

<script>

myCenter=new google.maps.LatLng(40.668156, 22.930170);
initZoom=5;

var myHood = new google.maps.Circle({
	  center:myCenter,
	  radius:0,
	  strokeColor:"#009b8e",
	  strokeOpacity:0.8,
	  strokeWeight:2,
	  fillColor:"#009b8e",
	  fillOpacity:0.4
 });
 var initMarker=new google.maps.Marker({
	  position:myCenter,
	  animation:google.maps.Animation.BOUNCE
});   

var mapProp = {	
	center:myCenter,
	zoom:initZoom,
	mapTypeId:google.maps.MapTypeId.TERRAIN 
};
var map='';

//initialize
function initialize(){
  	map=new google.maps.Map(document.getElementById("googleMap"),mapProp);
	myHood.setMap(map);	 
	initMarker.setMap(map);

	zooming(map,initMarker) ;

};

function zooming(map,marker){
	google.maps.event.addListener(marker,'click',function() {
		var zoom=map.getZoom();
		//check if deleting
		if (!document.getElementsByName('removeMarker')[0].checked){	
			zoom+=2;	
			map.setZoom(zoom);
			map.setCenter(marker.getPosition());
		}
	});
	
}

function placeMarker(pos){
	var marker =new  google.maps.Marker({
		position: pos,
		map: map,
		animation:google.maps.Animation.BOUNCE
	});
   
	zooming(map,marker)
	//remove Listener
	google.maps.event.addListener(marker, "click", function(){
	
		if (document.getElementsByName('removeMarker')[0].checked){
			//toggle add/remove
			document.getElementsByName('addMarker')[0].checked=false;
			google.maps.event.clearListeners( map,'click');

			google.maps.event.addListener(initMarker, "dblclick", function () {this.setMap(null);});
			
			this.setMap(null);
		}else{
			google.maps.event.clearListeners( initMarker,'click');
			zooming(map,initMarker) ;
		}

	} );

	
 }

function addMarker(){	
	if (document.getElementsByName('addMarker')[0].checked){
		document.getElementsByName('removeMarker')[0].checked=false;
		var listen=google.maps.event.addListener(map, 'click', function(event){placeMarker(event.latLng);});
	}else{
		google.maps.event.clearListeners( map,'click');
	}
}

//remove initMarker
function removeMarker(){	
	if (document.getElementsByName('removeMarker')[0].checked){
		//toogle add/remove
		document.getElementsByName('addMarker')[0].checked=false;
		google.maps.event.clearListeners( map,'click');
		
		google.maps.event.clearListeners( initMarker,'click');
		google.maps.event.addListener(initMarker, "click", function () {this.setMap(null);});
		
	}else{
		google.maps.event.clearListeners( initMarker,'click');
		zooming(map,initMarker) ;
	}
} 


 function getRadius(val){
	myHood.setRadius(parseInt(val));	 
};

google.maps.event.addDomListener(window, 'load', initialize);

</script>
</head>

<body>
<div id='toolbar'>
<table>
<tbody>
	<tr>
		<div class='table'><td class='radius'>set radius<input type="range" name="radius"  value=0 min=0 max=1000 step=100 onchange='getRadius(this.value)'></td></div>
		<div class='table'><td class="onswitch">add marker<input type="checkbox" name="addMarker"  onclick='addMarker()'></td></div>
		<div class='table'><td class="offswitch">remove marker<input type="checkbox" name="removeMarker" onclick='removeMarker()'></td></div>
	</tr>
	
	</tbody>
</table>
</div>
<div id="googleMap" style="width:800px;height:500px;"></div>

</body>
</html>
