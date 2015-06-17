<?php
	$user     = JFactory::getUser();
	$baseUrl  = JURI::base() . 'administrator/components/com_jcode/source/';
	$jBaseUrl = JURI::base();
    $lang = JFactory::getLanguage();
$lan = $lang->getTag();
?>
<div style="clear:both">
<style type="text/css">

	.dropdown > a:after{
		content:"" !important;
	}
	
header#topNav nav ul.nav-main > li:hover > a, header#topNav nav ul.nav-main li.active > a, header#topNav nav ul.nav-main li.active > a:hover, header#topNav nav ul.nav-main li.active > a:focus, header#topNav nav ul.nav-main li.active i.icon-caret-down {
    color: #2e363f;
}
header#topNav nav.mega-menu ul.nav-main li.mega-menu-item ul.dropdown-menu, header#topNav nav ul.nav-main ul.dropdown-menu {
    background-color: #2e363f;
}
header#topNav nav.mega-menu ul.nav-main li.mega-menu-item:hover ul.sub-menu li:hover a:hover, header#topNav nav ul.nav-main ul.dropdown-menu li:hover > a {
    background-color: #1b1f23;
}
header#topNav nav ul.nav-main li.dropdown:hover > a:after {
    border-bottom: 10px solid #2e363f;
	margin-top:23px;
}
header#topNav nav ul.nav-main li.dropdown:nth-child(1):hover > a:after,
header#topNav nav ul.nav-main li.dropdown:nth-child(2):hover > a:after {
    border-bottom: none;
}
header#topNav nav ul.nav-main li.dropdown ul ul.dropdown-menu{
   /* display:block !important;*/
}
header#topNav nav ul.nav-main li.dropdown li.dropdown-submenu ul.dropdown-menu {
    margin-left:-390px !important;
}
header#topNav nav.mega-menu ul.nav-main li.mega-menu-item .mega-menu-sub-title {
    color: #ffffff;
}
header#topNav nav.mega-menu ul.nav-main li.mega-menu-item ul.sub-menu a, header#topNav nav ul.nav-main ul.dropdown-menu > li > a {
    color: #afafaf;
}
header#topNav{
	/*position:relative;*/
}
body.site{
	padding-top:0;
}
</style>
<!--<link rel="stylesheet" href="<?php echo JURI::base();?>templates/protostar/endless/css/menu_dashboard.css" type="text/css" />-->
<script type="text/javascript">
	$(function(){
		$('.mega-menu .nav').attr('class','nav nav-pills nav-main scroll-menu').attr('id','topMain');
		//console.log($('.mega-menu .nav li:eq(1)').attr('class'));
		ln=$('.mega-menu .nav li').length;
		for(i=0;i<ln;i++){
			//alert($('.mega-menu .nav li:eq('+i+')').attr('class'));
			isActive=$('.mega-menu .nav li:eq('+i+')').attr('class');
			if(isActive){
			isActive=isActive.split('active');
			//console.log(isActive);
			if(isActive.length==1){
				$('.mega-menu .nav li:eq('+i+')').attr('class','dropdown');		
			}else{
				$('.mega-menu .nav li:eq('+i+')').attr('class','dropdown current active');		
			}}
		}	
		$('.mega-menu .nav li a').attr('class','dropdown-toggle');						
		$('.mega-menu .nav ul').attr('style','');	
		
		$('#topMain .dropdown ul.nav-child').attr('class','dropdown-menu');		
		
		$('.mega-menu .nav .dropdown ul ul').parent().attr('class','dropdown-submenu symbol');
		$('.mega-menu .nav').show();
	})
</script>