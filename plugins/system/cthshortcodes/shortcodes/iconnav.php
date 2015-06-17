<?php

//no direct accees
defined ('_JEXEC') or die('resticted aceess');

// [button type="default" link="#" target="#" size="sm" class="color"]Button[/button]

if(!function_exists('iconnav_sc')){
	function iconnav_sc($atts, $content='') {
		extract(shortcode_atts(array(
					"link" => '',
					"class"=>'',
					"icon"=>'',
					"title"=>''
				), $atts));
		$href ='';
		if($link){
			$href = 'href="'.$link.'"';
		}
		if(!empty($class)){
			$class = 'class="'.$class.'"';
		}
		

		ob_start(); ?>

		<div class="icon-nav">
			<a <?php echo $href.' '.$class ;?> >
		<?php if(!empty($icon)): ?>
				<i class="<?php echo $icon;?>"></i>
		<?php endif;?>
				<b><?php echo $title;?></b>
				<?php echo do_shortcode( $content ); ?>
			</a>
		</div>

<?php
		$data = ob_get_clean();

		return $data;
	}
	add_shortcode('iconnav', 'iconnav_sc');
}