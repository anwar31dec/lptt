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

<div class="nav-data">
	<div class="row">				
		<div class="col-md-6 col-padding">					
			<div class="tbl-header1" id="itemTable_length1">
				<center> 							
					<table id="month-year">
						<tbody>
							<tr>
								<td valign="middle" align="right">
								<button class="btn btn-info" type="button" id="left-arrow"><span class="fa fa-arrow-left fntC"> </span></button></td>
								<td>&nbsp;&nbsp;</td><td valign="middle" align="left"><select class="form-control" id="month-list"></select></td>
								<td>&nbsp;&nbsp;</td><td valign="middle" align="left"><select class="form-control" id="year-list"></select></td>
								<td>&nbsp;&nbsp;</td><td width="" valign="middle" align="left">
								<button class="btn btn-info" type="button" id="right-arrow"><span class="fa fa-arrow-right fntC"></span></button></td>
							</tr>
						</tbody>
					</table>
				</center> 				
			</div>
			
		</div>
		<div class="col-md-6 col-padding">
			<div class="tbl-header1 pull-right">
				<label>		
					<a id="PrintBTN" data-mce-href="#" class="but_print" href="javascript:void(0);" onclick="print_function('print')"><i data-mce-bootstrap="1" class="fa fa-print fa-lg">&nbsp;</i> <?php echo $TEXT['Print']; ?></a>
					<a id="PrintBTN1" data-mce-href="#" class="but_excel" href="javascript:void(0);" onclick="print_function('excel')"><i data-mce-bootstrap="1" class="fa fa-file-excel-o fa-lg">&nbsp;</i> <?php echo $TEXT['Excel']; ?></a>
				</label>
			</div>
		</div>
		
	</div>
</div>

<div class="row"> 
	<div id="sandbox-container" class="col-md-6">
		<div class="input-daterange input-group" id="datepicker">
			<input type="text" class="input-sm form-control" id="dp1-start" name="start" readonly=""/>
			<span class="input-group-addon">to</span>
			<input type="text" class="input-sm form-control" id="dp1-end" name="end" readonly=""/>
		</div>
	</div>
</div> 
<div class="row"> 
	<div class="col-md-6">
		<table  id="tblProcessCount" class="table table-striped table-bordered display table-hover" cellspacing="0">
			<thead>
				<tr>
					<th style="width:10%">SL.</th>
					<th style="width:30%"><?php echo 'Process Name'; ?></th>
					<th style="width:30%"><?php echo 'Total'; ?></th>
					<th style="width:30%"><?php echo 'Process Id'; ?></th>
				</tr>
			</thead>
			<tbody>	</tbody>
		</table>				
	</div>	
	<div class="col-md-6">
	</div>			
</div>
		

