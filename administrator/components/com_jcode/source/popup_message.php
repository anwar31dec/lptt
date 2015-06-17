<script src="<?php echo JURI::base(); ?>templates/<?php echo TPL_NAME;?>/media/js/jquery.popupoverlay.min.js"></script> 
<!-- Gritter -->
<link href="<?php echo JURI::base(); ?>templates/<?php echo TPL_NAME;?>/media/gritter/css/gritter/jquery.gritter.css" rel="stylesheet">
<!-- Gritter -->
<script src="<?php echo JURI::base(); ?>templates/<?php echo TPL_NAME;?>/media/gritter/js/jquery.gritter.min.js"></script> 
<div class="custom-popup light width-100" id="lightCustomModal" style="display:none;">
	<div class="padding-md">
		<h4 class="m-top-none"> This is alert message.</h4>
	</div>

	<div class="text-center">
		<a href="#" class="btn btn-success m-right-sm lightCustomModal_close" onClick="onConfirm()">Confirm</a>
		<a href="#" class="btn btn-danger lightCustomModal_close">Cancel</a>
	</div>
</div>  
<a href="#lightCustomModal" style="display:none;" class="btn btn-warning btn-small lightCustomModal_open">Edit</a>
<style>
#gritter-notice-wrapper{	
	top:55px;	
	right:20px;
}
</style>
<script type="text/javascript">
	function onSuccessMsg(msg){
		$('.gritter-item p').html(msg);
		$('.gritter-item p, #gritter-notice-wrapper').attr('class','success');		
		onAnimateMsg(msg,'gritter-success','success');
	}
	function onWarningMsg(msg){
		$('.gritter-item p').html(msg);
		$('.gritter-item p').attr('class','warning');
		$('.gritter-item p, #gritter-notice-wrapper').attr('class','warning');
		onAnimateMsg(msg,'gritter-warning','warning');	
	}
	function onErrorMsg(msg){
		$('.gritter-item p').html(msg);
		$('.gritter-item p').attr('class','error');
		$('.gritter-item p, #gritter-notice-wrapper').attr('class','error');
		onAnimateMsg(msg,'gritter-danger','error');	
	}
	function onInfoMsg(msg){
		$('.gritter-item p').html(msg);
		$('.gritter-item p').attr('class','info');
		$('.gritter-item p, #gritter-notice-wrapper').attr('class','info');
		onAnimateMsg(msg,'gritter-info','info');	
	}
	function onAnimateMsg(msg,class_name,title){		
		$.gritter.add({
			title: '<i class="fa fa-times-circle"></i> This is a '+title+' notification!',
			text: msg,
			sticky: false,
			time: 2000,
			class_name: class_name
		});
		return false;
	}	
	jQuery(function(){		
		jQuery('#lightCustomModal').popup({
				pagecontainer: '.container',
				 transition: 'all 0.3s'
			});
	});
	// lightCustomModal
	function onConfirm(){
		console.log('Its time to process.');
	}
	function onCustomModal(msg, onConfirm){
		//console.log(jQuery('.m-top-none'));
		$('.m-top-none').html(msg);
		$('.lightCustomModal_close.btn-success').attr('onClick', onConfirm + '()')
		$('.lightCustomModal_open').click();
	}	
</script>

<link href="<?php echo JURI::base(); ?>templates/<?php echo TPL_NAME;?>/media/css/global-custom.css" rel="stylesheet">
<script src="<?php echo JURI::base(); ?>templates/<?php echo TPL_NAME;?>/media/js/parsley.min.js"></script> 