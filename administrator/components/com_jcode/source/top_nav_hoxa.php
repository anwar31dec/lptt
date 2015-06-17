<?php 
$user = JFactory::getUser(); 
$userId=$user -> id;		
?>
<div class="hoxa-top-menu logmodule <?php echo ($userId>0?' ':' hideme ');?>">
<?php
	$document = JFactory::getDocument();
	$renderer = $document->loadRenderer('module');
	$Module =  JModuleHelper::getModule('mod_login');
	$Params = "param1=bruno\n\rparam2=chris";
	$Module->params = $Params;
	echo $renderer->render($Module);
?>
</div>
<style type="text/css">
.logmodule{float:right;}
.logmodule,.mod-languages,.hoxa-top-menu{position:relative;z-index:9999;} 
.login-greeting,.logout-button{float:left;color:#999;font-size:12px;}
.login-greeting{margin-right:10px}
.hideme{display:none;}
</style>
<script type="text/javascript">
	jQuery(function(){	
		<?php if($userId>0) :?>			
			jQuery('.top_nav ul.hoxa-top-menu li:eq(0),.top_nav ul.hoxa-top-menu li:eq(1)').hide();			
			jQuery('.logout-button .btn').removeClass('btn-primary').css('padding','0 12px');		
		<?php endif;?>
	});
</script>