<!-- Logout confirmation -->
<?php	
	$jBaseUrl=JURI::base();
?>
	<div class="custom-popup width-100" id="logoutConfirm" style="display:none;">
		<div class="padding-md">
			<h4 class="m-top-none"> Do you want to logout?</h4>
		</div>

		<div class="text-center">
			<a class="btn btn-success m-right-sm" href="<?php echo $jBaseUrl;?>index.php/log-out">Logout</a>
			<a class="btn btn-danger logoutConfirm_close">Cancel</a>
		</div>
	</div>  