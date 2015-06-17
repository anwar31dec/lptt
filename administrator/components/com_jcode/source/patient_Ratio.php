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
							<!--<div class="panel-heading clearfix">-->
									<div class="row">
										<div class="col-md-12 col-sm-12 col-sx-12">						
											<table id="month-year">
												<tbody>
													<tr>
													<td><?php echo $TEXT['Country']; ?>&nbsp;:&nbsp;&nbsp;</td>
														<td>
															<select class="form-control" id="country-list">
															<?php echo user_all_test();?>
															</select>
														</td>
														<td>&nbsp;&nbsp;</td>
														
														<td><?php echo $TEXT['Product Group']; ?>&nbsp;:&nbsp;&nbsp;</td>
														<td>
														<select class="form-control" id="item-group"></select>
														</td>
														<td>&nbsp;&nbsp;</td>													
														
														<td valign="middle" align="right">
														<button class="btn btn-info" type="button" id="left-arrow"><span class="fa fa-arrow-left fntC"> </span></button></td>
														<td>&nbsp;&nbsp;</td><td valign="middle" align="left"><select class="form-control" id="month-list"></select></td>
														<td>&nbsp;&nbsp;</td><td valign="middle" align="left"><select class="form-control" id="year-list"></select></td>
														<td>&nbsp;&nbsp;</td><td width="" valign="middle" align="left">
														<button class="btn btn-info" type="button" id="right-arrow"><span class="fa fa-arrow-right fntC"></span></button></td>
													</tr>
												</tbody>
											</table>						
										</div>
									</div>
								<!--</div>-->
						</div>
					</div>
				</div>
			</div>
			

			<div class="row">					
				<div class="col-md-12 col-sm-12 col-sx-12">
					<div class="panel panel-default">						
						<div class="panel-body" style="height: auto; overflow: hidden;">
							<span class="pull-right">
								  <label>					
									<a id="PrintBTN" data-mce-href="#" class="but_print" href="javascript:void(0);" onclick="print_function('print')"><i data-mce-bootstrap="1" class="fa fa-print fa-lg">&nbsp;</i> <?php echo $TEXT['Print']; ?></a>
									<a id="PrintBTN1" data-mce-href="#" class="but_excel" href="javascript:void(0);" onclick="print_function('excel')"><i data-mce-bootstrap="1" class="fa fa-file-excel-o fa-lg">&nbsp;</i> <?php echo $TEXT['Excel']; ?></a>
									<a id="PDFBTN" data-mce-href="#" class="but_pdf" href="javascript:void(0);" onclick="print_function('pdf')"><i data-mce-bootstrap="1" class="fa fa-file-pdf-o fa-lg">&nbsp;</i> <?php echo $TEXT['PDF']; ?></a>
								</label>								   
							</span>
						</div>						
					</div>
				</div>
			</div>
			
			
			<div class="row">	
					
				<div class="col-md-7 col-sm-12 col-sx-12">
					<div class="panel panel-default">
						<div id="cparams-header" class="panel-heading clearfix">
							<?php echo $TEXT['Patient Ratio Chart']; ?>
						</div>
						<div class="panel-body" style="height: auto; overflow: hidden;">
							<div class="clearfix list-panel" >
								<div id="wrap-line-chart">
									<div  id="patients-line-chart1" style="height: auto; overflow: hidden;"></div>
								</div>
							</div>
						</div>
						<div class="panel-footer"></div>
					</div>
				</div>

				<div class="col-md-5 col-sm-12 col-sx-12">
					<div class="panel panel-default">					
						<div class="panel-heading clearfix" style="height: 50px;">
							<label id="caseratiodata"></label>
							<!--
							<span class="pull-right">
								  <label>					
									<a id="PrintBTN" data-mce-href="#" class="but_print" href="javascript:void(0);" onclick="print_function('print')"><i data-mce-bootstrap="1" class="fa fa-print fa-lg">&nbsp;</i> <?php echo $TEXT['Print']; ?></a>
									<a id="PrintBTN1" data-mce-href="#" class="but_excel" href="javascript:void(0);" onclick="print_function('excel')"><i data-mce-bootstrap="1" class="fa fa-file-excel-o fa-lg">&nbsp;</i> <?php echo $TEXT['Excel']; ?></a>
									<a id="PDFBTN" data-mce-href="#" class="but_pdf" href="javascript:void(0);" onclick="print_function('pdf')"><i data-mce-bootstrap="1" class="fa fa-file-pdf-o fa-lg">&nbsp;</i> <?php echo $TEXT['PDF']; ?></a>
								</label>
								   
							</span>
							-->
						</div>
						
						<div class="panel-body">
							<div class="clearfix list-panel" id="tbl-pf">
								<table class="table table-striped table-bordered display table-hover" cellspacing="0" id="patientRatioTable">
									<thead>
									<tr>      
										<th>ID</th>						
										<th style="text-align: center;">Sl</th>   
										<th><?php echo $TEXT['Type']; ?></th>  
										<th style="text-align: right;"><?php echo $TEXT['Patients']; ?></th>
										<th style="text-align: right;"><?php echo $TEXT['Patient Percent']; ?></th>
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
	.SL{
		text-align: center !important;
	}
    .PatientTotal, #PatientTotal{
        text-align: right !important;
    }
