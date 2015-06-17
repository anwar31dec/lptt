<?php
$user = JFactory::getUser();
$baseUrl = JURI::base() . 'administrator/components/com_jcode/source/';
$jBaseUrl = JURI::base();
$lang = JFactory::getLanguage();
$lan = $lang->getTag();
?>
<script>
	var baseUrl = '<?php echo $baseUrl; ?>';
	var lan='<?php echo $lan;?>';
</script>

<?php 
	// include_once ('database_conn.php');
	// include_once ('init_month_year.php');
	// include_once ('function_lib.php');
	// include_once ('combo_script.php');
	include_once ('language/lang_en.php');
	include_once ('language/lang_fr.php');
	include_once ('language/lang_switcher.php');
?>

<div class="page-title">	
	<h3 class="no-margin"><?php echo $TEXT['Frequency of stockout reports by product'];?></h3>
</div>

<br />

<div class="row">	
	<div class="col-md-12 col-sm-12 col-sx-12">
		<div id="cparams-panel" class="panel panel-default">	
			<div id="cparams-header" class="panel-heading clearfix">
				<div class="pull-right" id="item-list">
					<table id="month-year">
						<tbody>
							<tr>
								<td><?php echo $TEXT['Select Country'];?>:&nbsp;</td><td valign="middle" align="left">
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
					<table class="table table-striped" id="tbl-frequency-stockout-products">
						<thead>
							<tr>		
								<th>Id</th>	
								<th><?php echo $TEXT['Product'];?></th>									
								<th><?php echo $TEXT['Stockout report at facility level (this month)'];?></th>			
								<th><?php echo $TEXT['Stockout report at facility level (previous three months)'];?></th>									
								<th><?php echo $TEXT['Stockout report at facility level (past nine months - excluding the past 3 months)'];?></th>							
								<th><?php echo $TEXT['Frequency of stock outs reported'];?></th>
								<th><?php echo $TEXT['Risk Category'];?></th>																						
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
<link href="<?php echo $baseUrl; ?>/frequency_stockout_products.css" rel="stylesheet">
<script src='<?php echo $baseUrl; ?>/frequency_stockout_products.js'></script>

