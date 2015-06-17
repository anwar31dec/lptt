<?php 

defined('_JEXEC') or die;

?>

<!-- ######### CSS STYLES ######### -->
    
    <link rel="stylesheet" href="<?php echo $template_folder;?>/onepage_css/reset.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $template_folder;?>/onepage_css/style.css" type="text/css" />

    <!-- Google fonts -->
    <style>
        body, input, textarea {
            font-family: <?php echo $bodyfontfamily;?>;
        }
        h1, 
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: <?php echo $headingfontfamily;?>;
        }
        p {
            font-family: <?php echo $bodyfontfamily;?>;
        }
        blockquote {
            font-family: <?php echo $bodyfontfamily;?> !important;
        }
        pre {
            font-family: <?php echo $bodyfontfamily;?>;
        }
        code, kbd {
            font-family: <?php echo $bodyfontfamily;?>;
        }
        .funfacts span {
            font-family: <?php echo $headingfontfamily;?>;
        }
        .section4 .packagesinfo li.title h4,.section4 .packagesinfo li.title h2 {
            font-family: <?php echo $bodyfontfamily;?>;
        }
    </style>
    
    <link rel="stylesheet" href="<?php echo $template_folder;?>/onepage_css/font-awesome/css/font-awesome.min.css">
    
    <!-- responsive devices styles -->
    <link rel="stylesheet" media="screen" href="<?php echo $template_folder;?>/onepage_css/responsive-leyouts.css" type="text/css" />
    
    <?php if($animationTurnOff !== '1') :?>
    <!-- animations -->
    <link href="<?php echo $template_folder;?>/onepage_js/animations/css/animations.min.css" rel="stylesheet" type="text/css" media="all" />
    <?php endif;?>
    <!-- style switcher -->
    <link rel = "stylesheet" media = "screen" href = "<?php echo $template_folder;?>/onepage_js/style-switcher/color-switcher.css" />
    
    <!-- menu -->
    <link href="<?php echo $template_folder;?>/onepage_js/mainmenu/sticky.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $template_folder;?>/onepage_js/mainmenu/menu.css">
    
    <!-- MasterSlider -->
    <link rel="stylesheet" href="<?php echo $template_folder;?>/onepage_js/masterslider/style/masterslider.css" />
    <link href="<?php echo $template_folder;?>/onepage_js/masterslider/skins/default/style.css" rel='stylesheet' type='text/css'>
    <link href='<?php echo $template_folder;?>/onepage_js/masterslider/ms-fullscreen.css' rel='stylesheet' type='text/css'>
    
    <!-- cubeportfolio -->
    <link rel="stylesheet" type="text/css" href="<?php echo $template_folder;?>/onepage_js/cubeportfolio/cubeportfolio.min.css">
    
    <!-- carousel -->
    <link rel="stylesheet" href="<?php echo $template_folder;?>/onepage_js/carousel/flexslider.css" type="text/css" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?php echo $template_folder;?>/onepage_js/carousel/skin.css" />
    
    <!-- progressbar -->
    <link rel="stylesheet" href="<?php echo $template_folder;?>/onepage_js/progressbar/ui.progress-bar.css">
    
    <!-- forms -->
    <link rel="stylesheet" href="<?php echo $template_folder;?>/onepage_js/form/sky-forms.css" type="text/css" media="all">

    <!-- REVOLUTION BANNER CSS SETTINGS -->
    <link rel="stylesheet" type="text/css" href="<?php echo $template_folder; ?>/js/rs-plugin/style.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?php echo $template_folder; ?>/js/rs-plugin/settings.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?php echo $template_folder; ?>/js/rs-plugin/style-added.css" media="screen" />

    <?php if($preset !== 'default' && $overrideColor == '0') :?>
    <link rel="stylesheet" href="<?php echo $template_folder; ?>/onepage_css/colors/<?php echo $preset;?>.css" />
    <?php endif;?>
    <?php if($overrideColor == '1') : ?>
    <link rel="stylesheet/less" type="text/css" media="screen" href="<?php echo $template_folder; ?>/onepage_css/colors/color.php?bc=<?php echo $bC;?>">
    <script type="text/javascript" src="<?php echo $template_folder; ?>/js/less.js"></script>
    
    <?php endif;?>
    <?php if($layoutsite === 'boxed') :?>
    <link rel="stylesheet" href="<?php echo $template_folder; ?>/css/bg-patterns/pattern-<?php echo $pattern;?>.css" />
    <?php endif;?>