<!-- AIzaSyBZtMW9AXbx8u8fUjfP1hdl-ri6DxJf2Eo -->

<!DOCTYPE html>
<html>
<head>
	<title>Google Maps API</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link rel="stylesheet" href="style1.css">
</head>
<body>
	<div class="header">
			<ul class="header-nav">
				<li><a href="#">HOME</a></li>
				<li><a href="#">LOGIN</a></li>
				<li><a href="#">LOGOUT</a></li>
			</ul>
	</div>
	<div id="bookmarks">
		<ul class="list-group">
			<li class="list-group-item">Bookmark 1</li>
			<li class="list-group-item">Bookmark 2</li>
			<li class="list-group-item">Bookmark 3</li>
			<li class="list-group-item">Bookmark 4</li>
			<li class="list-group-item">Bookmark 5</li>
		</ul>
	</div>
	<div class="container">
		<h1>Google Map Information</h1>
		<div class="row">
			<div class="col">
				<h2 id="text-center">Enter Location</h2>
				<form id="locationForm">
					<input type="text" id="locationInput" class="form-control form-control-lg" name="locationInput">
					<br>
					<button class="btn btn-primary btn-block" type="submit">Submit</button>
				</form>
			</div>
		</div>
		<div class="row">
			<div class="col">
				<div id="map"></div>
			</div>
			<div class="col">
				<div id="formatted_address" class="card-body"></div>
				<div id="address_components" class="card-body"></div>
				<div id="geometry" class="card-body"></div>
			</div>
		</div>

		
		
	</div>
	
	<script src="https://unpkg.com/axios/dist/axios.min.js"></script>

	<script type="text/javascript">
		var lat =0, lng=0, zoom=1;
		var locationForm = document.getElementById('locationForm');
		// listen for Submit

		locationForm.addEventListener('submit', geocode);
		
		function geocode(e){
			//Prevent Actual Submit
			e.preventDefault();
			let location = document.getElementById('locationInput').value;

			axios.get('https://maps.googleapis.com/maps/api/geocode/json?',{
				params: {
					address: location,
					key: 'AIzaSyBZtMW9AXbx8u8fUjfP1hdl-ri6DxJf2Eo'
				}
				}).then(function (response) {

				//formatted address
				var formatted_address = response.data.results[0].formatted_address;
				var formattedAddressOutput = `
				<ul class="list-group">
					<li class="list-group-item">${formatted_address	}</li>

				</ul>

				`;
				// Address Components
				var addressComponents = response.data.results[0].address_components;
				var addressComponentsOutput = '<ul class="list-group">';

				for(var i=0; i<addressComponents.length; i++){
					addressComponentsOutput+=
					`<li class="list-group-item"><strong>${addressComponents[i].types[0]}</strong>: ${addressComponents[i].long_name}</li>
					`
				}
				addressComponentsOutput+='</ul>';

				lat = response.data.results[0].geometry.location.lat;
				lng = response.data.results[0].geometry.location.lng;
				zoom = 14;

				var geometryOutput = `<ul class='list-group'>
				<li class="list-group-item"><strong>Latitude: </strong>${lat}</li>
				<li class="list-group-item"><strong>Longitude: </strong>${lng}</li>
				`
				
				//output to app

				document.getElementById("formatted_address").innerHTML = formattedAddressOutput	;

				document.getElementById("address_components").innerHTML =
					addressComponentsOutput;
				document.getElementById("geometry").innerHTML =
					geometryOutput;
				initMap();
				}).catch(function (error) {
					console.log(error);
				});
		}
		function initMap(){
			// map options 
			var options = {
				zoom: zoom,
				center: {lat: lat, lng:lng}
			}
			// new map
			var map = new google.maps.Map(document.getElementById('map'), options);
			
			//listen for api click on the map
			google.maps.event.addListener(map, 'click', function(event){
				addMarker({coords: event.latLng});
			});

			google.maps.event.addListener(map, 'mouseover', function(event){
				console.log(event);
				addInfoWindow({coords: event.latLng, content: event.content});
			});

			function addMarker(props){
				var marker = new google.maps.Marker({
					position: props.coords,
					map: map,
					//icon: props.iconImage
				});
				//check if there is a icon
				if(props.iconImage)
					marker.setIcon(props.iconImage);

				//check if there is a content tag
				if(props.content){
					var infoWindow = new google.maps.InfoWindow({
						content: props.content
					});

					marker.addListener('click', function(){
					infoWindow.open(map, marker);
					});
				}
			}

			function addInfoWindow(props){
				//check if there is a content tag

				if(props.content){
					console.log("Hello");
					var infoWindow = new google.maps.InfoWindow({
						content: props.content,
						position: props.coords
					});
					infoWindow.open(map);
				}
			}

			function getBookmarks(){

			}


		}
	</script>
	<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBZtMW9AXbx8u8fUjfP1hdl-ri6DxJf2Eo&callback=initMap">
    </script>
    

</body>
</html>