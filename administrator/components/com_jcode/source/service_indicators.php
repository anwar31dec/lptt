<?php
$user = JFactory::getUser();
$baseUrl = JURI::base() . 'administrator/components/com_jcode/source/';
$jBaseUrl = JURI::base();
$lang = JFactory::getLanguage();
$lan = $lang->getTag();
$userName = $user->username;
?>
<script>
	var baseUrl = '<?php echo $baseUrl; ?>';
	var lan='<?php echo $lan;?>';
	var userId='<?php echo $userName;?>';
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
<script type="text/javascript">
	var vLang = '<?php echo $vLang; ?>';
</script>

<div class="container">
	<div class="content_fullwidth lessmar">
		<div class="azp_col-md-12 one_full">
		
				
		<div class="row">
			<div class="col-md-12 col-sm-12 col-sx-12">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="row">							
							<div class="col-md-12 col-sm-12 col-sx-12">
								<div class="panel panel-default">
									<center>
										<table>
											<tbody>
												<tr>										
													<td><?php echo $TEXT['Country']; ?>&nbsp;:&nbsp;&nbsp;</td>
													<td><select class="form-control" id="country-list"></select></td>
													
													<td>&nbsp;&nbsp;</td>
													<td><?php echo $TEXT['Region']; ?>&nbsp;:&nbsp;&nbsp;</td>
													<td><select class="form-control" id="Region-list"><?php echo user_all_test();?></select></td>
													
													<td>&nbsp;&nbsp;</td>
													<td><?php echo $TEXT['District']; ?>&nbsp;:&nbsp;&nbsp;</td>
													<td><select class="form-control" id="District-list"></select></td>
													
													<td>&nbsp;&nbsp;</td>
													<td><?php echo $TEXT['Service Type']; ?>&nbsp;:&nbsp;&nbsp;</td>
													<td><select class="form-control" id="servicetype-list"></select></td>									
												</tr>
											</tbody>
										</table>
									</center>
								</div>
							</div>						
						</div>
						<div class="clearfix"><br></div>
						<div class="row">							
							<div class="col-md-12 col-sm-12 col-sx-12">
								<div class="panel panel-default">
									<center>
										<table>
											<tbody>
												<tr>												
													<td valign="middle" align="right">
													<button class="btn btn-info" type="button" id="left-arrow"><span class="fa fa-arrow-left fntC"> </span></button></td>
													<td>&nbsp;&nbsp;</td><td valign="middle" align="left"><select class="form-control" id="month-list"></select></td>
													<td>&nbsp;&nbsp;</td><td valign="middle" align="left"><select class="form-control" id="year-list"></select></td>
													<td>&nbsp;&nbsp;</td><td width="" valign="middle" align="left">
													<button class="btn btn-info" type="button" id="right-arrow"><span class="fa fa-arrow-right fntC"></span></button></td>
													<td>&nbsp;&nbsp;</td>
													<td><?php echo $TEXT['Total Patient']; ?>&nbsp;:&nbsp;&nbsp;</td>
													<td><span class="badge badge-success" id="totalPatient"></span></td>
												</tr>											
											</tbody>
										</table>
									</center>
								</div>
							</div>						
						</div>	
					</div>
				</div>
			</div>
		</div>

		<div class="margin_top1"><br></div>
		<div class="clearfix"><br></div>			
	

		
		<div class="row">
			<div class="col-md-12 col-sm-12 col-sx-12">
				<div class="panel panel-default">
					<div class="panel-heading clearfix">
						<?php echo $TEXT['Facility Service indicators Data List']; ?>
						<span class="pull-right">
							<label>					
								<a id="PrintBTN" data-mce-href="#" class="but_print" href="javascript:void(0);" onclick="print_function('print')"><i data-mce-bootstrap="1" class="fa fa-print fa-lg">&nbsp;</i> <?php echo $TEXT['Print']; ?></a>
								<a id="PrintBTN1" data-mce-href="#" class="but_excel" href="javascript:void(0);" onclick="print_function('excel')"><i data-mce-bootstrap="1" class="fa fa-file-excel-o fa-lg">&nbsp;</i> <?php echo $TEXT['Excel']; ?></a>
								<a id="PDFBTN" data-mce-href="#" class="but_pdf" href="javascript:void(0);" onclick="print_function('pdf')"><i data-mce-bootstrap="1" class="fa fa-file-pdf-o fa-lg">&nbsp;</i> <?php echo $TEXT['PDF']; ?></a>
							</label>
						</span>
					</div>
					<div class="panel-body">
						<div class="clearfix list-panel" id="tbl-pf">
							<table class="table table-striped table-bordered display table-hover" cellspacing="0" id="tbl-service-indicators">
								<thead>
									<tr>	
										<th>SL</th>									
										<th><?php echo $TEXT['Name of Facility']; ?></th>
										<th><?php echo $TEXT['(0-4 Years)']; ?></th>
										<th><?php echo $TEXT['(5-14 Years)']; ?></th>
										<th><?php echo $TEXT['(15+ Years)']; ?></th>
										<th><?php echo $TEXT['Pregnant Women']; ?></th>										
										<th><?php echo $TEXT['Number of Total Patients']; ?></th>										
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div> 
		
		</div>		
	</div>
