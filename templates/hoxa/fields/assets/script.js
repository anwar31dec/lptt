function changeFont(ele){ 
	var $ele = jQuery(ele);

	var id = $ele.attr('id').split("_");
	var variantsField = jQuery('fieldset#'+$ele.attr('id')+'variants');
	var familyField = jQuery('#'+$ele.attr('id')+'family');

	console.log(familyField);


	var fontCSS = $ele.find("option:selected").val();
	familyField.val(window.fontsFamily[fontCSS]);
	var fontVariantsArray = window.fontVariants[fontCSS]; 
	//console.log(fontVariantsArray);

	var input = '';

	for	(index = 0; index < fontVariantsArray.length; index++) {
	    //text += fontVariantsArray[index];
	    input += '<li><input aria-invalid="false" id="jform_params_'+id[2]+'variants'+index+'" name="jform[params]['+id[2]+'variants][]" value="'+fontVariantsArray[index]+'" type="checkbox"><label aria-invalid="false" for="jform_params_'+id[2]+'variants'+index+'">'+fontVariantsArray[index]+'</label></li>';
	} 

	variantsField.children().html(input);
}

//window.changeFont = changeFont;

(function($){
	$(document).ready(function() {
		$('.radio.colorpresets label').addClass('preset');
		$('.colorpresets input').each(function()
		{
			$('label[for=' + $(this).attr('id') + ']').addClass($(this).val());
		});
		$('.colorpresets label:not(.active)').click(function()
		{
			var label = $(this);
			var input = $('#' + label.attr('for'));

			if (!input.prop('checked')) {
				label.closest('.colorpresets').find('label').removeClass('active');
				// if (input.val() == '') {
				// 	label.addClass('active preset');
				// } else if (input.val() == 0) {
				// 	label.addClass('active preset');
				// } else {
					label.addClass('active preset');
				//}
				input.prop('checked', true);
			}
		});

		$('.colorpresets input[checked=checked]').each(function()
		{
			// if ($(this).val() == '') {
			// 	$('label[for=' + $(this).attr('id') + ']').addClass('active btn-primary');
			// } else if ($(this).val() == 0) {
			// 	$('label[for=' + $(this).attr('id') + ']').addClass('active btn-danger');
			// } else {
				$('label[for=' + $(this).attr('id') + ']').addClass('active preset');
			//}
		});
	});
})(jQuery);