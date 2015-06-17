<?php 
header("Content-type: text/css; charset=utf-8");

$bC = '#'.htmlspecialchars($_GET['bc']);

?>
@base-color: <?php echo $bC;?>;

.logo {
	background-color: @base-color;
}

.nav-collapse a:active,
.nav-collapse .active a {
    color: @base-color;
}

.section1 .circle {
	color: @base-color;
}
.section1 .circle:hover {
    background-color: @base-color;
}

.section2 {
	background: @base-color url(../../../../images/para-bg1.png) repeat-y center -65px;
}

.section3 strong {
	color: @base-color;
}
.section3 .one_fourth:hover, .section3 .one_fourth.active {
	background-color: @base-color;
}

.peosec strong {
    color: @base-color;
}

.section4 .packagesinfo ul.active li.mostp {
    background-color: @base-color;
}
.section4 .packagesinfo ul.active li.title h2 {
    color: @base-color;
}
.section4 .packagesinfo ul.active li.title h4 {
    color: @base-color;
}

.section4 .packagesinfo ul.active li.planbut a {
    background-color: @base-color;
}

.parallax_sec3 i {
	background: @base-color url(../../../../images/site-img5.png) no-repeat center top;
}

.punchtext h3 {
	color: @base-color;
}
.punchtext a {
	background-color: @base-color;
}

.sky-form .button {
    background-color: @base-color;
}

a {
	color: @base-color;
}

.tp-caption.finewide_verysmall_white_mw div i {color: @base-color;}
.tp-caption.finewide_medium_white em {background: @base-color;}