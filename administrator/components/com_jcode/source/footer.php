<?php 
	$user = JFactory::getUser();	
	$baseUrl = JURI::base() . 'administrator/components/com_jcode/source/';	
	//echo $jBaseUrl;
?>
<style>
	footer .footer-brand {
		margin-right: 10px;
	}
	footer{
		margin-left:0px;
	}
</style>
<div class="row">
	<div class="col-sm-12" >
		<span class="footer-brand">
		<div style="margin:0 auto;">
			<center>
			<!--
                <div class="col-sm-2"><a href="http://www.usaid.gov/" target="_blank"><img src="<?php echo $baseUrl;?>images/footer_usaid.png" border="0" /></a></div>
                <div class="col-sm-2"><a href="http://www.siapsprogram.org/" target="_blank"><img src="<?php echo $baseUrl;?>images/footer_siaps.png" border="0" /></a></div>
                <div class="col-sm-2" style="float: right;"><a href="http://www.msh.org/" target="_blank"><img src="<?php echo $baseUrl;?>images/footer_msh.png" border="0" /></a></div>			
				-->
				<img src="<?php echo $baseUrl;?>images/websitefooter1.png" usemap="#Map" />
			<map id="Map" name="Map">
				<area shape="rect" coords="0,1,180,52"  href="http://www.usaid.gov/" target="_blank" />
				<area shape="rect" coords="195,1,380,52" href="http://siapsprogram.org/" target="_blank" />
				<area shape="rect" coords="845,1,980,52" href="http://www.msh.org/" target="_blank" />
			</map>
			</center>			
		</div>
	</div><!-- /.col -->
</div><!-- /.row-->