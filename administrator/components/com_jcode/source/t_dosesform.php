<?php 
$user = JFactory::getUser();
$baseUrl = JURI::base() . 'administrator/components/com_jcode/source/';
$jBaseUrl = JURI::base();
?>

<script> var baseUrl = '<?php echo $baseUrl; ?>'; </script>

<?php 
	include_once ('language/lang_en.php');
	include_once ('language/lang_fr.php');
	include_once ('language/lang_switcher.php');
?>

<div class="page-title">
	<a class="btn btn-warning btn-small pull-right btn-list" role="button" href="javascript:void(0);" onClick="onListPanel()" style="margin-left:4px;"><i class="fa fa-chevron-left"></i>&nbsp;<?php echo $TEXT['Back to List']; ?></a>
	<a class="btn btn-primary btn-small pull-right btn-form fa" role="button" href="javascript:void(0);" onClick="onFormPanel()" style="margin-left:4px;"><?php echo $TEXT['Add Record']; ?></a>
	<h3 class="no-margin"><?php echo $TEXT['Dosage Form List']; ?></h3></h3></br>
	<button class="btn btn-info" type="button" id="PrintBTN" onclick="printfunction()" > Print </button>&nbsp;
    <button class="btn btn-info" type="button" id="PrintBTN" onclick="printfunction1()" > Excel </button>
	<!--<span>Welcome to WARP EWS Dashboard</span>-->
</div>

<div class="panel panel-default">
	<div class="panel-body">

		<div class="clearfix list-panel" >
			<table class="table table-striped" id="DosesFormTable">
				<thead>
					<tr>
						<th><?php echo $TEXT['Dosage Id']; ?></h3></th>
						<th style="text-align: center;">SL#</th>
						<th><?php echo $TEXT['Dosage Form Name']; ?></h3></th>
						<th style="text-align: center;"><?php echo $TEXT['Action']; ?></h3></th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>

		<div class="clearfix form-panel" >
        
			<form novalidate="" data-validate="parsley" id="t_doses_form" class="form-horizontal form-border no-margin">	
            							
				<div class="form-group">
					<label class="control-label col-lg-3"><?php echo $TEXT['Dosage Form Name']; ?></h3></label>
					<div class="col-lg-9">
                        <input class="form-control input-sm parsley-validated" type="text" name="DosesName" id="DosesName" data-required="true" placeholder="input here..."/>							
					</div>
				</div>
			               
				<div class="form-group">				
					<input type="text" style="display:none;" value="insertUpdateDosesFormData" id="action" name="action"/>
					<input type="text" style="display:none;" id="RecordId" name="RecordId"/>
					<label class="col-lg-2 control-label"></label>
					<div class="col-lg-10">
						<a href="javascript:void(0);" class="btn btn-success btn-form-success"><?php echo $TEXT['Submit']; ?></a>
						<a href="javascript:void(0);" class="btn btn-default" onClick="onListPanel()"><?php echo $TEXT['Cancel']; ?></a>
					</div>
				</div>
                
			</form>           
		</div>      
	</div>
</div>

<style>
	.SL, .Action {
		text-align: center !important;
	}
</style>

<script>
function printfunction()
{
	var baseUrl = '<?php echo $jBaseUrl; ?>'; 
	window.open("http://softworks02/warp/administrator/components/com_jcode/source/report/printProcessing.php?baseUrl="+baseUrl+"&action=getDosesFormData");			 
 
} 
		
 </script>
 
 <script>
function printfunction1()
{
	var baseUrl = '<?php echo $jBaseUrl; ?>'; 
	window.open("http://softworks02/warp/administrator/components/com_jcode/source/report/ExcelProcessing.php?baseUrl="+baseUrl+"&action=getDosesFormData");			 
 
} 
		
 </script>

<link href="<?php echo $baseUrl; ?>css/custom.css" rel="stylesheet"/>
<link href="<?php echo $jBaseUrl; ?>/templates/protostar/endless/css/jquery.dataTables_themeroller.css" rel="stylesheet"/>
<link href="<?php echo $jBaseUrl; ?>/templates/protostar/endless/css/endless.min.css" rel="stylesheet"/>

<script src='<?php echo $jBaseUrl; ?>/templates/protostar/endless/js/parsley.min.js'></script>
<script src='<?php echo $jBaseUrl; ?>/templates/protostar/endless/js/jquery.dataTables.min.js'></script>
<script src='<?php echo $baseUrl; ?>t_dosesform.js'></script>

