<?php

//no direct accees
defined ('_JEXEC') or die('resticted aceess');

// [button type="default" link="#" target="#" size="sm" class="color"]Button[/button]

if(!function_exists('button_sc')){
	function button_sc($atts, $content='') {
		extract(shortcode_atts(array(
					"link" => '',
					"class"=>''
				), $atts));
		$href ='';
		if($link){
			$href = 'href="'.$link.'"';
		}
		$classes = "btn";

		if($class){
			$classes .= ' '.$class;
		}
		$classes = 'class="'.$classes.'"';

		return '<a ' . $href . ' ' . $classes . ' >' .  do_shortcode($content)  . '</a>';
	}
	add_shortcode('button', 'button_sc');
}