</div>

<style>
.panel-title {	
    display: inline;
    line-height: 28px;
}
.SL{
    text-align: center !important;
}
.TotalPatient, .NewPatient, .ProjectedValue{
    text-align: right !important; 
}
 
</style>
<script>

function print_function(type){
	var dataTableId = 'tbl-service-indicators';
	var reportSaveName = 'Facility_Service_indicators'; //Not allow any type of special character of cahrtName
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
	
	var reportHeaderName = TEXT['Facility Service indicators']+'   '+ $('#country-list option[value='+$('#country-list').val()+']').text()+' on  '+$('#month-list option[value='+$('#month-list').val()+']').text()+',  '+$('#year-list option[value='+$('#year-list').val()+']').text();
	reportHeaderList[0] = reportHeaderName;
	reportHeaderList[1] = TEXT['Region']+ ': ' + $('#Region-list option[value='+$('#Region-list').val()+']').text()+'   '+
						  TEXT['District']+ ': ' + $('#District-list option[value='+$('#District-list').val()+']').text();
	reportHeaderList[2] = TEXT['Service Type']+ ': ' + $('#servicetype-list option[value='+$('#servicetype-list').val()+']').text();	
						  

	sqlParameterList[0]= (($('#country-list').val() == '') ? 0 : $('#country-list').val());	
	sqlParameterList[1]= (($('#Region-list').val() == '') ? 0 : $('#Region-list').val());	
	sqlParameterList[2]= (($('#District-list').val() == '') ? 0 : $('#District-list').val());	
	sqlParameterList[3]= (($('#servicetype-list').val() == '') ? 0 : $('#servicetype-list').val());	
	sqlParameterList[4]= (($('#month-list').val() == '') ? 0 : $('#month-list').val());	
	sqlParameterList[5]= (($('#year-list').val() == '') ? 0 : $('#year-list').val());	
	
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
	reportSaveName = reportSaveName+'_'+today;
	
	
  if(type == 'print'){	 
		window.open("<?php echo $baseUrl;?>report/print_master.php?action=getServiceIndicators"
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
		window.open("<?php echo $baseUrl;?>report/excel_master.php?action=getServiceIndicators"
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
				action: 'getServiceIndicators',
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




<script src="<?php echo $baseUrl; ?>lib/fnc-lib.js" type="text/javascript"></script>
<link href="<?php echo $baseUrl; ?>media/datatable-bootstrap/dataTables.bootstrap.css" rel="stylesheet"/>
<link href="<?php echo $baseUrl; ?>css/jquery.dataTables.custom.css" rel="stylesheet"/>
<script src='<?php echo $baseUrl; ?>media/datatable/js/jquery.dataTables.min.js'></script>
<script src='<?php echo $baseUrl; ?>media/highcharts/highcharts.js'></script>
<script src='<?php echo $baseUrl; ?>media/datatable-bootstrap/dataTables.bootstrap.min.js'></script>
<script src='<?php echo $baseUrl; ?>/service_indicators.js'></script>

<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_en.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_fr.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_switcher.js"></script>