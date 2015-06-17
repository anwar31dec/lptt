<?php
/**
 * @package Hoxa - Responsive Multipurpose Joomla Template
 * @author Cththemes - www.cththemes.com
 * @date: 01-10-2014
 *
 * @copyright  Copyright ( C ) 2014 cththemes.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
/*
 * Module chrome for rendering the module in a submenu
 */
function modChrome_mega($module, &$params, &$attribs)
{
	if ($module->content)
	{
		// echo "< class=\"well " . htmlspecialchars($params->get('moduleclass_sfx')) . "\">";
		// if ($module->showtitle)
		// {
		// 	echo "<h3 class=\"page-header\">" . $module->title . "</h3>";
		// }
		echo "<li><p>" . $module->title . "</p></li>";
		echo $module->content;
		//echo "</div>";
	}
}


function modChrome_footercolumns($module, &$params, &$attribs)
{
	$colclass = '';
	switch ($attribs['columns']) {
		case '1':
			$colclass = 'one_full';
			break;
		case '2':
			$colclass = 'one_half';
			break;
		case '3':
			$colclass = 'one_third';
			break;
		case '4':
			$colclass = 'one_fourth';
			break;
		case '6':
			$colclass = 'one_fifth';
			break;
		default:
			$colclass = 'one_fourth';
			break;
	}
	$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

	if ($module->content)
	{
		echo "<div class=\"" .$colclass. " animate\" data-anim-type=\"fadeInUp\">";
			if(!empty($moduleclass_sfx)){
				echo '<div class="'.$moduleclass_sfx.'">';
			}
			//echo "<div class=\"widget footer-widget ". htmlspecialchars($params->get('moduleclass_sfx')) ."\">";
			if ($module->showtitle)
			{
				echo "<h4 class=\"lmb\">" . $module->title . "</h4>";
			}
				echo $module->content;

			if(!empty($moduleclass_sfx)){
				echo '</div>';
			}
			//echo "</div>";						
		echo "</div>";
	}
}

function modChrome_footertwo($module, &$params, &$attribs)
{
	$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

	if ($module->content)
	{
		echo "<div class=\"one_half animate\" data-anim-type=\"fadeInLeft\">";
			if(!empty($moduleclass_sfx)){
				echo '<div class="'.$moduleclass_sfx.'">';
			}

			if ($module->showtitle)
			{
				echo "<h4 class=\"lmb\">" . $module->title . "</h4>";
			}
				echo $module->content;

			if(!empty($moduleclass_sfx)){
				echo '</div>';
			}						
		echo "</div>";
	}
}

function modChrome_footertwolast($module, &$params, &$attribs)
{
	$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

	if ($module->content)
	{
		echo "<div class=\"one_half last animate\" data-anim-type=\"fadeInRight\">";
			if(!empty($moduleclass_sfx)){
				echo '<div class="'.$moduleclass_sfx.'">';
			}

			if ($module->showtitle)
			{
				echo "<h4 class=\"lmb\">" . $module->title . "</h4>";
			}
				echo $module->content;

			if(!empty($moduleclass_sfx)){
				echo '</div>';
			}						
		echo "</div>";
	}
}

function modChrome_footerfour($module, &$params, &$attribs)
{
	$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

	if ($module->content)
	{
		echo "<div class=\"one_fourth animate\" data-anim-type=\"fadeInUp\">";
			if(!empty($moduleclass_sfx)){
				echo '<div class="'.$moduleclass_sfx.'">';
			}

			if ($module->showtitle)
			{
				echo "<h4 class=\"lmb\">" . $module->title . "</h4>";
			}
				echo $module->content;

			if(!empty($moduleclass_sfx)){
				echo '</div>';
			}						
		echo "</div>";
	}
}



function modChrome_footerfourlast($module, &$params, &$attribs)
{
	$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

	if ($module->content)
	{
		echo "<div class=\"one_fourth last animate\" data-anim-type=\"fadeInUp\">";
			if(!empty($moduleclass_sfx)){
				echo '<div class="'.$moduleclass_sfx.'">';
			}

			if ($module->showtitle)
			{
				echo "<h4 class=\"lmb\">" . $module->title . "</h4>";
			}
				echo $module->content;

			if(!empty($moduleclass_sfx)){
				echo '</div>';
			}						
		echo "</div>";
	}
}

function modChrome_topintro($module, &$params, &$attribs)
{
	if ($module->content)
	{
		echo "<div class=\"top_intro " . htmlspecialchars($params->get('moduleclass_sfx')) . "\">";
		if ($module->showtitle)
		{
			echo "<h5 class=\"white\">" . $module->title . "</h5>";
		}
		echo $module->content;
		echo "</div>";
	}
}

function modChrome_container($module,&$params, &$attribs){
	if($module->content){
		echo "<div class=\"cth-container " . htmlspecialchars($params->get('moduleclass_sfx')) . "\">";
			echo "<div class=\"container\">";
				if ($module->showtitle)
				{
					echo "<h5>" . $module->title . "</h5>";
				}
				echo $module->content;
			echo "</div>";
		echo "</div>";
	}
}

