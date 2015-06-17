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
	include_once ('database_conn.php');
	include_once ('init_month_year.php');
	include_once ('function_lib.php');
	include_once ('combo_script.php');
	include_once ('language/lang_en.php');
	include_once ('language/lang_fr.php');
	include_once ('language/lang_switcher.php');
?>

<div class="page-title">
<h3 class="no-margin"><?php echo $TEXT['Funding Requirements Source']; ?></h3></br>
</div>

<br />

<div class="row" id="filter-panel">
    <div class="panel panel-default">
    	<div class="panel-body">
            <table id="nav-country">
        		<tbody>
        		  <tr>      
                        <div class="clearfix">
                            <td>
                                <?php echo $TEXT['Product Group']; ?>:&nbsp;&nbsp;
                            </td>
                            <td width="200px">
                                <select class="form-control" id="item-group">
                                <?php echo user_all_test();?>
                                </select>
                    		</td>
                            <td>
                               &nbsp;&nbsp;&nbsp;&nbsp;
                            </td>
                             <td>
                                <?php echo $TEXT['Service Type']; ?>:&nbsp;&nbsp;
                            </td>
                            <td width="150px">
                                <select class="form-control" id="ServiceTypeId1">
                               <?php echo user_all_test();?>
                                </select>
                    		</td>
                        </div>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row" id="list-panel">
    <div class="panel panel-default table-responsive">
        <div class="panel-heading">
            <?php echo $TEXT['Funding Requirements List']; ?>
            <span class="pull-right">
                <button class="btn btn-info" type="button" id="PrintBTN" onclick="printfunction()" ><?php echo $TEXT['Print']; ?>  </button>
                <button class="btn btn-info" type="button" id="PrintBTN1" onclick="printfunction1()" ><?php echo $TEXT['Excel']; ?>  </button>
            </span>
        </div>	
        <div class="padding-md clearfix">
        	<table class="table table-striped display" id="fundingReqTable">
        		<thead>
        			<tr>
   			            <th><?php echo $TEXT['Funding Requirements Id']; ?></th>
						<th style="text-align: center;">SL#</th>
						<th><?php echo $TEXT['Funding Requirements Name']; ?></th>
						<th><?php echo $TEXT['Funding Requirements Name (French)']; ?></th>
        			</tr>
        		</thead>
        		<tbody></tbody>
        	</table>
        </div>
    </div>    
</div>

<!--<div class="row" id="form-panel">
    <div class="panel panel-default">
        <div class="panel-heading">
            <?php echo $TEXT['Formulation Form']; ?>    
        </div>
	    <div class="panel-body">
            <form novalidate="" data-validate="parsley" id="funding_form" class="form-horizontal form-border no-margin">	
            
                <div class="form-group">
					<label class="control-label col-lg-3"><?php echo $TEXT['Product Group']; ?></label>
					<div class="col-lg-4">
                        <select class="form-control chzn-select" name="ItemGroupId" id="ItemGroupId" data-required="true" >
                        <option value="" selected="true"><?php echo $TEXT['Product Group']; ?></option>
                        </select>                   						
					</div>
				</div>
                
                <div class="form-group">
					<label class="control-label col-lg-3"><?php echo $TEXT['Service Type']; ?></label>
					<div class="col-lg-4">
                        <select class="form-control chzn-select" name="ServiceTypeId" id="ServiceTypeId" data-required="true" >
                        <option value="" selected="true"><?php echo $TEXT['Service Type']; ?></option>
                        </select>                   						
					</div>
				</div>
			              						
				<div class="form-group">
					<label class="control-label col-lg-3"><?php echo $TEXT['Funding Requirements Name']; ?></label>
					<div class="col-lg-4">
                        <input class="form-control input-sm parsley-validated" type="text" name="FundingReqSourceName" id="FundingReqSourceName" data-required="true" placeholder="input here..."/>							
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-lg-3"><?php echo $TEXT['Funding Requirements Name (French)']; ?></label>
					<div class="col-lg-4">
                        <input class="form-control input-sm parsley-validated" type="text" name="FundingReqSourceNameFrench" id="FundingReqSourceNameFrench" data-required="true" placeholder="input here..."/>							
					</div>
				</div>
			             
				<div class="form-group">				
					<input type="text" style="display:none;" value="insertUpdateFundingReqData" id="action" name="action"/>
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
</div>-->

<style>
	.SL, .Action {
		text-align: center !important;
	}
    .panel-heading {
        padding: 10px 10px 23px 15px !important;
    }
</style>

<script>
function printfunction(){
	var currentSearch = $('#formulationTable_filter').find('input').val();
	window.open("<?php echo $baseUrl; ?>report/t_formulation_print.php?jBaseUrl=<?php echo $jBaseUrl; ?>&lan=<?php echo $lan;?>&ItemGroupId="+$('#item-group').val()
	+"&ItemGroupName="+$('#item-group option[value='+$('#item-group').val()+']').text()
	+"&sSearch="+currentSearch);		 
} 		
function printfunction1(){
	var currentSearch = $('#formulationTable_filter').find('input').val();
	window.open("<?php echo $baseUrl; ?>report/t_formulation_excel.php?jBaseUrl=<?php echo $jBaseUrl; ?>&lan=<?php echo $lan;?>&ItemGroupId="+$('#item-group').val()
	+"&ItemGroupName="+$('#item-group option[value='+$('#item-group').val()+']').text()
	+"&sSearch="+currentSearch);	
} 		
</script>

<link href="<?php echo $baseUrl; ?>css/custom.css" rel="stylesheet"/>
<link href="<?php echo $jBaseUrl; ?>/templates/protostar/endless/css/jquery.dataTables_themeroller.css" rel="stylesheet"/>
<link href="<?php echo $jBaseUrl; ?>/templates/protostar/endless/css/endless.min.css" rel="stylesheet"/>

<script src='<?php echo $jBaseUrl; ?>/templates/protostar/endless/js/parsley.min.js'></script>
<script src='<?php echo $jBaseUrl; ?>/templates/protostar/endless/js/jquery.dataTables.min.js'></script>
<script src='<?php echo $baseUrl; ?>t_funding_requirements_view.js'></script>

