 <?php
 // No direct access
 defined('_JEXEC') or die('Restricted access');
 
 jimport('joomla.form.formfield');
 
 /**
  * Book form field class
  */
 class JFormFieldModal_Gmap extends JFormField
 {
        /**
         * field type
         * @var string
         */
        protected $type = 'Modal_Gmap';


        /**
   * Method to get the field input markup
   */
  protected function getInput()
  {
      $doc = JFactory::getDocument();
      $doc->addStyleSheet(JURI::root(true)."/modules/mod_cthgmap/assets/css/custom.css");
      $doc->addScript("http://maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places");
      $doc->addScript(JURI::root(true)."/modules/mod_cthgmap/assets/js/jquery.geocomplete.min.js");

  	//JFactory::getDocument()->addScript(JURI::base(true).'/components/com_hccontact/assets/js/SqueezeBox.js');
          // Load modal behavior
          //JHtml::_('behavior.modal', 'a.modal');
  			$scripts = array();
  //       $scripts[] = '(function($){';
  //         $scripts[] = '$(document).ready(function(){';
  //           $scripts[] = '$("#map-input").geocomplete({';
  //             $scripts[] = 'map: "#map-canvas",';
  //             $scripts[] = 'mapOptions: {';
  //   $scripts[] = 'zoom: 10';
  // $scripts[] = '},';
  // $scripts[] = 'markerOptions: {';
  //   $scripts[] = 'draggable: true';
  // $scripts[] = '},';
  // $scripts[] = 'details: "form",';
  // //$scripts[] = 'details: ".details",';
  // //$scripts[] = 'detailsAttribute: "data-geo"';
  //           $scripts[] = '});';
  //       $scripts[] = '})';
  //     $scripts[] = '})(jQuery);';
$scripts[] = '(function($){';
$scripts[] = '$(document).ready(function(){';
  $scripts[] = 'var lat = $(\'input[id="jform_params_gmapLat"]\').val(); var log = $(\'input[id="jform_params_gmapLog"]\').val();';
  $scripts[] = 'var center = new google.maps.LatLng(lat,log);';
  $scripts[] = 'var loca = log+\',\'+lat;';
    $scripts[] = '$(\'#map-input\').geocomplete({';
    $scripts[] = 'map: \'#map-canvas\',';
    $scripts[] = 'types: [\'establishment\'],';
    $scripts[] = 'country: \'de\',';
    $scripts[] = 'details: \'form \',';
    $scripts[] = 'markerOptions: {';
      $scripts[] = 'draggable: true';
    $scripts[] = '},';
    $scripts[] = 'location:loca,';
    $scripts[] = 'mapOptions: {';
      $scripts[] = 'scrollwheel :true,';
      $scripts[] = 'center:center,';
      $scripts[] = 'zoom:15';
    $scripts[] = '}';
    $scripts[] = '});';
    $scripts[] = '$(\'#map-input\').bind(\'geocode:dragged\', function(event, latLng){';
      $scripts[] = '$(\'input[id="jform_params_gmapLat"]\').val(latLng.lat());';
      $scripts[] = '$(\'input[id="jform_params_gmapLog"]\').val(latLng.lng());';
      $scripts[] = '$(\'input[name="lat"]\').val(latLng.lat());';
      $scripts[] = '$(\'input[name="lng"]\').val(latLng.lng());';
    $scripts[] = '});';
 
 $scripts[] = '$(\'#gmapFind\').click(function(){';
  $scripts[] = 'var searchstr = $(\'input[id="map-input"]\').val();';
  $scripts[] = '$(\'#map-input\').geocomplete(\'find\', searchstr);';
 $scripts[] = '})';
$scripts[] = '})';
$scripts[] = '})(jQuery);';

        //$doc->addScriptDeclaration(implode("\n", $scripts));
 
          // Build the script
          

          $html[] = '<!-- Modal -->';
$html[] = '<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">';
  $html[] = '<div class="modal-header">';
    $html[] = '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>';
    $html[] = '<h3 id="myModalLabel">Google Map location option</h3>';
  $html[] = '</div>';
  $html[] = '<div class="modal-body">';
    $html[] = '<div id="map-canvas"></div>';
    $html[] = '<div class="input-append">';
 $html[] = '<input id="map-input" class="inputbox" name="map-input" >';
  $html[] = '<button class="btn" id="gmapFind" type="button">Search</button>';
  //$html[] = '<button class="btn" id="gmapReset" type="button">Options</button>';
  // $html[] = '<div class="details">';
  //   $html[] = 'Latitude:     <span data-geo="lat" />';
  //   $html[] = 'Longitude:    <span data-geo="lng" />';
  //   $html[] = 'Address:      <span data-geo="formatted_address" />';
  //   $html[] = 'Country Code: <span data-geo="country_short" />';
  // $html[] = '</div>';
  
$html[] = '</div>';
   
  $html[] = '</div>';
  $html[] = '<div class="modal-footer">';
    $html[] = '<form class="form-inline pull-left">';
    $html[] = 'Latitude: <input  class="input-medium" name="lat" type="text" >';
    $html[] = 'Longitude: <input  class="input-medium" name="lng" type="text">';
    //$html[] = 'Address:    <input class="inputbox" name="formatted_address" type="text" >';
    $html[] = '</form>';
    $html[] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Save</button>';
    //$html[] = '<button class="btn btn-primary">Save changes</button>';
  $html[] = '</div>';
$html[] = '</div>';
$html[] = '<script>';

$html[] = '(function($){';
$html[] = '$(document).ready(function(){';
  $html[] = 'var lat = $(\'input[id="jform_params_gmapLat"]\').val(); var log = $(\'input[id="jform_params_gmapLog"]\').val();';
  $html[] = 'var center = new google.maps.LatLng(lat,log);';
  $html[] = 'var loca = log+\',\'+lat;';
    $html[] = '$(\'#map-input\').geocomplete({';
    $html[] = 'map: \'#map-canvas\',';
    $html[] = 'types: [\'establishment\'],';
    $html[] = 'country: \'de\',';
    $html[] = 'details: \'form \',';
    $html[] = 'markerOptions: {';
      $html[] = 'draggable: true';
    $html[] = '},';
    $html[] = 'location:loca,';
    $html[] = 'mapOptions: {';
      $html[] = 'scrollwheel :true,';
      $html[] = 'center:center,';
      $html[] = 'zoom:15';
    $html[] = '}';
    $html[] = '});';
    $html[] = '$(\'#map-input\').bind(\'geocode:dragged\', function(event, latLng){';
      $html[] = '$(\'input[id="jform_params_gmapLat"]\').val(latLng.lat());';
      $html[] = '$(\'input[id="jform_params_gmapLog"]\').val(latLng.lng());';
      $html[] = '$(\'input[name="lat"]\').val(latLng.lat());';
      $html[] = '$(\'input[name="lng"]\').val(latLng.lng());';
    $html[] = '});';
 
 $html[] = '$(\'#gmapFind\').click(function(){';
  $html[] = 'var searchstr = $(\'input[id="map-input"]\').val();';
  $html[] = '$(\'#map-input\').geocomplete(\'find\', searchstr);';
 $html[] = '})';
$html[] = '})';
$html[] = '})(jQuery);';

$html[] = '</script>';

$html[] = '<a href="#myModal" role="button" class="btn modal btn-primary" data-toggle="modal">Google Map Location Option</a>';
 
 
       
 
          return implode("\n", $html);
  }
 
 }