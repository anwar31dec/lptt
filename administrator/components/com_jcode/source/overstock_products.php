<?php
$user = JFactory::getUser();
$baseUrl = JURI::base() . 'administrator/components/com_jcode/source/';
$jBaseUrl = JURI::base();
?>
<script>var baseUrl = '<?php echo $baseUrl; ?>';</script>

<div class="page-title">
	<h3 class="no-margin">Overstock Products</h3>
	<span>Overstock Products by country</span>
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
								<td>Select Country:&nbsp;</td><td valign="middle" align="left">
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
					<table class="table table-striped" id="tbl-overstock-products">
						<thead>
							<tr>		
								<th>Id</th>	
								<th>Product</th>									
								<th>Month of stock</th>									
								<th>Recommeded action</th>							
								<th>Date action needed</th>
								<th>Comments</th>																						
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
<link href="<?php echo $baseUrl; ?>/overstock_products.css" rel="stylesheet">
<script src='<?php echo $baseUrl; ?>/overstock_products.js'></script>

