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
if(empty($id)){
	$id = uniqid('map_canvas');
}
if(!empty($class)){
	$class = 'class="'.$class.'"';
}
?>
<!--google map-->
<section id="<?php echo $id;?>" <?php echo $class.' '.$gmapstyle;?>></section>
<!--end google map-->

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>

<script>
/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
    /* google map  */
    /*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/


    function initialize() {
        var map_canvas = document.getElementById('<?php echo $id;?>');

        var map_options = {
            center: new google.maps.LatLng(<?php echo $gmaplat;?>, <?php echo $gmaplog;?>),
            zoom: <?php echo $gmapzoom;?>,
            mapTypeId: google.maps.MapTypeId.<?php echo $gmaptypeid;?>,
            panControl:<?php echo ($gmappancontrol == '1')? 'true' : 'false';?>, 
			zoomControl: <?php echo ($gmapzoomcontrol == '1')? 'true' : 'false';?>,
			mapTypeControl: <?php echo ($gmaptypecontrol == '1')? 'true' : 'false';?>,
			streetViewControl: <?php echo ($gmapstreetviewcontrol == '1')? 'true' : 'false';?>,
            scrollwheel: <?php echo ($gmapscrollwheel == '1')? 'true' : 'false';?>,
        };

        var map = new google.maps.Map(map_canvas, map_options);
        var marker = new google.maps.Marker({
            position: new google.maps.LatLng(<?php echo $gmaplat;?>, <?php echo $gmaplog;?>),
            map: map,
            title: 'Hello World!'
        });
        /*var styles = [
            {
                "stylers": [
                    { "saturation": -56 },
                    { "color": "#838080" },
                    { "lightness": -45 }
                ]
            },{
                "featureType": "landscape",
                "stylers": [
                    { "color": "#938080" }
                ]
            },{
                "featureType": "landscape.man_made",
                "elementType": "geometry",
                "stylers": [
                    { "color": "#868483" },
                    { "saturation": -72 },
                    { "lightness": -35 }
                ]
            },{
                "featureType": "landscape.man_made",
                "elementType": "labels.text",
                "stylers": [
                    { "color": "#808080" },
                    { "saturation": -88 },
                    { "lightness": 100 },
                    { "weight": 0.1 }
                ]
            },{
                "featureType": "poi",
                "elementType": "labels.text",
                "stylers": [
                    { "saturation": -88 },
                    { "lightness": 100 },
                    { "weight": 0.1 }
                ]
            },{
                "featureType": "road.highway",
                "stylers": [
                    { "color": "#c99f6c" },
                    { "saturation": -40 }
                ]
            },{
                "featureType": "road.arterial",
                "stylers": [
                    { "color": "#c99f6c" },
                    { "saturation": -85 },
                    { "lightness": 41 }
                ]
            },{
                "featureType": "road.local",
                "stylers": [
                    { "color": "#c99f6c" },
                    { "saturation": -86 },
                    { "lightness": -49 }
                ]
            },{
                "featureType": "road",
                "elementType": "labels",
                "stylers": [
                    { "lightness": 100 },
                    { "weight": 0.1 }
                ]
            },{
                "featureType": "landscape",
                "elementType": "labels",
                "stylers": [
                    { "color": "#ae8080" },
                    { "lightness": 100 }
                ]
            },{
                "featureType": "poi",
                "elementType": "labels.icon",
                "stylers": [
                    { "color": "#558080" },
                    { "lightness": 100 },
                    { "weight": 0.1 }
                ]
            },{
                "featureType": "poi",
                "elementType": "labels.icon",
                "stylers": [
                    { "color": "#d18080" },
                    { "visibility": "off" }
                ]
            },{
                "featureType": "administrative",
                "elementType": "labels.text.fill",
                "stylers": [
                    { "color": "#958080" },
                    { "lightness": 100 }
                ]
            },{
            }
        ]
        map.setOptions({styles: styles});*/
    }
    google.maps.event.addDomListener(window, 'load', initialize);


</script>