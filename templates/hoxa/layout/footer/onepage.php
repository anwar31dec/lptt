<?php

defined('_JEXEC') or die;

?>

    

	<?php if($this->countModules('footer-onepage')) :?>
		<div class="clearfix"></div>

		<div class="copyrights">
			<div class="container">

				<jdoc:include type="modules" name="footer-onepage" style="none" />
				
			    

			</div>
		</div><!-- end copyrights section -->

		
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
<!-- <script type="text/javascript" src="<?php echo $template_folder; ?>/onepage_js/universal/jquery.js"></script> -->

<!-- style switcher -->
<!-- <script src="<?php echo $template_folder; ?>/onepage_js/style-switcher/jquery-1.js"></script>
<script src="<?php echo $template_folder; ?>/onepage_js/style-switcher/styleselector.js"></script> -->

<?php if($animationTurnOff !== '1') :?>
<!-- animations -->
<script src="<?php echo $template_folder; ?>/onepage_js/animations/js/animations.min.js" type="text/javascript"></script>
<?php endif;?>

<!-- Master Slider -->
<!--<script src="js/masterslider/jquery-1.10.2.min.js"></script>
<script src="js/masterslider/jquery.easing.min.js"></script>-->
<!-- <script src="<?php echo $template_folder; ?>/onepage_js/masterslider/masterslider.min.js"></script>
<script type="text/javascript">		
(function($) {
 "use strict";

	var slider = new MasterSlider();

	slider.control('arrows' ,{insertTo:'#masterslider'});	
	slider.control('bullets');	

	slider.setup('masterslider' , {
		width:1170,
		height:700,
		space:5,
		view:'basic',
		fullwidth:true,
		speed:20,
		autoplay:true,
		loop:true,
	});
	
})(jQuery);
</script>
 -->
<!-- scroll up -->
<script src="<?php echo $template_folder; ?>/onepage_js/scrolltotop/totop.js" type="text/javascript"></script>

<!-- sticky menu -->
<script type="text/javascript" src="<?php echo $template_folder; ?>/onepage_js/mainmenu/sticky.js"></script>
<script type="text/javascript" src="<?php echo $template_folder; ?>/onepage_js/mainmenu/modernizr.custom.75180.js"></script>

<!-- forms -->
<script src="<?php echo $template_folder; ?>/onepage_js/form/jquery.form.min.js"></script>
<script src="<?php echo $template_folder; ?>/onepage_js/form/jquery.validate.min.js"></script>
<script type="text/javascript">
(function($) {
 "use strict";

	$(function()
	{
		// Validation
		$("#sky-form").validate(
		{					
			// Rules for form validation
			rules:
			{
				name:
				{
					required: true
				},
				email:
				{
					required: true,
					email: true
				},
				message:
				{
					required: true,
					minlength: 10
				}
			},
								
			// Messages for form validation
			messages:
			{
				name:
				{
					required: 'Please enter your name',
				},
				email:
				{
					required: 'Please enter your email address',
					email: 'Please enter a VALID email address'
				},
				message:
				{
					required: 'Please enter your message'
				}
			},
								
			// Ajax form submition					
			submitHandler: function(form)
			{
				$(form).ajaxSubmit(
				{
					success: function()
					{
						$("#sky-form").addClass('submited');
					}
				});
			},
			
			// Do not change code below
			errorPlacement: function(error, element)
			{
				error.insertAfter(element.parent());
			}
		});
	});			

})(jQuery);
</script>

<!-- progress bar -->
<script src="<?php echo $template_folder; ?>/onepage_js/progressbar/progress.js" type="text/javascript" charset="utf-8"></script>


<!-- menu -->
<script src="<?php echo $template_folder; ?>/onepage_js/mainmenu/responsive-nav.js"></script>
<script src="<?php echo $template_folder; ?>/onepage_js/mainmenu/fastclick.js"></script>
<script src="<?php echo $template_folder; ?>/onepage_js/mainmenu/scroll.js"></script>
<script src="<?php echo $template_folder; ?>/onepage_js/mainmenu/fixed-responsive-nav.js"></script>

<!-- animate number -->
<script src="<?php echo $template_folder; ?>/onepage_js/aninum/jquery.animateNumber.min.js"></script>

<!-- cubeportfolio -->
<script type="text/javascript" src="<?php echo $template_folder; ?>/onepage_js/cubeportfolio/jquery.cubeportfolio.min.js"></script>
<script type="text/javascript" src="<?php echo $template_folder; ?>/onepage_js/cubeportfolio/main.js"></script>

<!-- carousel -->
<script defer src="<?php echo $template_folder; ?>/onepage_js/carousel/jquery.flexslider.js"></script>
<script defer src="<?php echo $template_folder; ?>/onepage_js/carousel/custom.js"></script>