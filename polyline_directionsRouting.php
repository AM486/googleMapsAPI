<!DOCTYPE html>
<html>
<head>
<script
src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDY0kkJiTPVd2U7aTOAwhc9ySH6oHxOIYM&sensor=false">
</script>
<style>

	body {font-family: tahoma;  }
	#googleMap{
		padding: 10px 5px 5px 5px;;
	}
	#distance{
		background-color: #dfe5ed;
		height:30px;
		width:200px;		
		color:#4c6579;
		margin-bottom: 10px;
		padding: 10px 5px 5px 5px;
		border-radius:10px;
		text-align:center;
		display:inline-block;
		
		}
	#distance span{		
		width:70px;
		margin: 5px 5px 5px 5px;
		font-weight:bolder;
		border:none;
		
	}
	div#delete{
		background-color: #009b8e  ;
		height:30px;
		width:100px;		
		color:#4c6579;
		margin-bottom: 10px;
		padding: 8px 3px 3px 3px;
		border-radius:10px;
		text-align:center;
		font-weight:bolder;
		display:inline-block;	
		border: 2px solid #009b8e;
	}
	div#delete:hover{
		background-color: #02766c      ;
		border: 2px solid #d7f9f6  ;
		color: #d7f9f6    ;
		box-shadow: 2px 2px 5px #888888;
		cursor:pointer;
	}
	div.toolbar{
		margin:10px 5px 10px 5px;
	}
	.testing{
		width:500px;
		height:300px;
		border:1px solid gray;
		overflow:auto;
		padding:5px 5px 5px 5px;
		margin:5px 5px 5px 5px;
	}

</style>
<script>

var myCenter=new google.maps.LatLng(40.668156, 22.930170);
var path = new google.maps.MVCArray();
var distance = new google.maps.MVCArray();
var waypts =[];
var directionsService = new google.maps.DirectionsService();
var directionsDisplay;
var map;
var poly;
var request={travelMode:  google.maps.DirectionsTravelMode.WALKING,
					optimizeWaypoints:false};

function addPoint(location){
	distance.push(location);
	if(path.length==0){		
		path.push(location);	
	    poly.setPath(path);
		request.origin=location;
		request.destination=location;
		directionsDisplay.setMap(map);
	}else{
		request.origin=path.getAt(path.length-1 );					
		request.destination=location;		
		printTesting(request.origin,request.destination);
		
		
		directionsService.route(request, function(result, status) {		
			if (status == google.maps.DirectionsStatus.OK) {				
				for (var i = 0, len = result.routes[0].overview_path.length;i < len; i++) {
						path.push(result.routes[0].overview_path[i]);						
					}
			}									
			newDirectionsService(path.getAt(0),location)
			path.push(location);
		});
	}
}

function printTesting(var1,var2){
	text=document.getElementsByClassName('testing')[0].innerHTML+'<strong>origin: </strong>'+var1+'</br>'+'<strong>destination: </strong>'+var2+'</br></br>';
	document.getElementsByClassName('testing')[0].innerHTML=text;
}
function newDirectionsService(origin,destination){
	request.origin=origin;					
	request.destination=destination;	
	directionsService.route(request, function(result2, status) {		
		if (status == google.maps.DirectionsStatus.OK) {				
					directionsDisplay.setDirections(result2);
		}});
}

function deleteLast(){
	//alert('deleting');
	if(distance.length<2){
		directionsDisplay.setMap(null);		
		while(path.length!=0){path.pop();}
		while(distance.length!=0){distance.pop();}
	}else{
		distance.pop();
		while(path.getAt(path.length-1)!= distance.getAt(distance.length-1)	){
			path.pop();
		}
		newDirectionsService(path.getAt(0),distance.getAt(distance.length-1));
	}
	// getTotalDistance();
}

function getTotalDistance(){	
	//var dist=(google.maps.geometry.spherical.computeLength(distance)/1000).toPrecision(4)
	//document.getElementById('yo').innerHTML=dist ;	
}

function initialize(){
	directionsDisplay = new google.maps.DirectionsRenderer({
	draggable:true,
	preserveViewport:true,
	suppressPolylines:true,
	});
	var mapProp = {
		center:myCenter,
		zoom:15,
		mapTypeIds: [google.maps.MapTypeId.ROADMAP, google.maps.MapTypeId.HYBRID,
          google.maps.MapTypeId.TERRAIN],
		draggableCursor: "crosshair"
	};
		
	map=new google.maps.Map(document.getElementById("googleMap"),mapProp);
	poly = new google.maps.Polyline({ 
		map: map,
		clickable:true,
		editable:true,
		strokeColor:'#009b8e',
		strokeWeight:'5',
		strokeOpacity:0.5
	});
	google.maps.event.addListener(map, 'click', function(event){	addPoint(event.latLng);getTotalDistance();  });
	google.maps.event.addListener(poly, 'mousemove', function(event){poly.setOptions({editable:true});  });
	google.maps.event.addListener(poly, 'mouseout', function(event){poly.setOptions({editable:false});  });

}

google.maps.event.addDomListener(window, 'load', initialize);
</script>
</head>

<body>
<div  class='toolbar'>
<div id='distance' class='toolbar' >Total Distance: <span id='yo'>0.000</span>km</div>
<div id='delete'  class='toolbar'  onclick='deleteLast()'>Delete last </div>
</div>
<div id="googleMap" style="width:500px;height:380px;"></div>
<div class='testing'></div>
</body>
</html>
