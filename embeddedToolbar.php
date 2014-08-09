<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="Fonts/font-awesome-4.1.0/css/font-awesome.min.css">
<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyAs6cUX2rQLHB6NJlCM40WVrtoafsftUFU&sensor=false">
</script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/jquery-ui.min.js"></script>
<style>
body{
	font-family:  Tahoma;
}

div#toolbar {	
	width:90px;
	height: 130px;
	border: 1px solid gray;
	box-shadow:inset 2px 2px  5px silver ;
	border-radius: 10px;
	background: rgba(186,186,186,0.8)   ;	
	margin-bottom:5px;
	padding: 0px 6px 3px 7px;
	z-index:1;
	text-align:right;
}

input[type='range']{
	-webkit-appearance: none;
	border-radius: 3px;
	box-shadow: inset 0 0 5px #333;
	background-color: gray;
	height: 4px;
	vertical-align: middle;
	width:40px;
}
input[type='checkbox']{
	##-webkit-appearance: none;
	##border-radius: 20px;
	##box-shadow: inset 0 0 5px #333;
	##background-color: gray;
	height: 15px;
	width: 15px;
	vertical-align: middle;	
	
}
label{
	padding-left:5px;
}

label[for="addMarker"]{
	padding-left:30px;
}

.toolbar  {
	##heigth:10px;
	##width:10px;	
	##border: 1px solid lightgray ;
	list-style-type:none;
	display:inline;
	font-family: 'Oswald',sans-serif;
	font-size: 12px;
    ##line-height:20px;
	color: #444 ;	
}

li  {						
	##border-bottom: 1px solid lightgray ;
	border-radius: 10px;
	height:30px;
	text-align:left;
	##padding-left: 5px;	
	##vertical-align: middle;	
} 

.fa {
	display: inline;
	font-family: FontAwesome;
	font-style: normal;
	font-weight: normal;
	font-size: 15px;	
	float:right;
	
}
li:hover{
	color: darkcyan ;
	
}

</style>

<script type="text/javascript" >

myCenter=new google.maps.LatLng(40.668156, 22.930170);
initZoom=5;

function Toolbar(controlDiv, map){

	controlDiv.style.padding = '5px';
	var toolbar = document.createElement('div');
	toolbar.id='toolbar';
	toolbar.style.textAlign = 'center';	

	controlDiv.appendChild(toolbar);
	
	var ul = document.createElement('ul');
	ul.className="toolbar";
	
	toolbar.appendChild(ul);
	var li=[];
	var hr=[];
	var input=[];
	var label=[];
	var ii=[];
	for (i=0;i<3;i++){
		li.push( document.createElement('li') );
		hr.push( document.createElement('hr') );
		input.push( document.createElement('input') );
		label.push(document.createElement('label') );
		ii.push(document.createElement('i') );
		
		ul.appendChild(li[i]);
		li[i].appendChild(hr[i]);
		li[i].appendChild(input[i]);
		li[i].appendChild(label[i]);
		li[i].appendChild(ii[i]);
	}
		
	input[0].setAttribute("type", "range");
	input[0].setAttribute("id", "radius");
	input[0].setAttribute("name", "radius");
	input[0].setAttribute("value", "0");
	input[0].setAttribute("min", "0");
	input[0].setAttribute("max", "1000");
	input[0].setAttribute("step", "100");
	input[0].setAttribute("onclick",'getRadius(this.value)');
	label[0].setAttribute("for",'radius');
	label[0].innerHTML='SET';
	ii[0].className="fa fa-dot-circle-o";
	
	input[1].setAttribute("type", "checkbox");
	input[1].setAttribute("id", "addMarker");
	input[1].setAttribute("name", "addMarker");
	input[1].setAttribute("onclick",'addMarker()');
	label[1].setAttribute("for",'addMarker');
	label[1].innerHTML='ADD';
	ii[1].className="fa fa-map-marker";
	
	input[2].setAttribute("type", "checkbox");
	input[2].setAttribute("id", "removeMarker");
	input[2].setAttribute("name", "removeMarker");
	input[2].setAttribute("onclick",'removeMarker()');
	label[2].setAttribute("for",'removeMarker');
	label[2].innerHTML='REMOVE';
	ii[2].className="fa fa-map-marker";
	

}

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
	panControl:true,
    zoomControl:true,
    mapTypeControl:true,
    scaleControl:true,
    streetViewControl:true,
    overviewMapControl:true,
    rotateControl:true,  
	mapTypeId:google.maps.MapTypeId.TERRAIN 
};
var map='';
//initialize
function initialize(){
	var mapDiv = document.getElementById('googleMap');
  	map=new google.maps.Map(mapDiv,mapProp);
	myHood.setMap(map);	 
	initMarker.setMap(map);
	zooming(map,initMarker) ;
	addInfoWindow(map,initMarker)
	
	var toolbarDiv = document.createElement('div');
	var toolbar=new Toolbar(toolbarDiv,map);
	toolbarDiv.index = 1;	
	map.controls[google.maps.ControlPosition.RIGHT_CENTER].push(toolbarDiv);
	
};
function zooming(map,marker){
	google.maps.event.addListener(marker,'click',function() {
		var zoom=map.getZoom();	
	// if deleting no zoom on  click
		if (!document.getElementsByName('removeMarker')[0].checked){	
			zoom+=2;	
			map.setZoom(zoom);
			map.setCenter(marker.getPosition());
		}
	});
}

function addInfoWindow(map,marker){
	var infowindow = new google.maps.InfoWindow({
		content: 'Latitude: ' + marker.getPosition().lat() + '<br>Longitude: ' + marker.getPosition().lng()
	});
	//hover InfoWindow  Listener
	google.maps.event.addListener(marker, "mouseover", function(){
		  infowindow.open(map,marker);
	} );
	google.maps.event.addListener(marker, "mouseout", function(){
		  infowindow.close(map,marker);
	} );
}
function placeMarker(pos){
	var marker =new  google.maps.Marker({
		position: pos,
		map: map,
		animation:google.maps.Animation.BOUNCE
	});
   
	zooming(map,marker)
	addInfoWindow(map,marker)
	
	//removeMarker Listener
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
<div >
<!--initial ttoolbar in HTML
<div id='toolbar'>
<ul class='toolbar'>
	<li>	
		<hr>
		<div class='table' ><input type="range"   id="radius"  name="radius"  value=0 min=0 max=1000 step=100 onchange='getRadius(this.value)'> 
		<label for="radius">SET <i class="fa fa-dot-circle-o"></i></label></div>
	</li>
	<li>
		<hr>
		<div class='table'><input type="checkbox" id="addMarker" name="addMarker"  onclick='addMarker()'>
		<label for="addMarker"> ADD  <i class="fa fa-map-marker"></i></label></div>
	</li>
	<li>
		<hr>
		<div class='table'><input type="checkbox" id="removeMarker" name="removeMarker" onclick='removeMarker()'>
		<label for="removeMarker">DELETE  <i class="fa fa-map-marker"></i></label> </div>
	</li>	
</ul>
</div> -->
<div id="googleMap" style="width:800px;height:500px;"></div>
</div>
</body>
</html>
