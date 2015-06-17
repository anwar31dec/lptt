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

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');


$doc = JFactory::getDocument();

$doc->addStyleSheet(JURI::base(true).'/components/com_azurapagebuilder/assets/fancybox/jquery.fancybox.css');
$doc->addStyleSheet(JURI::base(true).'/components/com_azurapagebuilder/assets/css/style.css');
$doc->addStyleSheet(JURI::base(true).'/components/com_azurapagebuilder/assets/css/jquery-ui.min.css');

//$doc->addStyleSheet('//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css');
$doc->addStyleSheet(JURI::base(true).'/components/com_azurapagebuilder/assets/font-awesome/css/font-awesome.min.css');

$doc->addScript(JURI::base(true).'/components/com_azurapagebuilder/assets/js/jquery.min.js');
$doc->addScript(JURI::base(true).'/components/com_azurapagebuilder/assets/js/jquery-ui.min.js');


$user  = JFactory::getUser();
$input = JFactory::getApplication()->input;

?>
<form action="index.php?option=com_azurapagebuilder&amp;task=element.iconfonts" id="iconForm" method="post" enctype="multipart/form-data" style="height:95%;">
	<div class="row-fluid" style="padding-top:20px;">
		<div class="span9">
				
			<input class="input-large" name="q" id="icon-searchbox" type="text" placeholder="Search for icon">

			<label class="radio inline" style="padding-top:0px; margin-bottom:11px; margin-left:20px;">
			  	<input type="radio" class="iconsizeselect"  name="fa-size"  value="fa-lg"> lg
			</label>
			<label class="radio inline" style="padding-top:0px; margin-bottom:11px;">
			  	<input type="radio" class="iconsizeselect"   name="fa-size"  value="fa-2x"> 2x
			</label>
			<label class="radio inline" style="padding-top:0px; margin-bottom:11px;">
			  	<input type="radio" class="iconsizeselect"   name="fa-size" value="fa-3x"> 3x
			</label>
			<label class="radio inline" style="padding-top:0px; margin-bottom:11px;">
			  	<input type="radio" class="iconsizeselect" name="fa-size"  value="fa-4x"> 4x
			</label>
			<label class="radio inline" style="padding-top:0px; margin-bottom:11px;">
			  	<input type="radio" class="iconsizeselect"   name="fa-size"  value="fa-5x"> 5x
			</label>

		</div>
		<div class="span3">
			<div class="form-horizontal">
				<div class="control-group pull-right">
					<button class="btn btn-primary" type="button" id="use-fontbtn">Select</button>
					<button class="btn" type="button" onclick="window.parent.SqueezeBox.close();"><?php echo JText::_('JCANCEL') ?></button>
				</div>
			</div>


		</div>
	</div>

	<div class="row-fluid">
		<div class="span12">
			<div class="iconswrapper" id="iconswrapper" style="height: 400px; overflow: auto; border: 1px solid #ccc; padding: 10px 10px;">
				<?php
					$awesomefont = ebor_icons_list();

					$return = array();

					foreach ($awesomefont as $font => $name) {
						$html[] = '<div class="icon-select">';
						$html[] = '<i data-font="'.$font.'" class="fa '.$font.' fa-2x"></i>';
						$html[] = '</div>';
					}

					echo implode("\n", $html);
					
				?>
			</div>
		</div>
	</div>

	<input type="hidden" id="f_url" value="">

</form>
<script>
	jQuery(document).ready(function($){
		$('body').on('submit', '#iconForm', function(event){
			event.preventDefault();
		});
		$('body').on('keyup', '#icon-searchbox', function(event){
			event.preventDefault();
			//$this = $(this);

			$iconForm = $('#iconForm');

			$.ajax({
				type: "POST",
				url: $iconForm.attr('action'),
				dataType: 'html',
				cache: false,
				data: $iconForm.serialize(),
				success: function(data) {

					$('#iconswrapper').html(data);
				}
			});
		});


		$('body').on('click', 'i', function(event) {
			event.stopPropagation();
			event.preventDefault();
			/* Act on the event */

			$('.icon-select.select').removeClass('select');
			$(this).parent().addClass('select');
			$('#f_url').val($(this).attr('data-font'));
		});

		$('body').on('click', '#use-fontbtn', function(event) {
			event.preventDefault();

			var font = 'fa '+ $('#f_url').val() ;
			if($('input[name="fa-size"]:checked').val()){
				font += ' ' + $('input[name="fa-size"]:checked').val();
			}

			window.parent.jInsertIconClassValue(font ,"<?php echo $this->state->get('field.id');?>");
			window.parent.SqueezeBox.close();
		});
	});
</script>