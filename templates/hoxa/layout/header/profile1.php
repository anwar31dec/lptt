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
        
    	<div id="openCloseWrap"><a href="#" class="topMenuAction" id="topMenuImage"><img src="<?php echo JURI::root(true).'/templates/'.$this->template;?>/js/slidepanel/open.png" alt="open" title="open" /></a></div>
            
    </div>

<div class="clearfix"></div>

<?php endif;?>

<header id="header">
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
	    
		     <div class="container">
		    
				<!-- Logo -->
				<div class="logo"><?php echo $logo;?><span class="focus-countries">Lab process time tracking</span></div>
				
				<!-- Menu -->
				<div class="menu_main">
			        
					<div class="navbar yamm navbar-default">
				    
				    <div class="container">
				      <div class="navbar-header">
				        <div class="navbar-toggle .navbar-collapse .pull-right " data-toggle="collapse" data-target="#navbar-collapse-1"  > <span><?php echo JText::_('TPL_HOXA_MENU_MENU_TEXT');?></span>
				          <button type="button" > <i class="fa fa-bars"></i></button>
				        </div>
				      </div>
				      
				      <div id="navbar-collapse-1" class="navbar-collapse collapse pull-right">
				      
				        <jdoc:include type="modules" name="main_nav" style="none" />
				        <?php if($this->countModules('top_search')):?>
				        <div id="wrap">
				          <jdoc:include type="modules" name="top_search" style="none" />
				        </div>  
				        <?php endif;?>
				      </div>
				      </div>
				    </div>
			     
				</div><!-- end menu -->
		        
			</div>
			
		</div>
    
	</div>
    
</header>

<div class="clearfix"></div>
