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
				<div class="col-md-6"></div>
				<div class="col-md-6">
					<div class="tbl-header1 pull-right">
						<label>					
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
            <table  id="ServiceTable" class="table table-striped table-bordered display table-hover" cellspacing="0">
                <thead>
                    <tr>
						<th>Service Id</th>
						<th style="text-align: left;">SL.</th>
						<th><?php echo $TEXT['Service Type']; ?></th>
						<th><?php echo $TEXT['Service Type (French)']; ?></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>			                   
        </div>
	</div>
</div>

<script>
	function print_function(type){
		var dataTableId = 'ServiceTable';
		var reportSaveName = 'Service_Types'; //Not allow any type of special character of cahrtName
		var currentSearch = $('#'+dataTableId+'_filter').find('input').val();
		var reportHeaderList = new Array();
		var tableHeaderList = new Array();
		var tableHeaderWidth = new Array();
		var dataType = new Array();
		var sqlParameterList = new Array();
		var groupBySqlIndex = -1;
		var colorCodeIndex = Array();
		var checkBoxIndex = new Array();
		var columns = $('#'+dataTableId).dataTable().dataTableSettings[0].aoColumns;
		$.each(columns, function(i,v) {
			if(v.bVisible){
				tableHeaderList.push(v.sTitle);
				tableHeaderWidth.push(v.sWidth);
				dataType.push(v.sType);
			}
		});
		
		var reportHeaderName = TEXT['Service Types List'];
		reportHeaderList[0] = reportHeaderName;
		//reportHeaderList[1] = TEXT['Product Group']+ ': ' + $('#item-group option[value='+$('#item-group').val()+']').text();

		//sqlParameterList[0]= (($('#item-group').val() == '') ? 0 : $('#item-group').val());	
		//groupBySqlIndex = 5;
		//colorCodeIndex[0] = 5;
		//checkBoxIndex[0] = 3;
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
		var mm = today.getMonth()+1; //January is 0!
		var yyyy = today.getFullYear();
		var hh = today.getHours();
		var min = today.getMinutes();
		var sec = today.getSeconds();
		today = dd+'_'+mm+'_'+yyyy+'_'+hh+'_'+min+'_'+sec;
		 reportSaveName = reportSaveName + '_' + today;
		
		
	  if(type == 'print'){	 
			window.open("<?php echo $baseUrl;?>report/print_master.php?action=getServiceData"
			+"&jBaseUrl=<?php	echo $jBaseUrl; ?>"
			+"&lan=<?php echo $lan;?>"
			+"&reportSaveName="+reportSaveName 
			+"&sSearch="+currentSearch 
			+"&reportHeaderList="+reportHeaderList
			+"&tableHeaderList=" + tableHeaderList 
			+"&tableHeaderWidth=" + tableHeaderWidth
			+"&useSl=" + false
			+"&dataType=" + dataType
			+"&sqlParameterList=" + sqlParameterList
			+"&reportType=" + type
			+"&groupBySqlIndex="+ groupBySqlIndex
			+"&colorCodeIndex="+ colorCodeIndex
			+"&checkBoxIndex="+ checkBoxIndex);	 
	  }
	  else if(type == 'excel'){
			window.open("<?php echo $baseUrl;?>report/excel_master.php?action=getServiceData"
			+"&jBaseUrl=<?php	echo $jBaseUrl; ?>"
			+"&lan=<?php echo $lan;?>"
			+"&reportSaveName="+reportSaveName 
			+"&sSearch="+currentSearch 
			+"&reportHeaderList="+reportHeaderList
			+"&tableHeaderList=" + tableHeaderList 
			+"&tableHeaderWidth=" + tableHeaderWidth
			+"&useSl=" + false
			+"&dataType=" + dataType
			+"&sqlParameterList=" + sqlParameterList
			+"&reportType=" + type
			+"&groupBySqlIndex="+ groupBySqlIndex
			+"&colorCodeIndex="+ colorCodeIndex
			+"&checkBoxIndex="+ checkBoxIndex);	
		
		}
	  else if(type == 'pdf'){
			$.ajax({
				url: baseUrl + 'report/pdf_master.php',
				type: 'post',
				data: {
					action: 'getServiceData',
					jBaseUrl: "<?php echo $jBaseUrl; ?>",
					lan: lan,
					reportSaveName: reportSaveName,
					sSearch : currentSearch,	
					reportHeaderList: reportHeaderList,
					tableHeaderList: tableHeaderList,
					tableHeaderWidth: tableHeaderWidth,
					useSl: false, 
					dataType: dataType,
					sqlParameterList : sqlParameterList,
					reportType : type,
					groupBySqlIndex : groupBySqlIndex,
					colorCodeIndex : colorCodeIndex,
					checkBoxIndex : checkBoxIndex				
				},
				success: function(response) {
					if (response == 'Processing Error') {
						alert('No Record Found.');
					} else {
						window.open( baseUrl + 'report/pdfslice/' + response);
						
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

<script src='<?php echo $baseUrl; ?>t_servicetype_view.js'></script>

<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_en.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_fr.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_switcher.js"></script>
