jQuery(document).ready(function($){

	$('.manager.thumbnails > li.thumbnail').css("float","left");

	$('.azp_tabs div').hide();
	$('.azp_tabs div:first').show();
	if($('.azp_tabs div:first').is('.azp_build')){
		$('.azp_tabs div:first').find('> *').show();
	}
	$('.azp_tabs ul li:first').addClass('active');
	$('.azp_tabs ul li a').click(function(){

		$('.azp_tabs ul li').removeClass('active');
		$(this).parent().addClass('active');
		var currentTab=$(this).attr('href');
		$('.azp_tabs div').hide();
		$(currentTab).show();
		if($(currentTab).is('.azp_build')){
			$(currentTab).find('> *').show();
		}
		return false;
	});

	// counter

	if(typeof jQuery.fn.appear === 'function'){
		jQuery(".azp_counter_wrap").appear(function(){
			jQuery('.azp_timer').countTo();
		});
	};
});