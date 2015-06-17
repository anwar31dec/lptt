<?php
$user = JFactory::getUser();
$baseUrl = JURI::base() . 'administrator/components/com_jcode/source/';
$jBaseUrl = JURI::base();
?>
<script>var baseUrl = '<?php echo $baseUrl; ?>';</script>

<?php 
	include_once ('language/lang_en.php');
	include_once ('language/lang_fr.php');
	include_once ('language/lang_switcher.php');
?>

<div class="page-title">	
	<h3 class="no-margin"><?php echo $TEXT['Number of patients by product']; ?></h3>
	<span><?php echo $TEXT['Number of patient by patients and by country']; ?></span>
</div>

<div class="row">	
	<div class="col-md-12 col-sm-12 col-sx-12">
		<div id="cparams-panel" class="panel panel-default">	
			<div id="cparams-header" class="panel-heading clearfix">
				<!-- <h3 class="panel-title">Service Indicators</h3> -->
				<div class="pull-right" id="item-list">
					<table id="month-year">
						<tbody>
							<tr>
								<td><?php echo $TEXT['Select Country']; ?>:&nbsp;</td><td valign="middle" align="left">
								<select id="country-list" class="form-control">
									<option value="1">Benin</option>
									<option value="2">Burkina Faso</option>
									<option value="3">Cameroon</option>
									<option value="4">Guinea</option>
									<option value="5">Niger</option>
									<option value="6">Togo</option>
								</select></td>								
							</tr>
						</tbody>
					</table>
				</div>
			</div>
	
			<div class="panel-body">		
				<div class="clearfix list-panel" >
					<table class="table table-striped" id="tbl-patient-by-product">
						<thead>
							<tr>		
								<th>Id</th>							
								<th><?php echo $TEXT['Product']; ?>(eg.ARV name, RTK)</th>								
								<th><?php echo $TEXT['Number of patient/clients using this product']; ?></th>
								<th><?php echo $TEXT['Number of new patients/clients using this product (this month)']; ?></th>
								<th><?php echo $TEXT['Number of new patients/clients using this product (this quarter)']; ?></th>
								<th><?php echo $TEXT['Stock of product on hand at health facility']; ?></th>
								<th><?php echo $TEXT['Month of stock on hand']; ?></th>
								<th><?php echo $TEXT['Stock of product on ordered']; ?></th>
								<th><?php echo $TEXT['MOS of stock on hand and stock ordered']; ?></th>
								<th><?php echo $TEXT['Projected month of stock out']; ?></th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>				    
			</div>
		</div>
	</div>
</div>

<link href="<?php echo $jBaseUrl; ?>/templates/protostar/endless/css/jquery.dataTables_themeroller.css" rel="stylesheet">
<link href="<?php echo $jBaseUrl; ?>/templates/protostar/endless/css/endless.min.css" rel="stylesheet">
<script src='<?php echo $jBaseUrl; ?>/templates/protostar/endless/js/jquery.dataTables.min.js'></script>
<link href="<?php echo $baseUrl; ?>/css/custom.css" rel="stylesheet">
<link href="<?php echo $baseUrl; ?>/patient_by_product.css" rel="stylesheet">
<script src='<?php echo $baseUrl; ?>/patient_by_product.js'></script>

