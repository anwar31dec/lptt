<?php

//no direct accees
defined ('_JEXEC') or die('resticted aceess');

// [button type="default" link="#" target="#" size="sm" class="color"]Button[/button]

if(!function_exists('calltoaction_sc')){
	function calltoaction_sc($atts, $content='') {
		extract(shortcode_atts(array(
					"link" => '',
					"class"=>'',
					"title"=>'Call to actions'
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

		ob_start(); ?>

		<div class="row">
			<div class="twelve col text-center">
				<?php echo do_shortcode($content);?>
				<a <?php echo $href.' '.$classes ;?> ><?php echo $title;?></a>
			</div>
		</div>

<?php
		$data = ob_get_clean();

		return $data;
	}
	add_shortcode('calltoaction', 'calltoaction_sc');
}