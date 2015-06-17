<?php 

defined('_JEXEC') or die;

?>

<div class="container">

	<div class="content_left">

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
	<!-- end content-left -->

	<!-- right sidebar starts -->
	<div class="right_sidebar">
	
		<?php if($this->countModules('right-sidebar')) :?>
			<jdoc:include type="modules" name="right-sidebar"  style="sidebarwidgetmargintop" />
		<?php endif;?>
		<?php if($this->countModules('position-7')) :?>
			<jdoc:include type="modules" name="position-7" style="well" />
		<?php endif;?>

	</div>
	<!-- end right-sidebar -->

</div>

<!-- end container -->