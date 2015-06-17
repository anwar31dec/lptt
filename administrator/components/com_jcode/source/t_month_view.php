<?php 
$user = JFactory::getUser();
$baseUrl = JURI::base() . 'administrator/components/com_jcode/source/';
$jBaseUrl = JURI::base();
?>

<script> var baseUrl = '<?php echo $baseUrl; ?>'; </script>

<?php 
	//include_once ('combo_script.php');
	include_once ('language/lang_en.php');
	include_once ('language/lang_fr.php');
	include_once ('language/lang_switcher.php');
?>

<div class="page-title">
<!--	<a class="btn btn-warning btn-small pull-right btn-list" role="button" href="javascript:void(0);" onClick="onListPanel()" style="margin-left:4px;"><i class="fa fa-chevron-left"></i>Back to List</a>
	
	<a class="btn btn-primary btn-small pull-right btn-form fa" role="button" href="javascript:void(0);" onClick="onFormPanel()" style="margin-left:4px;">Add Record</a>-->
	<h3 class="no-margin"><?php echo $TEXT['Month']; ?></h3>
	<!--<span><?php echo $TEXT['Welcome to WARP EWS Dashboard']; ?></span></br>
    <button class="btn btn-info" type="button" id="PrintBTN" onclick="printfunction()">Print</button>&nbsp;
    <button class="btn btn-info" type="button" id="PrintBTN" onclick="printfunction1()">Print</button>-->
</div>

<br />

<div class="row" id="list-panel">
	<div class="panel panel-default table-responsive">
		<div class="clearfix list-panel" >
			<table class="table table-striped" id="monthTable">
				<thead>
					<tr>
						<th><?php echo $TEXT['Month Id']; ?></th>
						<th style="text-align: center;">SL#</th>
						<th><?php echo $TEXT['Month Name']; ?></th>
						
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>

	<!--	<div class="clearfix form-panel" >
        
			<form novalidate="" data-validate="parsley" id="t_month" class="form-horizontal form-border no-margin">	
            							
				<div class="form-group">
					<label class="control-label col-lg-3">Month Name</label>
					<div class="col-lg-9">
                        <input class="form-control input-sm parsley-validated" type="text"  name="MonthName" id="MonthName" placeholder="input here..."/>							
					</div>
				</div>
			               
				<div class="form-group">				
					<input type="text" style="display:none;" value="insertUpdateMonthData" id="action" name="action"/>
					<input type="text" style="display:none;" id="RecordId" name="RecordId"/>
					<label class="col-lg-2 control-label"></label>
					<div class="col-lg-10">
						<a href="javascript:void(0);" class="btn btn-success btn-form-success">Submit</a>
						<a href="javascript:void(0);" class="btn btn-default" onClick="onListPanel()">Cancel</a>
					</div>
				</div>
                
			</form>           
		</div>   -->   
	</div> 
</div>

<style>
	.SL, .Action {
		text-align: center !important;
	}
</style>

<script>
function printfunction(){
	var baseUrl = '<?php echo $jBaseUrl; ?>'; 
	window.open("http://softworks02/warp/administrator/components/com_jcode/source/report/printProcessing.php?baseUrl="+baseUrl+"&action=getMonthData");			 
} 
</script>

<script>
function printfunction(){
	var baseUrl = '<?php echo $jBaseUrl; ?>'; 
	window.open("http://softworks02/warp/administrator/components/com_jcode/source/report/printProcessing.php?baseUrl="+baseUrl+"&action=getMonthData");			 
} 
</script>

<link href="<?php echo $baseUrl; ?>css/custom.css" rel="stylesheet"/>
<link href="<?php echo $jBaseUrl; ?>/templates/protostar/endless/css/jquery.dataTables_themeroller.css" rel="stylesheet"/>
<link href="<?php echo $jBaseUrl; ?>/templates/protostar/endless/css/endless.min.css" rel="stylesheet"/>

<script src='<?php echo $jBaseUrl; ?>/templates/protostar/endless/js/parsley.min.js'></script>
<script src='<?php echo $jBaseUrl; ?>/templates/protostar/endless/js/jquery.dataTables.min.js'></script>
<script src='<?php echo $baseUrl; ?>t_month_view.js'></script>

