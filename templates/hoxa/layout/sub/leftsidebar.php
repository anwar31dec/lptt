<?php 

defined('_JEXEC') or die;

?>

<div class="container">

	<!-- left sidebar starts -->
	<div class="left_sidebar">
	
		<?php if($this->countModules('left-sidebar')) :?>
			<jdoc:include type="modules" name="left-sidebar"  style="sidebarwidgetmargintop" />
		<?php endif;?>
		<?php if($this->countModules('position-8')) :?>
			<jdoc:include type="modules" name="position-8" style="xhtml" />
		<?php endif;?>

	</div>
	<!-- end left-sidebar -->

	<div class="content_right">

		<?php if ($this->countModules('position-1')) : ?>
	  		<jdoc:include type="modules" name="position-1" style="none" />
		<?php endif;?>
		<?php if ($this->countModules('position-2')) : ?>
	  		<jdoc:include type="modules" name="position-2" style="none" />
		<?php endif;?>

		<jdoc:include type="modules" name="position-3" style="xhtml" />
		<jdoc:include type="message" />
		<jdoc:include type="component" />

	</div>
	<!-- end content-right -->
</div>

<!-- end container -->