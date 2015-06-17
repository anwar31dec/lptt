<?php
$user = JFactory::getUser();
$baseUrl = JURI::base() . 'administrator/components/com_jcode/source/';
$jBaseUrl = JURI::base();
$lang = JFactory::getLanguage();
$lan = $lang->getTag();
?>

<script type="text/javascript">
    var baseUrl = '<?php echo $baseUrl; ?>';
    var lan = '<?php echo $lan; ?>';
</script>

<?php
include_once ('database_conn.php');
include_once ('function_lib.php');
include_once ('init_month_year.php');
include_once ('combo_script.php');
include_once ('language/lang_en.php');
include_once ('language/lang_fr.php');
include_once ('language/lang_switcher.php');
?>

<script type="text/javascript">
    var vLang = '<?php echo $vLang; ?>';
</script>

<div class="row"> 
	<div class="col-md-12">
		<div class="nav-data">
			<div class="row col-padding">				
				<div class="col-md-6">					
					<div class="tbl-header1" id="regimen-filter">
						<select class="form-control" id="country-list"  style="display: none;"></select>
						<label><?php echo $TEXT['Product Group']; ?>
							<!-- <select class="form-control" id="item-group">
		                        <option selected="" value=""><?php //echo $TEXT['All']; ?></option>
		                    </select> -->
		                    <select class="form-control" name="ItemGroupId" id="ItemGroupId"></select>
		                </label>
					</div>
				</div>
				<div class="col-md-6 col-padding">
					<div class="tbl-header1 pull-right">
						<label>					
							<a data-mce-href="#" class="btn-list but_back" href="javascript:void(0);" onClick="onListPanel()"><i data-mce-bootstrap="1" class="fa fa-reply fa-lg">&nbsp;</i> <?php echo $TEXT['Back to List']; ?></a>					
							<a data-mce-href="#" class="btn-form but_add" href="javascript:void(0);" onClick="onFormPanel()"><i data-mce-bootstrap="1" class="fa fa-plus fa-lg">&nbsp;</i> <?php echo $TEXT['Add Record']; ?></a>
							<a id="PrintBTN" data-mce-href="#" class="but_print" href="javascript:void(0);" onclick="print_function('print')"><i data-mce-bootstrap="1" class="fa fa-print fa-lg">&nbsp;</i> <?php echo $TEXT['Print']; ?></a>
							<a id="PrintBTN1" data-mce-href="#" class="but_excel" href="javascript:void(0);" onclick="print_function('excel')"><i data-mce-bootstrap="1" class="fa fa-file-excel-o fa-lg">&nbsp;</i> <?php echo $TEXT['Excel']; ?></a>
						</label>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row"> 
	<div class="col-md-12">
		<div id="list-panel">                	
            <table  id="RegimenTable" class="table table-striped table-bordered display table-hover" cellspacing="0">
                <thead>
                    <tr>
                        <th><?php echo $TEXT['RegimenId']; ?></th>
                        <th style="text-align: left;">SL.</th>
                        <th><?php echo $TEXT['Regimen Name']; ?></th>
                        <th style="text-align: center;"><?php echo $TEXT['Action']; ?></th>
                        <th><?php echo $TEXT['Formulation Name']; ?></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>		                   
        </div>            
            
        <div id="form-panel">
	        <div class="panel-heading">
	             <?php echo $TEXT['Regimen Entry Form']; ?> 
	        </div>
	        <div class="panel-body">
	            <form novalidate="" data-validate="parsley" id="Regimen_form" class="form-horizontal form-border no-margin">
	            			
	                <div class="form-group">
	                    <label class="control-label col-md-4" for="FormulationId"><?php echo $TEXT['Formulation']; ?>*</label>
	                    <div class="col-md-8">
	                        <select class="form-control parsley-validated" name="FormulationId" id="FormulationId" data-required="true" >
	                            <option value=""><?php echo $TEXT['Formulation Type']; ?></option>
	                        </select>
	                    </div>
	                </div>
	
	                <div class="form-group">
	                    <label class="control-label col-md-4" for="RegimenName"><?php echo $TEXT['Regimen Name']; ?>*</label>
	                    <div class="col-md-8">
	                        <select class="form-control parsley-validated" name="regimenMaster-list" id="regimenMaster-list" data-required="true" >
	                            <option value=""><?php echo $TEXT['Regimen Name']; ?></option>
	                        </select>
                		</div>
	                </div>
	
	                <div class="form-group">
	                    <label class="control-label col-md-4" for="GenderTypeId"><?php echo $TEXT['Gender Type']; ?>*</label>
	                    <div class="col-md-8">
	                        <select class="form-control parsley-validated" id="GenderTypeId" name="GenderTypeId" data-required="true">
	                            <option value="" ><?php echo $TEXT['Select Gender Type']; ?></option>
	                        </select>                 						
	                    </div>
	                </div>
	                		
	                <div class="form-group">
	                	<div class="col-md-4">
		                     <input type="hidden" value="insertUpdateRegimenData" id="action" name="action" />
		                    <input type="hidden" id="RegimenId" name="RegimenId" />
		                    <input type="text" style="display:none;" id="RecordId" name="RecordId"/>
		                    <input type="hiidden" style="display:none;" id="userId" value="<?php echo $user->name; ?>"/>
		                    <input type="hiidden" style="display:none;" id="en-GBId" value="<?php echo $lan; ?>"/>
	                    </div>
	                    <div class="col-md-8">		                    	
		                    <a href="javascript:void(0);" class="btn btn-success btn-form-success"><?php echo $TEXT['Submit']; ?></a>
                    		<a href="javascript:void(0);" class="btn btn-default" onClick="onListPanel()"><?php echo $TEXT['Cancel']; ?></a>			                    
	                    </div>
	                </div>
	            </form>    
	        </div>      
	    </div>
	</div>