</style>

<script>

function print_function(type){
	var tableId = 'patientRatioTable';
	var reportSaveName = 'Patient_Ratio'; //Not allow any type of special character of cahrtName
	var reportHeaderList = new Array();
	var dataAlignment = new Array();
	var cellWidth = new Array();
	var chart = 1;
	
	var reportHeaderName = TEXT['Case Ratio Report'];
	reportHeaderList[0] = reportHeaderName;
	reportHeaderList[1] = $('#country-list option[value='+$('#country-list').val()+']').text()+' - ' + $('#item-group option[value='+$('#item-group').val()+']').text() + ' - ' + $('#month-list option[value='+$('#month-list').val()+']').text()+', ' + $('#year-list option[value='+$('#year-list').val()+']').text();
	
	dataAlignment = ["center","left","right","right"];
	//when column count and width array count are not same then last value repeat
	cellWidth = ["10","50","15"];
	reportHeaderList = JSON.stringify(reportHeaderList);

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
	//alert(baseUrl);
	 $.ajax({
		    type: "POST",
			url: baseUrl + "report/chart_generate_svg.php",
			async: false,
			datatype: "json",
			cache: true,
			data: {
				baseUrl :baseUrl,
				svgName : reportSaveName,
				html: $('#patients-line-chart1 svg').parent().html(), 
				//alavel: $('#barchartlegend').html(),
				htmlTable: '<table  class="table table-striped display" width="100%" border="0.5" style="margin:0 auto;"> '+ $("#"+tableId).html()+' </table>',
				chart: chart			
			},
			success: function(response) {

				if(type == 'print'){
					window.open("<?php echo $baseUrl;?>report/print_master_dynamic_column.php?jBaseUrl=<?php echo $jBaseUrl; ?>"
					+"&lan=<?php echo $lan;?>"
					+"&reportSaveName="+reportSaveName 
					+"&reportHeaderList="+reportHeaderList
					+"&chart=" + chart
					);			
				}
				else if(type == 'excel'){
					var tableHeaderList = new Array();
					var dataList = new Array();					
					 
					var datatable =$('#'+tableId).dataTable();
					var rows = datatable.dataTableSettings[0].nTHead.rows;
					
					var tableHeaderList = [];
					var tableHeaderColSpanList = [];
					var tableHeaderRowSpanList = [];					
					for(var r = 0; r < rows.length; r++){	
						//// Creates an empty line
								  tableHeaderList.push([]);
								  tableHeaderColSpanList.push([]);
								  tableHeaderRowSpanList.push([]);
						 //// Adds cols to the empty line:
								  //tableHeaderList[index].push( new Array(totalColumn));
								  tableHeaderList[r].push( new Array(rows[r].length));
								  tableHeaderColSpanList[r].push( new Array(rows[r].length));
								  tableHeaderRowSpanList[r].push( new Array(rows[r].length));
						//}	  
						for(var c = 0; c < rows[r].cells.length; c++){	
							tableHeaderList[r][c] = rows[r].cells[c].textContent;//topTds[m].textContent;
							tableHeaderColSpanList[r][c] = rows[r].cells[c].colSpan;
							tableHeaderRowSpanList[r][c] = rows[r].cells[c].rowSpan;
						}		
					}
										
					tableHeaderList = JSON.stringify(tableHeaderList);
					tableHeaderColSpanList = JSON.stringify(tableHeaderColSpanList);
					tableHeaderRowSpanList = JSON.stringify(tableHeaderRowSpanList);
					tableHeaderColWidthList = JSON.stringify(cellWidth);
					var datatable =$('#'+tableId).dataTable();
					var rows = datatable.dataTableSettings[0].nTBody.rows;
					
					var dataList = [];
					var dataColSpanList = [];			
					for(var r = 0; r < rows.length; r++){	
						//// Creates an empty line
								  dataList.push([]);
								  dataColSpanList.push([]);
						 //// Adds cols to the empty line:
								  //dataList[index].push( new Array(totalColumn));
								  dataList[r].push( new Array(rows[r].length));
								  dataColSpanList[r].push( new Array(rows[r].length));
						//}	  
						for(var c = 0; c < rows[r].cells.length; c++){	
							dataList[r][c] = rows[r].cells[c].textContent;//topTds[m].textContent;
							dataColSpanList[r][c] = rows[r].cells[c].colSpan;
						}		
					}
										
					dataList = JSON.stringify(dataList);
					dataColSpanList = JSON.stringify(dataColSpanList);
					
					
					$.ajax({
						url: baseUrl + 'report/excel_master_dynamic_column.php',
						type: 'post',
						data: {
							jBaseUrl: "<?php echo $jBaseUrl; ?>",
							lan: lan,
							reportSaveName : reportSaveName,
							reportHeaderList : reportHeaderList,
							tableHeaderList : tableHeaderList,
							tableHeaderColSpanList : tableHeaderColSpanList,
							tableHeaderRowSpanList : tableHeaderRowSpanList,
							tableHeaderColWidthList : tableHeaderColWidthList,
							dataList : dataList,
							dataColSpanList:dataColSpanList,
							chart : chart,
                            dataAlignment: JSON.stringify(dataAlignment)	
						},
						success: function(response) {
						window.open( baseUrl + 'report/media/'+reportSaveName+'.xlsx');
						}
					});	
					/**/	
				}
				else if(type == 'pdf'){			
					var columns = $('#'+tableId).dataTable().dataTableSettings[0].aoColumns;
					var totalColumn=0;
					var totalWidth = 1192;
					$.each(columns, function(i,v) {
						if(v.bVisible){
							totalColumn++;
						}
					});				
					
					var htmlTable ='<table width="100%" border="0.5" style="margin:0 auto;"><thead>';
					var topThs = $("#"+tableId+" tHead tr");
					for(var m = 0; m < topThs.length; m++){
						htmlTable+= '<tr role="row">';
						for(var n = 0; n < topThs[m].children.length; n++){
							var tmpThWidth = ((topThs[m].children[n].clientWidth*100)/totalWidth).toFixed();//Math.round((topThs[m].children[n].clientWidth*100)/totalWidth);
							htmlTable+= '<th role="columnheader" colspan="'+topThs[m].children[n].colSpan
							+'" rowspan="'+topThs[m].children[n].rowSpan
							+'" class="'+topThs[m].children[n].className
							+'" style=" width:'+tmpThWidth
							+'%; text-align:'+topThs[m].children[n].style.textAlign
							+';">'+topThs[m].children[n].textContent+'</th>';
						}
						htmlTable+= '</tr>';
					}
					htmlTable+= '</thead><tbody>';

					var topTds = $("#"+tableId+" tr td");
					var i=0;
					for(var m = 0; m < topTds.length; m++){
						i++
						if(i==1){
							htmlTable+='<tr>';
						}
						var tmpWidth = Math.round((topTds[m].clientWidth*100)/totalWidth);
						htmlTable+='<td width="'+tmpWidth+'%" class="'+topTds[m].className+'"'+'>'+topTds[m].textContent+'</td>';
						//console.log(topTds[m], "client: ", topTds[m].clientWidth, "offset ", topTds[m].offsetWidth, "scroll ", topTds[m].scrollWidth,topTds[m].textContent);
						//console.log(topTds[m].style);
						if(i==totalColumn){
							htmlTable+='</tr>';
							i = 0;
						}			
					}
					htmlTable+='</tbody></table>';

					$.ajax({
						url: baseUrl + 'report/pdf_master_dynamic_column.php',
						type: 'post',
						data: {
							jBaseUrl: "<?php echo $jBaseUrl; ?>",
							lan: lan,
							reportSaveName : reportSaveName,
							reportHeaderList: reportHeaderList,
							chart : chart,
							htmlTable: htmlTable	
						},
						success: function(response) {
							window.open( baseUrl + 'report/pdfslice/' + response);
						}
					});					
				}			
			}
		});	
}
function print_function_back(type){
	var tableId = 'patientRatioTable';
	var reportHeaderList = new Array();
	var chart = 1;
	
	var reportHeaderName = 'Case Ratio Report';
	reportHeaderList[0] = reportHeaderName;
	reportHeaderList[1] = 'Country Name: ' + $('#country-list option[value='+$('#country-list').val()+']').text()+', Product Group: ' + $('#item-group-list option[value='+$('#item-group').val()+']').text();
	reportHeaderList[2] = 'Owner Type: ' + $('#OwnerType option[value='+$('#country-list').val()+']').text();
	//reportHeaderList[2] = 'Month: ' + $('#month-list option[value='+$('#month-list').val()+']').text()+', Year: ' + $('#year-list option[value='+$('#year-list').val()+']').text();
	
	reportHeaderList = JSON.stringify(reportHeaderList);

	//Get current date time
	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth()+1; //January is 0!
	var yyyy = today.getFullYear();
	var hh = today.getHours();
	var min = today.getMinutes();
	var sec = today.getSeconds();
	today = dd+'_'+mm+'_'+yyyy+'_'+hh+'_'+min+'_'+sec;
	var reportSaveName = reportHeaderName+'_'+today;// reportHeaderList[0].str.replace(/ /g, '_')+'_'+today;

	 $.ajax({
		    type: "POST",
			url: baseUrl + "report/chart_generate_svg.php",
			async: false,
			datatype: "json",
			cache: true,
			data: {
				jBaseUrl :baseUrl,
				svgName : reportSaveName,
				html: $('#patients-line-chart1 svg').parent().html(), 
				//alavel: $('#barchartlegend').html(),
				htmlTable: '<table  class="table table-striped display" width="100%" border="0.5" style="margin:0 auto;"> '+ $("#"+tableId).html()+' </table>',
				chart: chart			
			},
			success: function(response) {

				if(type == 'print'){
					window.open("<?php echo $baseUrl;?>report/print_master_dynamic_column.php?jBaseUrl=<?php echo $jBaseUrl; ?>"
					+"&lan=<?php echo $lan;?>"
					+"&reportSaveName="+reportSaveName 
					+"&reportHeaderList="+reportHeaderList
					+"&chart=" + chart
					);			
				}
				else if(type == 'pdf'){			
					var columns = $('#'+tableId).dataTable().dataTableSettings[0].aoColumns;
					var totalColumn=0;
					var totalWidth = 1192;
					$.each(columns, function(i,v) {
						if(v.bVisible){
							totalColumn++;
						}
					});				
					
					var htmlTable ='<table width="100%" border="0.5" style="margin:0 auto;"><thead>';
					var topThs = $("#"+tableId+" tHead tr");
					for(var m = 0; m < topThs.length; m++){
						htmlTable+= '<tr role="row">';
						for(var n = 0; n < topThs[m].children.length; n++){
							var tmpThWidth = ((topThs[m].children[n].clientWidth*100)/totalWidth).toFixed();//Math.round((topThs[m].children[n].clientWidth*100)/totalWidth);
							htmlTable+= '<th role="columnheader" colspan="'+topThs[m].children[n].colSpan
							+'" rowspan="'+topThs[m].children[n].rowSpan
							+'" class="'+topThs[m].children[n].className
							+'" style=" width:'+tmpThWidth
							+'%; text-align:'+topThs[m].children[n].style.textAlign
							+';">'+topThs[m].children[n].textContent+'</th>';
						}
						htmlTable+= '</tr>';
					}
					htmlTable+= '</thead><tbody>';

					var topTds = $("#"+tableId+" tr td");
					var i=0;
					for(var m = 0; m < topTds.length; m++){
						i++
						if(i==1){
							htmlTable+='<tr>';
						}
						var tmpWidth = Math.round((topTds[m].clientWidth*100)/totalWidth);
						htmlTable+='<td width="'+tmpWidth+'%" class="'+topTds[m].className+'"'+'>'+topTds[m].textContent+'</td>';
						//console.log(topTds[m], "client: ", topTds[m].clientWidth, "offset ", topTds[m].offsetWidth, "scroll ", topTds[m].scrollWidth,topTds[m].textContent);
						//console.log(topTds[m].style);
						if(i==totalColumn){
							htmlTable+='</tr>';
							i = 0;
						}			
					}
					htmlTable+='</tbody></table>';

					$.ajax({
						url: baseUrl + 'report/pdf_master_dynamic_column.php',
						type: 'post',
						data: {
							jBaseUrl: "<?php echo $jBaseUrl; ?>",
							lan: lan,
							reportSaveName : reportSaveName,
							reportHeaderList: reportHeaderList,
							chart : chart,
							htmlTable: htmlTable	
						},
						success: function(response) {
							window.open( baseUrl + 'report/pdfslice/' + response);
						}
					});					
				}			
			}
		});	
}

	
function excel_function(){
		window.open("<?php echo $baseUrl; ?>report/t_patient_trend_time_series_excel.php?jBaseUrl=<?php echo $jBaseUrl; ?>&lan=<?php echo $lan;?>
		&CountryId="+$('#country-list').val()+"&MonthNumber="+MonthNumber
		+"&CountryName="+$('#country-list option[value='+$('#country-list').val()+']').text()
		+"&StartMonthId="+$('#start-month-list').val()+"&EndMonthId="+$('#end-month-list').val()
	    +"&StartYearId="+$('#start-year-list').val()+"&EndYearId="+$('#end-year-list').val()
    +"");
}
</script>

<script src="<?php echo $baseUrl; ?>lib/fnc-lib.js" type="text/javascript"></script>
<link href="<?php echo $baseUrl; ?>media/datatable-bootstrap/dataTables.bootstrap.css" rel="stylesheet"/>
<link href="<?php echo $baseUrl; ?>css/jquery.dataTables.custom.css" rel="stylesheet"/>
<script src='<?php echo $baseUrl; ?>media/datatable/js/jquery.dataTables.min.js'></script>
<script src="<?php echo $baseUrl; ?>media/highcharts/highcharts.js" type="text/javascript"></script>
<script src="<?php echo $baseUrl; ?>media/highcharts/data.js" type="text/javascript"></script>
<script src="<?php echo $baseUrl; ?>media/highcharts/drilldown.js" type="text/javascript"></script>
<script src='<?php echo $baseUrl; ?>media/datatable-bootstrap/dataTables.bootstrap.min.js'></script>
<script src='<?php echo $baseUrl; ?>/Patient_Ratio.js'></script>

<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_en.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_fr.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_switcher.js"></script>