<?php 

defined('_JEXEC') or die;


?>

<!doctype html>
<!--[if IE 7 ]>    <html lang="<?php echo $this->language;?>" class="isie ie7 oldie no-js"> <![endif]-->
<!--[if IE 8 ]>    <html lang="<?php echo $this->language;?>" class="isie ie8 oldie no-js"> <![endif]-->
<!--[if IE 9 ]>    <html lang="<?php echo $this->language;?>" class="isie ie9 no-js"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="<?php echo $this->language;?>" class="no-js"> <!--<![endif]-->
<head>
	<meta charset="<?php echo $this->_charset;?>">

	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	
	
	<!-- this styles only adds some repairs on idevices  -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<jdoc:include type="head" />

	<!-- Standard Favicon--> 
	<link rel="shortcut icon" href="<?php echo JURI::base(true). (!empty($favicon)? '/'.$favicon : '/images/favicon/favicon.ico');?>">
	
	<!-- joomla default style -->
	<link rel="stylesheet" href="<?php echo $template_folder; ?>/css/jldefault-style.css">

	<!-- Google fonts - witch you want to use - (rest you can just remove) -->
   	<link href='http://fonts.googleapis.com/css?family=<?php echo $bodyfont;?>' rel='stylesheet' type='text/css'>
   	<link href='http://fonts.googleapis.com/css?family=<?php echo $headingfont;?>' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=<?php echo $highlightfont;?>' rel='stylesheet' type='text/css'>
    
    <!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
    
    <?php require_once dirname(__FILE__).'/header/profile/'.$templateprofile.'.php'; ?>

    <!-- Custom Style -->
    <link rel="stylesheet" type="text/css" href="<?php echo $template_folder; ?>/css/custom.css" media="all" />
	
	<?php if(!empty($customStyleLinks)) {

		foreach ($customStyleLinks as $link) {
			echo '<link rel="stylesheet" type="text/css" href="'.$link.'" media="screen" />';
		}

	} ?>

	<script type="text/javascript" >
		baseUrl = '<?php echo JURI::root(true);?>';
		siteName = '<?php echo $sitename;?>';
		templateName = '<?php echo $this->template;?>';
	</script>
</head>

<body>
<?php if($layoutsite === 'boxed') :?>
<div class="wrapper_boxed">
<?php endif;?>

	<div class="site_wrapper">

	<?php require_once dirname(__FILE__).'/header/'.$templateprofile.'.php'; ?>
