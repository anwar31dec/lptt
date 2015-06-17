<?php 
$user = JFactory::getUser();
$baseUrl = JURI::base() . 'administrator/components/com_jcode/source/';
$jBaseUrl = JURI::base();
?>

<script> var baseUrl = '<?php echo $baseUrl; ?>'; </script>

<div class="page-title">
	<a class="btn btn-warning btn-small pull-right btn-list" role="button" href="javascript:void(0);" onClick="onListPanel()" style="margin-left:4px;"><i class="fa fa-chevron-left"></i>&nbsp;Back to List</a>
	<a class="btn btn-primary btn-small pull-right btn-form fa" role="button" href="javascript:void(0);" onClick="onFormPanel()" style="margin-left:4px;">Add Record</a>
	<h3 class="no-margin">Funding Source</h3>
	<span>Welcome to WARP EWS Dashboard</span>
</div>

<div class="panel panel-default">
	<div class="panel-body">

		<div class="clearfix list-panel" >
			<table class="table table-striped" id="FundingSourceTable">
				<thead>
					<tr>
						<th>FundingSourceId</th>
						<th style="text-align: center;">SL#</th>
						<th> Funding Source Name</th>
                        <th> Description</th>                       
						<th style="text-align: center;">Action</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>

		<div class="clearfix form-panel" >
        
			<form novalidate="" data-validate="parsley" id="t_Agency_form" class="form-horizontal form-border no-margin">	 
            
                <div class="form-group">
					<label class="control-label col-lg-3">Funding Source Name</label>
					<div class="col-lg-9">
                        <input class="form-control input-sm parsley-validated" type="text" name="FundingSourceName" id="FundingSourceName" data-required="true" placeholder="input here..."/>							
					</div>
				</div>                
               <!-- <div class="form-group">
					<label class="control-label col-lg-3">Agency Full Name</label>
					<div class="col-lg-9">
                        <input class="form-control input-sm parsley-validated" type="text" name="AgencyFullName" id="AgencyFullName"  data-required="true" placeholder="input here..."/>							
					</div>
				</div>-->
                <div class="form-group">
					<label class="control-label col-lg-3">Description</label>
					<div class="col-lg-9">
                    <textarea class="form-control" rows="3" name="FundingSourceDesc" id="FundingSourceDesc" placeholder="input here..."></textarea>
			     </div>
				</div>  
                         
				<div class="form-group">				
					<input type="text" style="display:none;" value="insertUpdateFundingSourceData" id="action" name="action"/>
					<input type="text" style="display:none;" id="RecordId" name="RecordId"/>
					<label class="col-lg-2 control-label"></label>
					<div class="col-lg-10">
						<a href="javascript:void(0);" class="btn btn-success btn-form-success">Submit</a>
						<a href="javascript:void(0);" class="btn btn-default" onClick="onListPanel()">Cancel</a>
					</div>
				</div>
                
			</form>           
		</div>      
	</div>
</div>

<style>
	.SL, .Action,.minMos,.maxMos {
		text-align: center !important;
	}
</style>

<link href="<?php echo $baseUrl; ?>css/custom.css" rel="stylesheet"/>
<link href="<?php echo $jBaseUrl; ?>/templates/protostar/endless/css/jquery.dataTables_themeroller.css" rel="stylesheet"/>
<link href="<?php echo $jBaseUrl; ?>/templates/protostar/endless/css/endless.min.css" rel="stylesheet"/>

<script src='<?php echo $jBaseUrl; ?>/templates/protostar/endless/js/parsley.min.js'></script>
<script src='<?php echo $jBaseUrl; ?>/templates/protostar/endless/js/jquery.dataTables.min.js'></script>
<script src='<?php echo $baseUrl; ?>t_funding_source.js'></script>

