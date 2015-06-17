<?php
//no direct accees
defined ('_JEXEC') or die('resticted aceess');

//[Icon]
if(!function_exists('icon_sc')) {

	function icon_sc( $atts, $content="" ) {
	
		extract(shortcode_atts(array(
			   'name' => 'fa-joomla',
			   'class' =>""
		 ), $atts));


		$name = "fa fa-".str_replace("fa-", "", $name);

		if(!empty($class)){
			$name .= ' '.$class;
		}
		 
		 
		 
		return '<i class="' . $name . '"></i>';
	 
	}
		
	add_shortcode( 'icon', 'icon_sc' );
}