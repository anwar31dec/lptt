<?php 

defined('_JEXEC') or die;

?>

<?php if ($this->countModules('footer-graph')) : ?>
  		<jdoc:include type="modules" name="footer-graph" style="none" />
	<?php endif;?>

	<?php if ($this->countModules('footer-four or footer-two')) : ?>
	<div class="clearfix"></div>

	<div class="footer1">
		<div class="container">

			<?php if ($this->countModules('footer-two')) : ?>
				<jdoc:include type="modules" name="footer-two"  style="footertwo" />
			<?php endif;?>

		    <div class="clearfix divider_dashed1"></div>

			<?php if ($this->countModules('footer-four')) : ?>
				<jdoc:include type="modules" name="footer-four"  style="footerfour" />
			<?php endif;?>
	  		
  		</div>
	</div><!-- end footer -->
	<?php endif;?>
    

	<?php if($this->countModules('footer-copyright')) :?>
		<div class="clearfix"></div>

		<div class="copyright_info">
			<div class="container">

				<div class="clearfix divider_dashed10"></div>
			    
			    <jdoc:include type="modules" name="footer-copyright" style="none" />
			    
			</div>
		</div><!-- end copyright info -->

	<?php endif;?>
	
	<!-- end scroll to top of the page-->
	<a href="#" class="scrollup">Scroll</a>

	<?php if ($this->countModules('debug')) : ?>
  		<jdoc:include type="modules" name="debug" style="none" />
	<?php endif;?>


</div>
<!-- /.site_wrapper -->
<?php if($layoutsite === 'boxed') :?>
</div>
<!-- /.wrapper_boxed -->
<?php endif;?>

<!-- ######### JS FILES ######### -->
<!-- get jQuery from the google apis -->
<!-- <script type="text/javascript" src="<?php echo $template_folder; ?>/js/universal/jquery.js"></script> -->

<!-- style switcher -->
<!-- <script src="<?php echo $template_folder; ?>/js/style-switcher/jquery-1.js"></script>
<script src="<?php echo $template_folder; ?>/js/style-switcher/styleselector.js"></script>
 -->
<?php if($animationTurnOff !== '1') :?>
<!-- animations -->
<script src="<?php echo $template_folder; ?>/js/animations/js/animations.min.js" type="text/javascript"></script>
<?php endif;?>

<!-- slide panel -->
<script type="text/javascript" src="<?php echo $template_folder; ?>/js/slidepanel/slidepanel.js"></script>

<!-- Master Slider -->
<!-- <script src="<?php echo $template_folder; ?>/js/masterslider/jquery.easing.min.js"></script>
<script src="<?php echo $template_folder; ?>/js/masterslider/masterslider.min.js"></script> -->
<!--<script type="text/javascript">
(function($) {
 "use strict";

var slider = new MasterSlider();
 slider.setup('masterslider' , {
     width: 1400,    // slider standard width
     height:580,   // slider standard height
     space:0,
	 speed:45,
     fullwidth:true,
     loop:true,
     preload:0,
     autoplay:true,
	 view:"basic"
});
// adds Arrows navigation control to the slider.
slider.control('arrows');
slider.control('bullets');

})(jQuery);

</script>-->

<!-- mega menu -->
<script src="<?php echo $template_folder; ?>/js/mainmenu/bootstrap.min.js"></script> 
<script src="<?php echo $template_folder; ?>/js/mainmenu/customeUI.js"></script> 

<!-- jquery jcarousel -->
<script type="text/javascript" src="<?php echo $template_folder; ?>/js/carousel/jquery.jcarousel.min.js"></script>

<!-- scroll up -->
<script src="<?php echo $template_folder; ?>/js/scrolltotop/totop.js" type="text/javascript"></script>

<!-- tabs -->
<script src="<?php echo $template_folder; ?>/js/tabs/assets/js/responsive-tabs.min.js" type="text/javascript"></script>

<!-- jquery jcarousel -->
<script type="text/javascript">
(function($) {
 "use strict";

	jQuery(document).ready(function() {
			jQuery('#mycarouselthree').jcarousel();
	});
	
})(jQuery);
</script>

<!-- accordion -->
<script type="text/javascript" src="<?php echo $template_folder; ?>/js/accordion/custom.js"></script>

<!-- sticky menu -->
<script type="text/javascript" src="<?php echo $template_folder; ?>/js/mainmenu/sticky.js"></script>
<script type="text/javascript" src="<?php echo $template_folder; ?>/js/mainmenu/modernizr.custom.75180.js"></script>

<!-- progress bar -->
<script src="<?php echo $template_folder; ?>/js/progressbar/progress.js" type="text/javascript" charset="utf-8"></script>

<!-- cubeportfolio -->
<script type="text/javascript" src="<?php echo $template_folder; ?>/js/cubeportfolio/jquery.cubeportfolio.min.js"></script>
<!-- <script type="text/javascript" src="<?php echo $template_folder; ?>/js/cubeportfolio/main.js"></script>
<script type="text/javascript" src="<?php echo $template_folder; ?>/js/cubeportfolio/main5.js"></script>
<script type="text/javascript" src="<?php echo $template_folder; ?>/js/cubeportfolio/main6.js"></script> -->

<!-- carousel -->
<script defer src="<?php echo $template_folder; ?>/js/carousel/jquery.flexslider.js"></script>
<script defer src="<?php echo $template_folder; ?>/js/carousel/custom.js"></script>

<!-- lightbox -->
<script type="text/javascript" src="<?php echo $template_folder; ?>/js/lightbox/jquery.fancybox.js"></script>
<script type="text/javascript" src="<?php echo $template_folder; ?>/js/lightbox/custom.js"></script>

<script src="<?php echo $template_folder; ?>/js/form/jquery.form.min.js"></script>
<script src="<?php echo $template_folder; ?>/js/form/jquery.validate.min.js"></script>
