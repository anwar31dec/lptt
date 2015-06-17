var map = L.map('map').setView([8.76939, 1.14618], 7);
L.tileLayer('https://{s}.tiles.mapbox.com/v3/{id}/{z}/{x}/{y}.png', {
	maxZoom : 18,
	attribution : 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' + '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' + 'Imagery © <a href="http://mapbox.com">Mapbox</a>',
	id : 'anwar31dec.k9l0pc3a'
}).addTo(map);

var LeafIcon = L.Icon.extend({
	options: {
		shadowUrl: baseUrl + 'leafletjs/images/marker-shadow.png',
		iconSize:     [40, 63],
		shadowSize:   [63, 63],
		iconAnchor:   [32, 64],
		shadowAnchor: [32, 64],
		popupAnchor:  [0, -60]
	}
	});

// var markerIcon = new LeafIcon({iconUrl: baseUrl + 'leafletjs/images/marker-red-icon.png'});	
// var markerIconSo = new LeafIcon({iconUrl: baseUrl + 'leafletjs/images/marker-red-icon-so.png'});
var markerIconNr = new LeafIcon({iconUrl: baseUrl + 'leafletjs/images/NR.png'});

var LeafIcon2 = L.Icon.extend({
	options: {
		shadowUrl: baseUrl + 'leafletjs/images/marker-shadow.png',
		iconSize:     [59, 100],
		shadowSize:   [100, 100],
		iconAnchor:   [24, 100],
		shadowAnchor: [24, 100],
		popupAnchor:  [5, -90]
	}
	});

var mapLocationPinsBlue = new LeafIcon2({iconUrl: baseUrl + 'leafletjs/images/map-location-pins-blue.png'});
var mapLocationPinsGreen = new LeafIcon2({iconUrl: baseUrl + 'leafletjs/images/map-location-pins-green.png'});
var mapLocationPinsRed = new LeafIcon2({iconUrl: baseUrl + 'leafletjs/images/map-location-pins-red.png'});
var mapLocationPinsYellow = new LeafIcon2({iconUrl: baseUrl + 'leafletjs/images/map-location-pins-yellow.png'});

var iconBaseUrl = baseUrl + 'leafletjs/images/';	

var popup = L.popup();

function onMapClick(e) {
	popup.setLatLng(e.latlng).setContent("You clicked the map at " + e.latlng.toString()).openOn(map);
}

//map.on('click', onMapClick);

