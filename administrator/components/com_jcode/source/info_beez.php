<?php
defined('_JEXEC') or die;

require_once JPATH_SITE.'/components/com_users/helpers/route.php';

JHtml::_('behavior.keepalive');
JHtml::_('bootstrap.tooltip');

$user = JFactory::getUser(); 

$userId=$user -> id;
if($userId>0){
?>

<style type="text/css">
	.btn-dashboard{
		color: #ffffff;
	}
	.btn.btn-dashboard {
		background: none repeat scroll 0 0 #9EA615;
		border: 1px solid #5CA615;
		font-size: 2em;
	}
	.btn.btn-dashboard.active, .btn.btn-dashboard:active, .btn.btn-dashboard:focus, .btn.btn-dashboard:hover {
		color: #ffffff;
	}
	.btn.btn-dashboard.active, .btn.btn-dashboard:active, .btn.btn-dashboard:focus, .btn.btn-dashboard:hover {
		background: none repeat scroll 0 0 #7E840F;
		transition: all 0.3s ease 0s;
	}
</style>

<div class="alert alert-info">Welcome to OSP-SIDA<br /><a href="<?php JURI::base();?>?template=protostar">
	<!-- <button class="btn btn-info btn-sm" type="button">Go to Dashboard</button> -->
	<button type="button" class="btn btn-dashboard">Go to Dashboard</button>
	</a></div>
<?php	
}else{	
?>

<script type="text/javascript">
	$(function(){
		//$('#login-form1 [name=return]').val($('#login-form [name=return]').val());
	});
	//alert($('#login-form [name=return]').val());
</script>
<?php
}
?>
