<?php
$user = JFactory::getUser();
$baseUrl = JURI::base() . 'administrator/components/com_jcode/source/';
$jBaseUrl = JURI::base();
?>

<?php 
	include_once ('database_conn.php');
	include_once ('init_month_year.php');
	include_once ('function_lib.php');
	include_once ('combo_script.php');
	include_once ('language/lang_en.php');
	include_once ('language/lang_fr.php');
	include_once ('language/lang_switcher.php');
?>

<script>var baseUrl = '<?php echo $baseUrl; ?>';</script>

<div class="page-title">
	<h3 class="no-margin"><?php echo $TEXT['Patient Ratio']; ?></h3><br />
</div>


<div class="row">
	<div class="col-md-4 col-sm-12 col-sx-12">
		<div class="panel panel-default">
			<div class="panel-body">				
					<div class="center-block" id="month-year-block" >
						<table id="month-year">
							<tbody>
								<tr>
									<td width="" valign="middle" align="right">
									<button class="btn btn-info" type="button" id="left-arrow">
										<span class="glyphicon icon-arrow-left"> </span>
									</button></td>
									<td>&nbsp;&nbsp;</td><td valign="middle" align="left">
									<select class="form-control" id="month-list">										
									</select></td>
									<td>&nbsp;&nbsp;</td><td valign="middle" align="left">
									<select class="form-control" id="year-list">										
									</select></td>
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
	<div class="col-md-8 col-sm-12 col-sx-12">
		<div class="panel panel-default">
			<div class="panel-body">
				<table id="month-year1" class="pull-right">
					<tbody>
						<tr>
							<td><?php echo $TEXT['Country']; ?>:&nbsp;</td><td width="120px" valign="middle" align="left">
							<select class="form-control" id="country-list">								
							</select></td>																		
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<!-- <div class="row">
	<div class="col-md-12 col-sm-12 col-sx-12">
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="row">
					<div class="col-md-6 col-sm-12 col-sx-12">						
						<table id="nav-year">
							<tbody>
								<tr>
									<td width="" valign="middle" align="right">
									<button class="btn btn-info" type="button" id="left-arrow">
										<span class="glyphicon icon-arrow-left"> </span>
									</button></td>
									<td>&nbsp;&nbsp;</td><td valign="middle" align="left"><select class="form-control" id="year-list"></select></td>
									<td>&nbsp;&nbsp;</td><td width="" valign="middle" align="left">
									<button class="btn btn-info" type="button" id="right-arrow">
										<span class="glyphicon icon-arrow-right"></span>
									</button></td>
								</tr>
							</tbody>
						</table>						
					</div>
					<div class="col-md-6 col-sm-12 col-sx-12">
						<div class="pull-right">
						    <table id="month-year1" class="pull-right">
								<tbody>
									<tr>
            							<td><?php echo $TEXT['Country']; ?>:&nbsp;</td><td valign="middle" align="left">
            							<select class="form-control" id="country-list">
          								 <?php echo user_all_test();?>
            							</select></td>
            						</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div> -->

<div class="row">
	<div class="col-md-12 col-sm-12 col-sx-12">
		<div class="panel panel-default">
		
			<div class="panel-body">
				<div class="row">
					<div class="col-md-12 col-sm-12 col-sx-12">
						 <div id="art-patient-ratio"></div>  
					</div>					
				</div>
			</div>
			<div class="panel-footer"></div>
		</div>
	</div>
</div>

<!-- <div class="row">
	<div class="col-md-12 col-sm-12 col-sx-12">
		<div class="panel panel-default">
			<div class="panel-heading clearfix">
				Patient Trend Time Series Data List
			</div>
			<div class="panel-body">
				<div class="clearfix list-panel" >
					<table class="table table-striped" id="tbl-patient-trend-time-series">
						<thead>
							<tr>
								<th>SL</th>
								<th>Patient Type</th>
								<th>Jan'13</th>
								<th>Feb'13</th>
								<th>Mar'13</th>
								<th>Apr'13</th>
								<th>May'13</th>
								<th>Jun'13</th>
								<th>Jul'13</th>
								<th>Aug'13</th>
								<th>Sep'13</th>
								<th>Oct'13</th>
								<th>Nov'13</th>
								<th>Dec'13</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
			<div class="panel-footer"></div>
		</div>
	</div>
</div> -->




<link href="<?php echo $baseUrl; ?>/css/custom.css" rel="stylesheet">
<link href="<?php echo $jBaseUrl; ?>/templates/protostar/endless/css/jquery.dataTables_themeroller.css" rel="stylesheet">
<link href="<?php echo $jBaseUrl; ?>/templates/protostar/endless/css/endless.min.css" rel="stylesheet">
<script src='<?php echo $jBaseUrl; ?>/templates/protostar/endless/js/jquery.dataTables.min.js'></script>
<script src='<?php echo $baseUrl; ?>dashboard/js/d3.v3.min.js'></script>
<link href="<?php echo $baseUrl; ?>/art_patient_ratio.css" rel="stylesheet">
<script src='<?php echo $baseUrl; ?>/art_patient_ratio.js'></script>
<link href="<?php echo $baseUrl; ?>dashboard/css/morris.css" rel="stylesheet">
<script src='<?php echo $baseUrl; ?>dashboard/js/morris.min.js'></script>
<script src='<?php echo $baseUrl; ?>/lib/fnc-lib.js'></script>