</div>   

<div class="modal fade" id="RegimentListModal" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">       
            <div class="modal-header">
                <button type="button" class="close closeModal" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?php echo $TEXT['Select Regimen Item']; ?></h4>
            </div>
            <div class="modal-body">
                <div class="row pull-right">
                    <label class="control-label" id="combin"><?php echo $TEXT['Combination']; ?></label>
                    <button class="btn btn-primary" id="combtn"></button>    				
                </div>

                <div class="clearfix"></div>
                <br/>

                <div class="row">
                    <select class="select-box pull-left form-control" id="selectedBox1" multiple="multiple"></select>

                    <div class="select-box-option">
                        <a id="btnRemove" class="btn btn-sm btn-default">
                            <i class="fa fa-angle-left"></i>
                        </a>
                        <a id="btnSelect" class="btn btn-sm btn-default">
                            <i class="fa fa-angle-right"></i>
                        </a>
                        <div class="seperator"></div>
                        <a id="btnRemoveAll" class="btn btn-sm btn-default">
                            <i class="fa fa-angle-double-left"></i>
                        </a>
                        <a id="btnSelectAll" class="btn btn-sm btn-default">
                            <i class="fa fa-angle-double-right"></i>
                        </a>
                    </div>

                    <select class="select-box pull-right form-control" id="selectedBox2" multiple="multiple"></select>
                </div>
            </div>
            <div class="modal-footer">   				
                <a href="javascript:void(0);" class="btn btn-success" onclick="saveRegimenItem()"><?php echo $TEXT['Submit']; ?></a>
                <a href="javascript:void(0);" class="btn btn-danger closeModal" data-dismiss="modal"><?php echo $TEXT['Cancel']; ?></a> 				
            </div>                          
        </div>
    </div>
</div>

