<?php
$user = JFactory::getUser();
$baseUrl = JURI::base() . 'administrator/components/com_jcode/source/';
$jBaseUrl = JURI::base();
$lang = JFactory::getLanguage();
$lan = $lang->getTag();
?>

<script>
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
			<div class="row columns">				
				<div class="col-md-4  col-padding">					
					<div class="tbl-header1" id="itemTable_length1">
						<label><?php echo $TEXT['Select Country']; ?>
							<select class="form-control" name="AllCountryId" id="AllCountryId">
								<option value=""><?php echo $TEXT['All']; ?></option>
							</select>
		                </label>
					</div>
				</div>

				<div class="col-md-8  col-padding">
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
                <table  id="rptFrequencyTable" class="table table-striped table-bordered display table-hover" cellspacing="0">
                    <thead>
                        <tr>
							<th>RepFreq Id</th>
							<th style="text-align: center;">SL.</th>
							<th><?php echo $TEXT['Country Name']; ?></th>
							<th><?php echo $TEXT['Product Group']; ?></th>
							<th><?php echo $TEXT['Frequency Name']; ?></th>
							<th><?php echo $TEXT['Start Year']; ?></th>
							<th><?php echo $TEXT['Start Month']; ?></th>
							<th style="text-align: center;"><?php echo $TEXT['Action']; ?></th>
							<th>CountryId</th>
							<th>ItemGroupId</th>
							<th>FrequencyId</th>						
							<th>StartMonthId</th>
							<th>StartYearId</th> 
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>			                   
            </div>
            
            
            <div id="form-panel">
		        <div class="panel-heading">
		            <?php echo $TEXT['Reporting Frequency Form']; ?>           
		        </div>
		        <div class="panel-body">
		            <form novalidate="" data-validate="parsley" id="region_form" class="form-horizontal form-border no-margin">
		
		                <div class="form-group">
		                    <label class="control-label col-md-4" for="CountryId"><?php echo $TEXT['Country']; ?>*</label>
		                    <div class="col-md-8">
								<select class="form-control" name="CountryId" id="CountryId" data-required="true">
									<option value=""><?php echo $TEXT['Select Country']; ?></option>
								</select> 
								
		                    </div>
		                </div>
		
		                <div class="form-group">
		                    <label class="control-label col-md-4" for="ItemGroupId"><?php echo $TEXT['Product Group']; ?>*</label>
		                    <div class="col-md-8">
								<select class="form-control" name="ItemGroupId" id="ItemGroupId" data-required="true">
									<option value=""><?php echo $TEXT['Select Product Group']; ?></option>
								</select>								
		                    </div>
		                </div>
		
		                <div class="form-group">
		                    <label class="control-label col-md-4" for="FrequencyId"><?php echo $TEXT['Frequency Name']; ?>*</label>
		                    <div class="col-md-8">
								<select class="form-control" name="FrequencyId" id="FrequencyId" data-required="true">
									<option value=""><?php echo $TEXT['Select Frequency']; ?></option>
								</select>
		                    </div>
		                </div>
		                <div class="form-group">
		                    <label class="control-label col-md-4" for="StartYearId"><?php echo $TEXT['Start Year']; ?>*</label>
		                    <div class="col-md-8">
								<select class="form-control" name="StartYearId" id="StartYearId" data-required="true">
									<option value=""><?php echo $TEXT['Select Year']; ?></option>
								</select>
		                    </div>
		                </div>
		
		                <div class="form-group">
		                    <label class="control-label col-md-4" for="StartYearId"><?php echo $TEXT['Start Month']; ?>*</label>
		                    <div class="col-md-8">
								<select class="form-control chzn-select" name="StartMonthId" id="StartMonthId" data-required="true">
									<option value=""><?php echo $TEXT['Select Month']; ?></option>
								</select>  
		                    </div>
		                </div>
						
		                <div class="form-group">
		                	<div class="col-md-4">
			                    <input type="text" value="insertUpdateFrequencyData" id="action" name="action" style="display: none;"/>
			                    <input type="text" style="display:none;" id="RecordId" name="RecordId"/>
			                    <input type="hiidden" style="display:none;" id="userId" value="<?php echo $user->name; ?>"/>
			                    <input type="hiidden" style="display:none;" id="en-GBId" value="<?php echo $lan; ?>"/>
		                    </div>
		                    <div class="col-md-8">		                    	
								<a href="javascript:void(0);" class="btn btn-success btn-form-success" id="submitItemList"><?php echo $TEXT['Submit']; ?></a>
								<a href="javascript:void(0);" class="btn btn-default" onClick="onListPanel()"><?php echo $TEXT['Cancel']; ?></a>			                    
		                    </div>
		                </div>
		            </form>    
		        </div>      
		    </div>
	</div>
</div>

 
 <script>
        
function print_function(type) {
        var dataTableId = 'rptFrequencyTable';
		var reportSaveName = 'Reporting Frequency'; //Not allow any type of special character of cahrtName
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

        var reportHeaderName = TEXT['Reporting Frequency List'];
        reportHeaderList[0] = reportHeaderName;
        reportHeaderList[1] = 'Country: ' + $('#AllCountryId option[value='+$('#AllCountryId').val()+']').text();
	
		sqlParameterList[0]= (($('#AllCountryId').val() == '') ? 0 : $('#AllCountryId').val());

        //sqlParameterList[0]= (($('#item-group').val() == '') ? 0 : $('#item-group').val());	
        //groupBySqlIndex = 7;
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
        reportSaveName = reportSaveName+'_'+today;


        if (type == 'print') {
            window.open("<?php echo $baseUrl; ?>report/print_master.php?action=getrptFrequencyData"
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
            window.open("<?php echo $baseUrl; ?>report/excel_master.php?action=getrptFrequencyData"
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
                    action: 'getrptFrequencyData',
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

<style>
	#region_form select, #region_form input, #colorpicker3 input-group{
		max-width: 300px;
	}
	#colorpicker3 {
		max-width: 300px;
	}	
	.input-group-addon:last-child {
		border-left: 0 none;
	}
	
</style>

<link href="<?php echo $baseUrl; ?>media/datatable-bootstrap/dataTables.bootstrap.css" rel="stylesheet"/>
<link href="<?php echo $baseUrl; ?>css/main.css" rel="stylesheet"/>
<link href="<?php echo $baseUrl; ?>css/jquery.dataTables.custom.css" rel="stylesheet"/>
<script src='<?php echo $baseUrl; ?>media/datatable/js/jquery.dataTables.min.js'></script>
<script src='<?php echo $baseUrl; ?>media/datatable-bootstrap/dataTables.bootstrap.min.js'></script>
<script src='<?php echo $baseUrl; ?>t_reportingfrequency.js'></script>
<script src='<?php echo $baseUrl; ?>js/plugins/bootstrap-colorpicker/bootstrap-colorpicker.js'></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_en.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_fr.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_switcher.js"></script>