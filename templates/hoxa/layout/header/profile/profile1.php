<?php 

defined('_JEXEC') or die;

?>

<!-- ######### CSS STYLES ######### -->
	<?php if($layoutsite === 'boxed') :?>
    <link rel="stylesheet" href="<?php echo $template_folder; ?>/css/boxed-reset.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo $template_folder; ?>/css/boxed-style.css" type="text/css" />
    <?php else:?>
    <link rel="stylesheet" href="<?php echo $template_folder; ?>/css/reset.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $template_folder; ?>/css/style.css" type="text/css" />
    <?php endif;?>
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
        .feature_section11 strong {
            font-family: <?php echo $highlightfontfamily;?>;
        }
        .feature_section15 strong {
            font-family: <?php echo $highlightfontfamily;?>;
        }
        .feature_section17 .tbox {
            font-family: <?php echo $highlightfontfamily;?>;
        }
        .feature_section25 li.title h1 {
            font-family: <?php echo $bodyfontfamily;?>;
        }
        .newsletter_two .input_submit {
            font-family: <?php echo $bodyfontfamily;?>;
        }
        .fnewsletter .input_submit {
            font-family: <?php echo $bodyfontfamily;?>;
        }
        .big_text1 {
            font-family: <?php echo $highlightfontfamily;?>;
        }
        .error_pagenotfound {
            font-family: <?php echo $bodyfontfamily;?>;
        }
        a.but_goback,
        a.but_ok_2,
        a.but_wifi,
        a.but_warning_sign,
        a.but_user,
        a.but_tag,
        a.but_table,
        a.but_star,
        a.but_search,
        a.but_phone,
        a.but_pencil,
        a.but_new_window,
        a.but_music,
        a.but_hand_right,
        a.but_thumbs_down,
        a.but_thumbs_up,
        a.but_globe,
        a.but_hospital,
        a.but_coffe_cup,
        a.but_settings,
        a.but_chat,
        a.but_play_button,
        a.but_remove_2,
        a.but_lock,
        a.but_shopping_cart,
        a.but_exclamation_mark,
        a.but_info,
        a.but_question_mark,
        a.but_minus,
        a.but_plus,
        a.but_folder_open,
        a.but_file,
        a.but_envelope,
        a.but_edit,
        a.but_cogwheel,
        a.but_check,
        a.but_camera,
        a.but_calendar,
        a.but_bookmark,
        a.but_book,
        a.but_download,
        a.but_pdf,
        a.but_word_doc,
        a.but_woman {
            font-family: <?php echo $bodyfontfamily;?>;
        }
        .dropcap1, .dropcap2, .dropcap3 {
            font-family: <?php echo $bodyfontfamily;?>;
        }
        .pricing-tables .title, .pricing-tables .price {
            font-family: <?php echo $bodyfontfamily;?>;
        }
        .pricing-tables-helight .title,.pricing-tables-helight .price {
            font-family: <?php echo $bodyfontfamily;?>;
        }
        .pricing-tables-two .title,.pricing-tables-two .price {
            font-family: <?php echo $bodyfontfamily;?>;
        }
        .pricing-tables-helight-two .title,.pricing-tables-helight-two .price {
            font-family: <?php echo $bodyfontfamily;?>;
        }
        .about_author a {
            font-family: <?php echo $bodyfontfamily;?> !important;
        }
        .pagination,.comment_submit,.portfolio_image .title {
            font-family: <?php echo $bodyfontfamily;?>;
        }
        .rw-wrapper{
            font-family: <?php echo $highlightfontfamily;?>;
        }
    </style>
    
    <link rel="stylesheet" href="<?php echo $template_folder; ?>/css/font-awesome/css/font-awesome.min.css">
    
    <!-- responsive devices styles -->
    <?php if($layoutsite === 'boxed') :?>
    <link rel="stylesheet" media="screen" href="<?php echo $template_folder; ?>/css/boxed-responsive-leyouts.css" type="text/css" />
    <?php else :?>
    <link rel="stylesheet" media="screen" href="<?php echo $template_folder; ?>/css/responsive-leyouts.css" type="text/css" />
    <?php endif;?>
    <?php if($animationTurnOff !== '1') :?>
    <!-- animations -->
    <link href="<?php echo $template_folder; ?>/js/animations/css/animations.min.css" rel="stylesheet" type="text/css" media="all" />
    <?php endif;?>

    <!-- style switcher -->
    <link rel = "stylesheet" media = "screen" href = "<?php echo $template_folder; ?>/js/style-switcher/color-switcher.css" />
    
    <!-- mega menu -->
    <?php if($layoutsite === 'boxed') :?>
    <link href="<?php echo $template_folder; ?>/css/boxed-sticky.css" rel="stylesheet">
    <?php else:?>
    <link href="<?php echo $template_folder; ?>/js/mainmenu/sticky.css" rel="stylesheet">
    <?php endif;?>
    <link href="<?php echo $template_folder; ?>/js/mainmenu/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $template_folder; ?>/js/mainmenu/demo.css" rel="stylesheet">
    <link href="<?php echo $template_folder; ?>/js/mainmenu/menu.css" rel="stylesheet">
    
    <!-- slide panel -->
    <link rel="stylesheet" type="text/css" href="<?php echo $template_folder; ?>/js/slidepanel/slidepanel.css">
    
	<!-- Master Slider -->
    <link rel="stylesheet" href="<?php echo $template_folder; ?>/js/masterslider/style/masterslider.css" />
    <link rel="stylesheet" href="<?php echo $template_folder; ?>/js/masterslider/skins/default/style.css" />
    <link rel="stylesheet" href="<?php echo $template_folder; ?>/js/masterslider/style.css" />

	<!-- cubeportfolio -->
	<link rel="stylesheet" type="text/css" href="<?php echo $template_folder; ?>/js/cubeportfolio/cubeportfolio.min.css">
    
	<!-- tabs -->
    <link rel="stylesheet" type="text/css" href="<?php echo $template_folder; ?>/js/tabs/assets/css/responsive-tabs.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $template_folder; ?>/js/tabs/assets/css/responsive-tabs2.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $template_folder; ?>/js/tabs/assets/css/responsive-tabs3.css">
	<!-- carousel -->
    <link rel="stylesheet" href="<?php echo $template_folder; ?>/js/carousel/flexslider.css" type="text/css" media="screen" />
 	<link rel="stylesheet" type="text/css" href="<?php echo $template_folder; ?>/js/carousel/skin.css" />
    
    <!-- progressbar -->
  	<link rel="stylesheet" href="<?php echo $template_folder; ?>/js/progressbar/ui.progress-bar.css">

    <!-- accordion -->
    <link rel="stylesheet" href="<?php echo $template_folder; ?>/js/accordion/accordion.css" type="text/css" media="all">
    
    <!-- Lightbox -->
    <link rel="stylesheet" type="text/css" href="<?php echo $template_folder; ?>/js/lightbox/jquery.fancybox.css" media="screen" />

    <!-- forms -->
    <link rel="stylesheet" href="<?php echo $template_folder; ?>/js/form/sky-forms.css" type="text/css" media="all">
    
    <!-- REVOLUTION BANNER CSS SETTINGS -->
    <link rel="stylesheet" type="text/css" href="<?php echo $template_folder; ?>/js/rs-plugin/style.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?php echo $template_folder; ?>/js/rs-plugin/settings.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?php echo $template_folder; ?>/js/rs-plugin/style-added.css" media="screen" />

    <?php if($preset !== 'default' && $overrideColor == '0') :?>
    <link rel="stylesheet" href="<?php echo $template_folder; ?>/css/colors/<?php echo $preset;?>.css" />
    <?php endif;?>

    <?php if($overrideColor == '1') : ?>
    <link rel="stylesheet/less" type="text/css" media="screen" href="<?php echo $template_folder; ?>/css/colors/color.php?bc=<?php echo $bC;?>">
    <script type="text/javascript" src="<?php echo $template_folder; ?>/js/less.js"></script>
    
    <?php endif;?>

    <?php if($layoutsite === 'boxed') :?>
    <link rel="stylesheet" href="<?php echo $template_folder; ?>/css/bg-patterns/pattern-<?php echo $pattern;?>.css" />
    <?php endif;?>