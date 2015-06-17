<?php
/**
 * @package Azura Joomla Pagebuilder
 * @author Cththemes - www.cththemes.com
 * @date: 15-07-2014
 *
 * @copyright  Copyright ( C ) 2014 cththemes.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

$doc = JFactory::getDocument();

$doc->addScript(JURI::base(true).'/components/com_azurapagebuilder/assets/js/jquery.min.js');

$doc->addStyleSheet(JURI::base(true)."/components/com_azurapagebuilder/assets/css/gmap.css");
$doc->addScript("http://maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places");

$user  = JFactory::getUser();
$input = JFactory::getApplication()->input;

?>
<div class="row">
	<div class="span12" style="width:96%;">
		<h3 id="myModalLabel">Google Map location option</h3>
		<form class="form-inline pull-left">
		Latitude: <input  class="input-medium" name="lat" type="text" >
		Longitude: <input  class="input-medium" name="lng" type="text">
&nbsp;&nbsp;
			<input id="map-input" class="input-medium" type="text" value="70 FDR Dr, New York, NY 10065, USA" name="formatted_address" >
			<button class="btn" id="gmapFind" type="button">Search</button>
			<button class="btn" id="gmapReset" type="button">Clear</button>
		</form>
		<div class="cleafix" > </div>
		<div id="map-canvas"></div>
		<div class="clearfix"></div>
		
		
		<br>
		<br>
		<div style="margin:0 auto; text-align: center;">
		<button class="btn btn-primary" id="btn-gmap-save">Save</button>
		<button class="btn btn-default" id="btn-gmap-cancel">Cancel</button>
	</div>
	</div>
	

</div>

<script src="/devcashemir/administrator/components/com_azurapagebuilder/assets/js/jquery.geocomplete.min.js" type="text/javascript"></script>
<script>
(function($){
	  var lat = $(window.parent.document).find('#elementAttrs_gmapLat').val();//$('input[id="jform_params_gmapLat"]').val(); 
	  var log = $(window.parent.document).find('#elementAttrs_gmapLog').val();//$('input[id="jform_params_gmapLog"]').val();
	  var center = new google.maps.LatLng(lat,log);
	  var loca = log+','+lat;
    $('#map-input').geocomplete({
	    map: '#map-canvas',
	    types: ['establishment'],
	    //country: 'vi',
	    details: 'form',
	    markerOptions: {
	      draggable: true
	    },
    	location:loca,
	    mapOptions: {
	      scrollwheel :true,
	      center:center,
	      zoom:15
	    }
    });
    $('#map-input').bind('geocode:dragged', function(event, latLng){
      $('input[name="lat"]').val(latLng.lat());
      $('input[name="lng"]').val(latLng.lng());
    });
 
	$('#gmapFind').click(function(){
		var searchstr = $('input[id="map-input"]').val();
		$('#map-input').geocomplete('find', searchstr);
	});

	$('body').on('click','#gmapReset', function(event){
		event.preventDefault();
		$('#map-input').val('');
	});

	$('body').on('click','#btn-gmap-cancel', function(event){
		event.preventDefault();
		window.parent.SqueezeBox.close();
	});

	$('body').on('click', '#btn-gmap-save', function(event) {
		event.preventDefault();

		$(window.parent.document).find('#elementAttrs_gmapLat').val($('input[name="lat"]').val());
		$(window.parent.document).find('#elementAttrs_gmapLog').val($('input[name="lng"]').val());

		window.parent.SqueezeBox.close();
	});
})(jQuery);
</script>