;(function($, window, document, undefined) {
	var $win = $(window);
	var $doc = $(document);
	var maps_container = '.google-map';
	var map = []; // Contains all maps that will be created
	var map_bounds = []; // Contains all bounds per map

	$doc.ready(function() {
		googleMap(maps_container);
	});

	/**
	* Generate Map From Address or Coordinates
	* mapOptions - an array of map options, like Controls
	* markerOptions - an array of marker options, like icon
	*/

	function googleMap(container, mapOptions, markerOptions){
		$(container).each(function(){
			var $map_container = $(this);
			var _lat = $map_container.data('lat');
			var _lng = $map_container.data('lng');
			var _coordinates = $map_container.data('coordinates');
			var _address = $map_container.data('location');
			var _zoom = $map_container.data('zoom');
			var _id = $map_container.attr('id');
			if ( typeof $map_container.data('map-options') != 'undefined' ) {
				var _mapOptions = $.parseJSON(urlDecode($map_container.data('map-options')));
				mapOptions = $.extend(mapOptions, _mapOptions);
				console.log(mapOptions);
			};
			if ( typeof $map_container.data('marker-options') != 'undefined' ) {
				var _markerOptions = $.parseJSON(urlDecode($map_container.data('marker-options')));
				markerOptions = $.extend(markerOptions, _markerOptions);
			};
			if ( typeof $map_container.data('pins') != 'undefined' ) {
				var _pins = $.parseJSON(urlDecode($map_container.data('pins')));
			};
			// Set default Zoom
			if ( typeof _zoom == 'undefined' ) {
				_zoom = 16;
			}
			if ( typeof _address != 'undefined' ) {
				var geocoder = new google.maps.Geocoder();
				geocoder.geocode({ address: _address }, function(result, status) {
					if (status == 'OK') {
						var loc = result[0].geometry.location;
						drawMap( _id, loc, _zoom, mapOptions, markerOptions );
					}
				});
			} else if ( typeof _coordinates != 'undefined' ) {
				var _coordinates_array = _coordinates.split(',');
				var loc = new google.maps.LatLng(_coordinates_array[0], _coordinates_array[1]);
				drawMap( _id, loc, _zoom, mapOptions, markerOptions );
			} else if ( typeof _lat != 'undefined' && typeof _lng != 'undefined' ) {
				var loc = new google.maps.LatLng(_lat, _lng);
				drawMap( _id, loc, _zoom, mapOptions, markerOptions );
			} else if ( typeof _pins != 'undefined' && _pins.length >= 1 ) {
				var loc = new google.maps.LatLng(_pins[0]['lat'], _pins[0]['lng']);
				drawMap( _id, loc, _zoom, mapOptions, $.extend({ map: null }, markerOptions) );
				drawMarkers( _id, _pins, markerOptions );
			};
		});
	};

	//Draw the map
	function drawMap(_id, loc, _zoom, mapOptions, markerOptions) {
		// Custom Pin
		/*
		// This value is passed from the wordpress, for correct image load.
		var dir = php_passed_variables['stylesheet_directory'];
		// Retina Image Support
		var image = {
			url: dir + '/images/google-pin.png',
			size: new google.maps.Size(50, 100),
			origin: new google.maps.Point(0, 0),
			anchor: new google.maps.Point(25, 50),
			scaledSize: new google.maps.Size(25, 50)
		};
		*/
		var mapOptions = $.extend({
			zoom: _zoom,
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			center: loc,
			panControl: false,
			zoomControl: false,
			mapTypeControl: false,
			scaleControl: false,
			streetViewControl: false,
			overviewMapControl: false
		}, mapOptions);
		if ( loc !== '' ) {
			$.extend(true, mapOptions, {
				center: loc,
			});
		}
		map[_id] = new google.maps.Map(document.getElementById(_id), mapOptions);
		if ( loc !== '' ) {
			markerOptions = $.extend({
				map: map[_id],
				position: loc,
				// icon: image
			}, markerOptions);
			var marker = new google.maps.Marker(markerOptions);
			// Clicking on marker will redirect if url attribute is setup
			google.maps.event.addListener( marker, 'click', (function(marker) {
				return function() {
					if ( typeof marker.url != 'undefined' ) {
						window.open( marker.url );
					};
				}
			})(marker));
		};
	};

	// Draw markers on the map
	function drawMarkers(_id, _pins, markerOptions) {
		// Custom Pin
		/*
		// This value is passed from the wordpress, for correct image load.
		var dir = php_passed_variables['stylesheet_directory'];
		// Retina Image Support
		var image = {
			url: dir + '/images/google-pin.png',
			size: new google.maps.Size(50, 100),
			origin: new google.maps.Point(0, 0),
			anchor: new google.maps.Point(25, 50),
			scaledSize: new google.maps.Size(25, 50)
		};
		*/
		var bounds = new google.maps.LatLngBounds();
		var infoWindowses = [];
		var markers = [];
		for (var i = 0; i < _pins.length; i++) {
			var pin = _pins[i];
			var loc = new google.maps.LatLng(pin.lat, pin.lng);
			// Initialize Pin
			var currentMarkerOptions = $.extend({
				map: map[_id],
				title: pin.title,
				position: loc,
				// icon: image
			}, markerOptions);
			markers[i] = new google.maps.Marker(currentMarkerOptions);
			// Add the current pin to the collection for centering the map
			bounds.extend(markers[i].position);
			// Initialize infoWindow
			infoWindowses[i] = new google.maps.InfoWindow({
				content: pin.title
			});
			// Allow each marker to have an info window
			google.maps.event.addListener(markers[i], 'click', (function(marker, i) {
				return function() {
					$.each(infoWindowses, function(i, val) {
						this.close(map[_id], markers[i]);
					});
					infoWindowses[i].setContent('<h4>' + _pins[i].title + '</h4>');
					infoWindowses[i].open(map[_id], marker);
				}
			})(markers[i], i));
		};
		map_bounds[_id] = bounds;
		map[_id].fitBounds(bounds);
	};

	// Regenerate the map, to be used inside accordions, or other elements, where there is an issue with reinitialization
	function reDrawMaps(container){
		$(container).each(function(){
			var _id = $(this).attr('id');
			var center = map[_id].getCenter();
			google.maps.event.trigger(map[_id],'resize')
			map[_id].setCenter(center);
			if ( typeof map_bounds[_id] != 'undefined' ) {
				map[_id].fitBounds(map_bounds[_id]);
			};
		});
	};

	// Regenerate the map on window resize
	google.maps.event.addDomListener(window, 'resize', function() {
		reDrawMaps(maps_container);
	});

	urlDecode = function(str) {
		return decodeURIComponent((str + '')
			.replace(/%(?![\da-f]{2})/gi, function() {
				// PHP tolerates poorly formed escape sequences
				return '%25';
			})
			.replace(/\+/g, '%20'));
	}

})(jQuery, window, document);
