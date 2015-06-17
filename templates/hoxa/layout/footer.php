<?php 

defined('_JEXEC') or die;


?>

<?php require_once dirname(__FILE__).'/footer/'.$templateprofile.'.php'; ?>

	
<!-- Template style js links -->
<?php if(!empty($customScriptLinks)) {

	foreach ($customScriptLinks as $link) {
		echo '<script type="text/javascript" src="'.$link.'"></script>';
	}

} ?>

<!-- Template Custom Script -->
<script type="text/javascript" src="<?php echo $template_folder; ?>/js/custom.js"></script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>		
</body>
</html>

