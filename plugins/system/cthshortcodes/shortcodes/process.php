<?php

//no direct accees
defined ('_JEXEC') or die('resticted aceess');

//[progress]
if(!function_exists('process_sc')){
	function process_sc($atts, $content=''){
		 extract(shortcode_atts(array(
	        "class" => '',
			"icon" =>'',
	        "title" => ''
	     ), $atts));

	     if(!empty($class)){
	     	$class ='class="'.$class.'"';
	     }

	     $html = array();

	    $html[] = '<!-- Process item -->';
		$html[] = '<div '.$class.'>';
			$html[] = '<div class="icon-circle">';
			if(!empty($icon)){
				$html[] = '<i class="'.$icon.'"></i>';
			}
				
				$html[] = '<h3 class="h5 text-white">'.$title.'</h3>';
				$html[] =  do_shortcode( $content );
			$html[] = '</div>';
		$html[] = '</div>';

     	return implode("\n", $html);
	}
	add_shortcode('process','process_sc');
}

