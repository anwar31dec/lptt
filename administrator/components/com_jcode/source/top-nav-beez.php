<?php
	$user = JFactory::getUser();    	
	$baseUrl=JURI::base().'administrator/components/com_jcode/source/';	
	$jBaseUrl=JURI::base();
?>
<div class="navbar-header col-sm-9">
	<button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
		<span class="sr-only">Toggle navigation</span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
	</button>
	<div class="col-sm-12">
		<a href="index.php/home" class="navbar-brand"> <b>Malaria FP & MCH Dashboard</b> </a>
	</div>
	<div class="col-sm-12">
	<br>	
		<span style="color:red"><b>Focus Countries: Mali - South Sudan - Guinea</b></span>
	</div>
	
</div>
<script type="text/javascript">
	$(function() {
		$('.nav.menu').attr('class', 'nav navbar-nav navbar-right');
	}); 
</script>
<style>
	#top-nav {
		height: 100px;
		white-space: normal;
	}
	#landing-content {
		padding-top: 102px;
	}
	.navbar-brand {
		font-size: 1.7em;
	}
	.navbar-header{
		min-height:102px;
	}
	.navbar-brand{
		margin-top:15px;
	}
</style>