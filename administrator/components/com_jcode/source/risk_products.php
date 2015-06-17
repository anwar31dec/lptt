<?php
$user = JFactory::getUser();
$baseUrl = JURI::base() . 'administrator/components/com_jcode/source/';
$jBaseUrl = JURI::base();
?>

<?php
include_once ('init_month_year.php');
?>

<script>var baseUrl = '<?php echo $baseUrl; ?>';</script>

<div class="page-title">
	<h3 class="no-margin">ARV at Very High Risk</h3>
	<span>Product details those are in risk of stock out</span>
</div>

<div class="row">
	<div class="col-md-8 col-sm-12 col-sx-12">
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="row">
					<div class="col-md-8  col-md-offset-3 col-sm-12 col-sx-12">
						<table id="month-year">
							<tbody>
								<tr>
									<td valign="middle" align="right">
									<button class="btn btn-info" type="button" id="left-arrow">
										<span class="glyphicon icon-arrow-left"> </span>
									</button></td>
									<td>&nbsp;&nbsp;</td><td valign="middle" align="left"><select class="form-control" id="month-list"></select></td>
									<td>&nbsp;&nbsp;</td><td valign="middle" align="left"><select class="form-control" id="year-list"></select></td>
									<td>&nbsp;&nbsp;</td><td width="" valign="middle" align="left">
									<button class="btn btn-info" type="button" id="right-arrow">
										<span class="glyphicon icon-arrow-right"></span>
									</button></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-4 col-sm-12 col-sx-12">
		<div class="panel panel-default">
			<div class="panel-body">
				<table id="month-year1" class="pull-right">
					<tbody>
						<tr>
							<td>Select Country:&nbsp;</td><td valign="middle" align="left">
							<select class="form-control" id="country-list">
								<option value="0" selected>All</option>
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
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-sm-12 col-sx-12">
		<div id="cparams-panel" class="panel panel-default">
			<div id="cparams-header" class="panel-heading clearfix">
				List of ARV at Very High Risk
			</div>
			<div class="panel-body">
				<div class="clearfix list-panel" >
					<table class="table table-striped" id="tbl-risk-products">
						<thead>
							<tr>
								<th>SL</th>
								<th>Product</th>
								<th>AMC</th>
								<th>Closing</th>
								<th>MOS</th>
								<th></th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<link href="<?php echo $baseUrl; ?>css/custom.css" rel="stylesheet"/>
<link href="<?php echo $jBaseUrl; ?>/templates/protostar/endless/css/jquery.dataTables_themeroller.css" rel="stylesheet">
<link href="<?php echo $jBaseUrl; ?>/templates/protostar/endless/css/endless.min.css" rel="stylesheet">
<script src='<?php echo $jBaseUrl; ?>/templates/protostar/endless/js/jquery.dataTables.min.js'></script>
<script src='<?php echo $baseUrl; ?>/lib/fnc-lib.js'></script>
<link href="<?php echo $baseUrl; ?>risk_products.css" rel="stylesheet">
<script src='<?php echo $baseUrl; ?>risk_products.js'></script>

