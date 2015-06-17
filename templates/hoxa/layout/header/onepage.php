<?php 

defined('_JEXEC') or die;

$logo = '';
if(!empty($logoImage)){
	$logo .= '<a href="#home" data-scroll ><img src="'.JURI::root(true).'/'.$logoImage.'" alt="'.$sitename.'" /></a>';
}elseif(!empty($logoText)){
	$logo .= '<a href="#home" data-scroll class="logo_text">'.$logoText.'</a>';
}elseif(empty($logo)){
	$logo .= '<a href="#home" data-scroll  class="logo_text">'.$sitename.'</a>';
}

?>

<?php if($this->countModules('top_intro')) :?>
<div id="sliderWrap">

    	<div id="openCloseIdentifier"></div>
        
            <div id="slider">
            
              	<div id="sliderContent">
                	<div class="container">
              
                		<jdoc:include type="modules" name="top_intro" style="topintro" />
                    
                	</div>    
                </div>
                
            </div>
        
    	<div id="openCloseWrap"><a href="#" class="topMenuAction" id="topMenuImage"><img src="js/slidepanel/open.png" alt="open" title="open" /></a></div>
            
    </div>

<div class="clearfix"></div>

<?php endif;?>

<div id="header">
	
	<?php if($this->countModules('top_nav')):?>
	<!-- Top header bar -->
	<div id="topHeader">
    
		<div class="wrapper">
	         
	        <div class="top_nav">
	        <div class="container">
	                
	        <div class="right">
	            
	            <jdoc:include type="modules" name="top_nav" style="none" />
	            
	        </div><!-- end right social links -->
	        
	        </div>
	        </div>
	            
	 	</div>
    
	</div><!-- end top navigation -->
	
    <?php endif;?>
    
	<div id="trueHeader">
    
	<div class="wrapper">
    
     <div class="container_full">
    		
        <header>
        
            <!-- Logo -->
            <div class="logo"><?php echo $logo;?></div>
            
            <!-- Menu -->
            <div class="menu_main">
            
            	<jdoc:include type="modules" name="main_nav" style="none" />
            
            </div><!-- end menu -->  
        
        </header>
        
     </div>
		
	</div>
    
	</div>
    
</div>

<div class="clearfix"></div>