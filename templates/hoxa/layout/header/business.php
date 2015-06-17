<?php 

defined('_JEXEC') or die;

$logo = '';
if(!empty($logoImage)){
	$logo .= '<a href="'.JURI::root(true).'" id="logo"  title="'.$sitename.'" alt="'.$sitename.'" style="background-image:url('.JURI::root(true).'/'.$logoImage.');"></a>';
}elseif(!empty($logoText)){
	$logo .= '<h1><a href="index.php#intro"'.(($pageType == 'onePage')? ' class="scroll-link"': '').'>'.$logoText.'</a></h1>';
}elseif(empty($logo)){
	$logo .= '<h1><a href="index.php#intro"'.(($pageType == 'onePage')? ' class="scroll-link"': '').'>'.$sitename.'</a></h1>';
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


<header id="header">
	
	<div id="trueHeader">
    
	<div class="wrapper">
    
    
    <div class="logoarea">
    <div class="container">
    
    <!-- Logo -->
    <div class="logo"><?php echo $logo;?></div>
    
	<div class="right_links">
        
        <jdoc:include type="modules" name="top_nav" style="none" />
        
    </div><!-- end right links -->
    
    
    </div>
    </div>
		
	<!-- Menu -->
	<div class="menu_main">
    
    <div class="container">
        
	<div class="navbar yamm navbar-default">
    
    <div class="container">
      <div class="navbar-header">
        <div class="navbar-toggle .navbar-collapse .pull-right " data-toggle="collapse" data-target="#navbar-collapse-1"  > <span>Menu</span>
          <button type="button" > <i class="fa fa-bars"></i></button>
        </div>
      </div>
      
      <div id="navbar-collapse-1" class="navbar-collapse collapse">
      
        <jdoc:include type="modules" name="main_nav" style="none" />
        <?php if($this->countModules('top_search')):?>
        <div id="wrap">
          <jdoc:include type="modules" name="top_search" style="none" />
        </div>  
        <?php endif;?> 
      </div>
      </div>
     </div>
     
	</div>
    </div><!-- end menu -->
        
	</div>
    
	</div>
    
</header>

<div class="clearfix"></div>