<script>
function print_function(type){
	//console.log($('#tblJobCountInAllProcess').dataTable());
	var tableId = 'tblJobCountInAllProcess';
	var reportHeaderList = new Array();
	var dataAlignment = new Array();
	var chart = 0;
	var reportSaveName = 'Job_Total_Time_Duration'; //Not allow any type of special character of cahrtName
	
	var reportHeaderName = 'Job Total Time Duration';
	reportHeaderList[0] = reportHeaderName;
	reportHeaderList[1] = $('#country-list option[value='+$('#country-list').val()+']').text()+' - ' + $('#item-group option[value='+$('#item-group').val()+']').text()+ ' - ' + $('#report-by option[value='+$('#report-by').val()+']').text();

	dataAlignment = ["center","left","right","right","right","right","right","right","right","right","right","right","right","right"];
	//when column count and width array count are not same then last value repeat
	cellWidth = ["10","82","13"];

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
	reportSaveName = reportSaveName+'_'+today;// reportHeaderList[0].str.replace(/ /g, '_')+'_'+today;
	
	 $.ajax({
		    type: "POST",
			url: baseUrl + "report/chart_generate_svg.php",
			async: false,
			datatype: "json",
			cache: true,
			data: {
				baseUrl :baseUrl,
				svgName : reportSaveName,
				html: $('#bar-chart svg').parent().html(), 
				alavel: $('#barchartlegend').html(),
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
					//var tableHeaderColWidthList = [];					
					for(var r = 0; r < rows.length; r++){	
						//// Creates an empty line
								  tableHeaderList.push([]);
								  tableHeaderColSpanList.push([]);
								  tableHeaderRowSpanList.push([]);
								  //tableHeaderColWidthList.push([]);
						 //// Adds cols to the empty line:
								  //tableHeaderList[index].push( new Array(totalColumn));
								  tableHeaderList[r].push( new Array(rows[r].length));
								  tableHeaderColSpanList[r].push( new Array(rows[r].length));
								  tableHeaderRowSpanList[r].push( new Array(rows[r].length));
								 // tableHeaderColWidthList[r].push( new Array(rows[r].length));
						//}	  
						for(var c = 0; c < rows[r].cells.length; c++){	
							tableHeaderList[r][c] = rows[r].cells[c].textContent;//topTds[m].textContent;
							tableHeaderColSpanList[r][c] = rows[r].cells[c].colSpan;
							tableHeaderRowSpanList[r][c] = rows[r].cells[c].rowSpan
							//tableHeaderColWidthList[r][c] = rows[r].cells[c].clientWidth;
						}		
					}
										
					tableHeaderList = JSON.stringify(tableHeaderList);
					tableHeaderColSpanList = JSON.stringify(tableHeaderColSpanList);
					tableHeaderRowSpanList = JSON.stringify(tableHeaderRowSpanList);
					tableHeaderColWidthList = JSON.stringify(cellWidth);
					//console.log(tableHeaderColWidthList);
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
                            tableHeaderColWidthList: tableHeaderColWidthList,
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
					var datatable =$('#'+tableId).dataTable();				
					var columns = datatable.dataTableSettings[0].aoColumns;
					var totalColumn=0;
					//var totalWidth = datatable[0].clientWidth;//1192;
					var totalWidth = datatable[0].clientWidth+20;//1192;
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
							var tmpThWidth = ((topThs[m].children[n].clientWidth*100)/totalWidth).toFixed();
							//////Math.round((topThs[m].children[n].clientWidth*100)/totalWidth);
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
						htmlTable+='<td style="text-align:'+dataAlignment[i-1]+';" width="'+tmpWidth+'%" class="'+topTds[m].className+'"'+'>'+topTds[m].textContent+'</td>';
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
						}
						
						,
						success: function(response) {
							window.open( baseUrl + 'report/pdfslice/' + response);
						}
					});	
					
/*
var ajaxRequest;

        try{
            // Opera 8.0+, Firefox, Safari
            ajaxRequest = new XMLHttpRequest();
        } catch (e){
            // Internet Explorer Browsers
            try{
                ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                try{
                    ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e){
                    // Something went wrong
                    alert("Browser Not Supported");
                    return false;
                }
            }
        }
ajaxRequest.open("POST",  baseUrl + "report/pdf_master_dynamic_column.php", true);
ajaxRequest.send("jBaseUrl=localhost&lan="+lan+"&reportSaveName="+reportSaveName+"&reportHeaderList="+reportHeaderList+"&chart="+chart+"&htmlTable="+htmlTable);
*/

				}			
			}
		});	
}




</script>

<script src="<?php echo $baseUrl; ?>lib/fnc-lib.js" type="text/javascript"></script>

<link href="<?php echo $baseUrl; ?>media/datatable-bootstrap/dataTables.bootstrap.css" rel="stylesheet"/>
<link href="<?php echo $baseUrl; ?>css/jquery.dataTables.custom.css" rel="stylesheet"/>
<script src='<?php echo $baseUrl; ?>media/datatable/js/jquery.dataTables.min.js'></script>
<script src='<?php echo $baseUrl; ?>media/datatable-bootstrap/dataTables.bootstrap.min.js'></script>

<link href="<?php echo $baseUrl; ?>lib/bootstrap-datepicker-1.4.0/css/bootstrap-datepicker3.css" rel="stylesheet"/>
<script src='<?php echo $baseUrl; ?>lib/bootstrap-datepicker-1.4.0/js/bootstrap-datepicker.js'></script>



<script src='<?php echo $baseUrl; ?>dashboard.js'></script>

<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_en.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_fr.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_switcher.js"></script>

   