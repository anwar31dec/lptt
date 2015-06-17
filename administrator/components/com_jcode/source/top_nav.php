<?php
	$user = JFactory::getUser();
    $baseUrl = JURI::base() . 'administrator/components/com_jcode/source/';
    $jBaseUrl = JURI::base();
    $userName = $user->username; 
    $lang = JFactory::getLanguage();
    $lan = $lang->getTag();
    
    if($lan == 'en-GB'){
            $HeaderLang = 'Health Commodity Dashboard';
        }else{
			$HeaderLang = 'Tableau de Bord pour les Produits de Sante';
        } 
?>

<style type="text/css">
	.f90-logout-button{
		cursor: pointer !important;
		color: #ff0000 !important;
	}
</style>

<div style="margin-left:20px;">
<a href="<?php echo $jBaseUrl;?>" class="navbar-brand">
				<b><?php echo $HeaderLang; ?></b>
			</a><!-- /brand -->					
</div>	
			<!--
			<button type="button" class="navbar-toggle pull-left" id="sidebarToggle">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			-->
			<ul class="nav-notification clearfix">				
				<li class="profile dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#">
						<strong> <?php echo $user->name;?> </strong>
						<span><i class="fa fa-chevron-down"></i></span>
					</a>
					<ul class="dropdown-menu">						
						<li>
							
							<!-- <a class="f90-logout-button"><i class="fa fa-lock fa-lg"></i> Log out</a> -->
							
							<a tabindex="-1" class="main-link logoutConfirm_open" href="#logoutConfirm"><i class="fa fa-lock fa-lg"></i> Log out</a>
							
							</li>
							<!-- <li><a tabindex="-1" class="main-link logoutConfirm_open"  href="/warp/index.php/log-out"><i class="fa fa-lock fa-lg"></i>Log out</a></li> -->
					</ul>
				</li>
			</ul>
<style>
	#top-nav {
		height:90px !important;
		white-space: normal;
		/*border:1px solid red; */
	}
	#landing-content {
		padding-top: 102px;
	}
	.navbar-brand {
		font-size: 1.7em;
		color:#BECFE0 !important;
	}
	.navbar-header{
		min-height:102px;
	}
	.navbar-brand{
		margin-top:15px;
	}
	#main-container{
		padding-top:100px !important;
	}
	.slimScrollDiv{
		padding-top:58px;
	}
</style>