<div class="modal fade" id="CombinationModal" style="display: none">
    <div class="modal-dialog">
        <div class="modal-content">       
            <div class="modal-header">
                <button type="button" class="close closeModal" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?php echo $TEXT['Combination% Entry']; ?></h4>
            </div>
            <div class="modal-body">                           
                <center>
                    <table class="table table-striped display" id="CombinationTable">
                        <thead>
                            <tr style="display: none;">
                                <th style="display: none;"></th>
                                <th style="display: none;"></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </center>
            </div>
            <div class="modal-footer"> 
                <a href="#">Total <span class="badge badge-info" id="comTotal"></span></a>  				
                <a href="javascript:void(0);" class="btn btn-success" onclick="saveCombinationItem()"><?php echo $TEXT['Submit']; ?></a>
                <a href="javascript:void(0);" class="btn btn-danger closeModal" data-dismiss="modal"><?php echo $TEXT['Cancel']; ?></a> 				
            </div>                          
        </div>
    </div>
</div>

<style type="text/css">
    .SL, .Action {
        text-align: left !important;
    }
    .percentage, .datacell{
        text-align: right !important;
    }
    .display > tbody > tr > td{
        vertical-align: middle !important;
    }
    .display > tbody > tr > td:nth-of-type(1){
        padding-right: 160px !important;
    }   
    table tbody tr.even.row_selected td, table tbody tr.odd.row_selected td {
        background-color: #9AD268;
        color: #fff;
    }
    #RegimenTable tbody tr {
        cursor: pointer;
    }
    #combtn{
        padding: 12px;
    }
    div.modal{
        background-color:inherit !important;
        margin-left: 0 !important;
        width:auto !important;
        border: none !important;
        right: auto !important;
        bottom: auto !important;
        overflow: hidden !important;
    }
    #RegimentListModal {
        left: 18% !important;
    }
    #CombinationModal {
        left: 28% !important;
    } 
    #RegimentListModal .modal-dialog {
        width: 930px;
    }
    #RegimentListModal .modal-body {
        overflow-y: hidden !important;
        padding: 30px !important;
    }
    #CombinationModal .modal-body {
        overflow-y: hidden !important;
        padding: 10px 10px 10px 62px !important;
    }
    
    #Regimen_form select, #Regimen_form input {
	  max-width: 300px;
	}

</style>

