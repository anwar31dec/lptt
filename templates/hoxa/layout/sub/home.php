<?php 

defined('_JEXEC') or die;

?>


<?php if ($this->countModules('position-1')) : ?>
		<jdoc:include type="modules" name="position-1" style="none" />
<?php endif;?>
<?php if ($this->countModules('position-2')) : ?>
		<jdoc:include type="modules" name="position-2" style="none" />
<?php endif;?>

<jdoc:include type="modules" name="position-3" style="xhtml" />
<jdoc:include type="message" />
<jdoc:include type="component" />
