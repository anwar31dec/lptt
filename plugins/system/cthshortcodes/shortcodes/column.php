<?php

//no direct accees
defined ('_JEXEC') or die('resticted aceess');

//[Row]
if(!function_exists('row_sc')) {
	$columnArray = array();

	function row_sc( $atts, $content="" ){
		global $columnArray;
		$id='';
		
		$params = shortcode_atts(array(
			  'id' => '',
			  'class' => ''
		 ), $atts);
		
		 if ($params['id']) 
			$id = 'id="' . $params['id'] . '"'; 
		
		do_shortcode( $content );
		
		//Row
		$html = '<div class="row ' . $params['class'] . '" ' . $id . '>';
		//Columns
		if(count($columnArray)){
			foreach ($columnArray as $key=>$value) $html .='<div class="' . $value['class'] . '">' . do_shortcode($value['content']) . '</div>';
		}else{
			$html .= do_shortcode($content);
		}
		
		$html .='</div>';
	
		$columnArray = array();	
		return $html;
	}
	
	add_shortcode( 'row', 'row_sc' );
		
	//Row Items
	function span_sc( $atts, $content="" ){
		global $columnArray;
		$columnArray[] = array('class'=>$atts['class'], 'content'=>$content);
	}

	add_shortcode( 'col', 'span_sc' );			
}