function modChrome_sidebarhr($module,&$params, &$attribs){
	if($module->content){
		echo "<div class=\"cth-sidebar " . htmlspecialchars($params->get('moduleclass_sfx')) . "\">";
		if ($module->showtitle)
		{
			echo "<" . htmlspecialchars($params->get('header_tag')) . " class=\"sidebar-heading ". htmlspecialchars($params->get('header_class')) . "\">" . $module->title . "</" . htmlspecialchars($params->get('header_tag')) . ">";
		}
		echo $module->content;
		echo "</div>";
		echo "<hr>";
	}
}

function modChrome_sidebar($module,&$params, &$attribs){
	if($module->content){
		echo "<div class=\"cth-sidebar " . htmlspecialchars($params->get('moduleclass_sfx')) . "\">";
		if ($module->showtitle)
		{
			echo "<" . htmlspecialchars($params->get('header_tag')) . " class=\"sidebar-heading ". htmlspecialchars($params->get('header_class')) . "\">" . $module->title . "</" . htmlspecialchars($params->get('header_tag')) . ">";
		}
		echo $module->content;
		echo "</div>";
	}
}

function modChrome_shortcodes($module,&$params, &$attribs){
	if($module->content){
		echo'<div class="container '. htmlspecialchars($params->get('moduleclass_sfx')) .'">';
		if ($module->showtitle)
		{
			echo'<div class="elements-heading">';
				echo'<h3>'.$module->title.'</h3>';
				echo'<hr style="margin: 10px 0 20px;">';
			echo'</div>';
		}
		echo $module->content;
		echo "</div>";
	}
}

function modChrome_well($module, &$params, &$attribs)
{
	if ($module->content)
	{
		echo "<div class=\"well " . htmlspecialchars($params->get('moduleclass_sfx')) . "\">";
		if ($module->showtitle)
		{
			echo "<h3 class=\"page-header\">" . $module->title . "</h3>";
		}
		echo $module->content;
		echo "</div>";
	}
}


function modChrome_sidebarwidget($module, &$params, &$attribs)
{
	if ($module->content)
	{
		echo "<div class=\"sidebar_widget\">";
		if ($module->showtitle)
		{
			echo "<div class=\"sidebar_title\"><h4>" . preg_replace('/--([^-]*)--/', '<i>$1</i>', $module->title) . "</h4></div>";
		}
		echo $module->content;
		echo "</div>";
	}
}

function modChrome_sidebarwidgetmargintop($module, &$params, &$attribs)
{
	if ($module->content)
	{
		echo "<div class=\"clearfix margin_top4\"></div>";
		echo "<div class=\"sidebar_widget\">";
		if ($module->showtitle)
		{
			echo "<div class=\"sidebar_title\"><h4>" . preg_replace('/--([^-]*)--/', '<i>$1</i>', $module->title) . "</h4></div>";
		}
		echo $module->content;
		echo "</div>";
	}
}




function modChrome_widget($module, &$params, &$attribs)
{
	if ($module->content)
	{
		echo "<div class=\"widget " . htmlspecialchars($params->get('moduleclass_sfx')) . "\">";
		if ($module->showtitle)
		{
			echo "<h4>" . $module->title . "</h4>";
		}
		echo $module->content;
		echo "</div>";
	}
}

function modChrome_section($module, &$params, &$attribs){
	if($module->content){
		$cthmoduleid_sfx = $params->get('cthmoduleid_sfx');
		$cthmoduleclass_sfx = htmlspecialchars($params->get('cthmoduleclass_sfx'));
		echo '<section '.(!empty($cthmoduleid_sfx)? ' id="'.$cthmoduleid_sfx.'"' : '').' '.(!empty($cthmoduleclass_sfx)? ' class="'.$cthmoduleclass_sfx.'"' : '').'>';

			if ($module->showtitle)
			{
				echo '<!-- Title -->';
				echo '<div class="row title '.htmlspecialchars($params->get('header_class')).'">';
					echo '<h2>'. $module->title .'</h2>';
					echo '<hr>';
				echo '</div>';

			}

			echo $module->content;

		echo '</section>';

	}
}

function modChrome_footer($module, &$params, &$attribs)
{
	if ($module->content)
	{
		echo "<footer class=\"cth-footer " . htmlspecialchars($params->get('moduleclass_sfx')) . "\">";
		if ($module->showtitle)
		{
			echo "<h3>" . $module->title . "</h3>";
		}
		echo $module->content;
		echo "</footer>";
	}
}

function modChrome_promo($module, &$params, &$attribs)
{
	if ($module->content)
	{
		echo'<!-- Hidden promo control -->';
		echo'<div class="promo-control"><a>'.$module->title.'</a></div>';


		echo'<!-- Hidden promo -->';
		echo'<section class="footer-promo">';
			echo'<div class="row">';
				echo'<div class="eight col center">';
					echo $module->content;
				echo'</div>';
			echo'</div>';
		echo'</section>';

	}
}

?>