<script>
    String.prototype.replaceAll = function(token, newToken, ignoreCase) {
        var _token;
        var str = this + "";
        var i = -1;
        if (typeof token === "string") {

            if (ignoreCase) {

                _token = token.toLowerCase();
                while ((
                        i = str.toLowerCase().indexOf(
                        token, i >= 0 ? i + newToken.length : 0
                        )) !== -1
                        ) {
                    str = str.substring(0, i) +
                            newToken +
                            str.substring(i + token.length);
                }

            } else {
                return this.split(token).join(newToken);
            }

        }
        return str;
    };


    function print_function(type) {
        var dataTableId = 'RegimenTable';
        var currentSearch = $('#' + dataTableId + '_filter').find('input').val();
        var reportHeaderList = new Array();
        var tableHeaderList = new Array();
        var tableHeaderWidth = new Array();
        var dataType = new Array();
        var sqlParameterList = new Array();
        var groupBySqlIndex = -1;
        var colorCodeIndex = Array();
        var checkBoxIndex = new Array();
        var columns = $('#' + dataTableId).dataTable().dataTableSettings[0].aoColumns;
        $.each(columns, function(i, v) {
            if (v.bVisible) {
                tableHeaderList.push(v.sTitle);
                tableHeaderWidth.push(v.sWidth);
                dataType.push(v.sType);
            }
        });
        var reportHeaderName = TEXT['Case Types List'];
        reportHeaderList[0] = reportHeaderName;
        reportHeaderList[1] = TEXT['Product Group'] + ': ' + $('#ItemGroupId option[value=' + $('#ItemGroupId').val() + ']').text();
        sqlParameterList[0] = (($('#ItemGroupId').val() == '') ? 0 : $('#ItemGroupId').val());
        groupBySqlIndex = 2;
        //colorCodeIndex[0] = 5;
        //checkBoxIndex[0] = 4;
        //checkBoxIndex[1] = 6;

        reportHeaderList = JSON.stringify(reportHeaderList);
        dataType = JSON.stringify(dataType);
        tableHeaderList = JSON.stringify(tableHeaderList);
        tableHeaderWidth = JSON.stringify(tableHeaderWidth);
        sqlParameterList = JSON.stringify(sqlParameterList);
        colorCodeIndex = JSON.stringify(colorCodeIndex);
        checkBoxIndex = JSON.stringify(checkBoxIndex);
        //Get current date time
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1; //January is 0!
        var yyyy = today.getFullYear();
        var hh = today.getHours();
        var min = today.getMinutes();
        var sec = today.getSeconds();
        today = dd + '_' + mm + '_' + yyyy + '_' + hh + '_' + min + '_' + sec;
        var reportSaveName = reportHeaderName + '_' + today; // reportHeaderList[0].str.replace(/ /g, '_')+'_'+today;


        if (type == 'print') {
            window.open("<?php echo $baseUrl; ?>report/print_master.php?action=getRegimenData"
                    + "&jBaseUrl=<?php echo $jBaseUrl; ?>"
                    + "&lan=<?php echo $lan; ?>"
                    + "&reportSaveName=" + reportSaveName
                    + "&sSearch=" + currentSearch
                    + "&reportHeaderList=" + reportHeaderList
                    + "&tableHeaderList=" + tableHeaderList
                    + "&tableHeaderWidth=" + tableHeaderWidth
                    + "&useSl=" + false
                    + "&dataType=" + dataType
                    + "&sqlParameterList=" + sqlParameterList
                    + "&reportType=" + type
                    + "&groupBySqlIndex=" + groupBySqlIndex
                    + "&colorCodeIndex=" + colorCodeIndex
                    + "&checkBoxIndex=" + checkBoxIndex);
        }
        else if (type == 'excel') {
            window.open("<?php echo $baseUrl; ?>report/excel_master.php?action=getRegimenData"
                    + "&jBaseUrl=<?php echo $jBaseUrl; ?>"
                    + "&lan=<?php echo $lan; ?>"
                    + "&reportSaveName=" + reportSaveName
                    + "&sSearch=" + currentSearch
                    + "&reportHeaderList=" + reportHeaderList
                    + "&tableHeaderList=" + tableHeaderList
                    + "&tableHeaderWidth=" + tableHeaderWidth
                    + "&useSl=" + false
                    + "&dataType=" + dataType
                    + "&sqlParameterList=" + sqlParameterList
                    + "&reportType=" + type
                    + "&groupBySqlIndex=" + groupBySqlIndex
                    + "&colorCodeIndex=" + colorCodeIndex
                    + "&checkBoxIndex=" + checkBoxIndex);
        }
        else if (type == 'pdf') {
            $.ajax({
                url: baseUrl + 'report/pdf_master.php',
                type: 'post',
                data: {
                    action: 'getRegimenData',
                    jBaseUrl: "<?php echo $jBaseUrl; ?>",
                    lan: lan,
                    reportSaveName: reportSaveName,
                    sSearch: currentSearch,
                    reportHeaderList: reportHeaderList,
                    tableHeaderList: tableHeaderList,
                    tableHeaderWidth: tableHeaderWidth,
                    useSl: false,
                    dataType: dataType,
                    sqlParameterList: sqlParameterList,
                    reportType: type,
                    groupBySqlIndex: groupBySqlIndex,
                    colorCodeIndex: colorCodeIndex,
                    checkBoxIndex: checkBoxIndex
                },
                success: function(response) {
                    if (response == 'Processing Error') {
                        alert('No Record Found.');
                    } else {
                        window.open(baseUrl + 'report/pdfslice/' + response);
                    }
                }
            });
        }

    }

</script> 

<link href="<?php echo $baseUrl; ?>media/datatable-bootstrap/dataTables.bootstrap.css" rel="stylesheet"/>
<link href="<?php echo $baseUrl; ?>css/jquery.dataTables.custom.css" rel="stylesheet"/>
<script src='<?php echo $baseUrl; ?>media/datatable/js/jquery.dataTables.min.js'></script>
<script src='<?php echo $baseUrl; ?>media/datatable-bootstrap/dataTables.bootstrap.min.js'></script>

<script src='<?php echo $baseUrl; ?>t_regimen.js'></script>

<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_en.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_fr.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_switcher.js"></script>