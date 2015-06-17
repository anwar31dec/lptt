<?php
$user = JFactory::getUser();
$baseUrl = JURI::base() . 'administrator/components/com_jcode/source/';
$jBaseUrl = JURI::base();
$lang = JFactory::getLanguage();
$lan = $lang->getTag();
//echo $jBaseUrl;
?>

<?php 
	include_once ('database_conn.php');
	include_once ('init_month_year.php');
	include_once ('function_lib.php');
	include_once ('combo_script.php');
	include_once ('language/lang_en.php');
	include_once ('language/lang_fr.php');
	include_once ('language/lang_switcher.php');
?>

<script>
	var baseUrl = '<?php echo $baseUrl; ?>';
	var jbaseUrl = '<?php echo $jBaseUrl; ?>';
	var lan='<?php echo $lan;?>';
</script>
<script type="text/javascript">
	var vLang = '<?php echo $vLang; ?>';
</script>

<?php
	function getMOSbox() {
		$sql = "SELECT MosTypeName, MinMos, MaxMos, ColorCode FROM t_mostype ORDER BY MosTypeId";
		$result = mysql_query($sql);
		$total = mysql_num_rows($result);

		$x = "<table><tr>";
		$z = "</tr><tr>";
		$y = "</tr><tr>";
		if ($total > 0) {
			while ($row = mysql_fetch_object($result)) {
				$x .= "<td><div style='background-color:" . $row -> ColorCode . ";'>&nbsp;</div></td>";
				$z .= "<td>" . $row -> MosTypeName . "</td>";
				$y .= "<td>MOS: " . $row -> MinMos . " - " . $row -> MaxMos . "</td>";
			}
		}
		$x = $x . $z . $y . "</tr></table>";

		//$x = str_replace("\n", '', $x);
		//$x = str_replace("\r", '', $x);
		return $x;
	}
	
	function getBtnGroupMosType() {
		$sql = "SELECT MosTypeId, MosTypeName, MinMos, MaxMos, ColorCode FROM t_mostype ORDER BY MosTypeId";
		$result = mysql_query($sql);
		$total = mysql_num_rows($result);

		$x = '<div class="btn-group pull-left">'
			.'<button id="0" class="btn btn-default active" type="button">All</button>';
		if ($total > 0) {
			while ($row = mysql_fetch_object($result)) {
				$x .= '<button id = "' . $row -> MosTypeId . '" class="btn btn-default" type="button">' . $row -> MosTypeName . '</button>';
			}
		}
		$x = $x . "</div>";
		return $x;
	}
?>

<div class="container">
	<div class="content_fullwidth lessmar">
		<div class="azp_col-md-12 one_full">
		
		<div class="row">
			<div class="col-md-12 col-sm-12 col-sx-12">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12 col-sm-12 col-sx-12">		
								<center>							
									<table id="month-year">
										<tbody>
											<tr>
											<td><?php echo $TEXT['Country']; ?>&nbsp;:&nbsp;&nbsp;</td>
												<td>
													<select class="form-control" id="country-list">
													</select>
												</td>
												<td>&nbsp;&nbsp;</td>
												
												<td><?php echo $TEXT['Product Group']; ?>&nbsp;:&nbsp;&nbsp;</td>
												<td>
												<select class="form-control" id="item-group-list"></select>
												</td>
												<td>&nbsp;&nbsp;</td>
												
												<td><?php echo $TEXT['Report By']; ?>&nbsp;:&nbsp;&nbsp;</td>
												<td>
												<select class="form-control" id="report-by"></select>
												</td>
											</tr>
										</tbody>
									</table>
								</center>								
							</div>
						</div>
						<div class="clearfix"><br></div>
						<div class="row">
							<div class="col-md-12 col-sm-12 col-sx-12">	
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
						<div class="clearfix"><br></div>	
							<div class="row">
								<div class="col-md-12 col-sm-12 col-sx-12">
									<center>
										<table id="nav-country">
											<tbody>
												<tr>													
													<td><b><?php echo $TEXT['Reporting Rate']; ?></b> &nbsp;&nbsp;</td>									
													<td><?php echo $TEXT['Total']; ?>:&nbsp;&nbsp;</td>
													<td><span class="badge badge-success" id="Total"></span></td>
													
													<td>&nbsp;&nbsp;</td>
													<td><?php echo $TEXT['Facility Level']; ?>:&nbsp;&nbsp;</td>
													<td><span class="badge badge-success" id="Facility"></span></td>
													
													 <td>&nbsp;&nbsp;</td>
													<td><?php echo $TEXT['District Level']; ?>:&nbsp;&nbsp;</td>
													<td><span class="badge badge-success" id="District"></span></td>
													
													 <td>&nbsp;&nbsp;</td>
													<td><?php echo $TEXT['Region Level']; ?>:&nbsp;&nbsp;</td>
													<td><span class="badge badge-success" id="Region"></span></td>
													
													 <td>&nbsp;&nbsp;</td>
													<td><?php echo $TEXT['ppm Central']; ?>:&nbsp;&nbsp;</td>
													<td><span class="badge badge-success" id="Central"></span></td>
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
		
		
		<div class="margin_top1"><br></div>
		<div class="clearfix"><br></div>			
					
					
		<div class="row"> 
			<div class="col-md-12 col-sm-12 col-sx-12">
				<div id="cparams-panel" class="panel panel-default">
					<div class="panel-heading clearfix">		
						<div id="fic-group-button"></div>
						
						<div id="barchartlegend" class="legend-80 pull-right">
							<?php //echo getMOSbox(); ?>
						</div>
					</div>
					
					<div class="clearfix"><br></div>
					<div class="panel-heading clearfix">
						<?php echo $TEXT['National Inventory Control Data List']; ?>
						<span class="pull-right">
								<label>					
								<a id="PrintBTN" data-mce-href="#" class="but_print" href="javascript:void(0);" onclick="print_function('print')"><i data-mce-bootstrap="1" class="fa fa-print fa-lg">&nbsp;</i> <?php echo $TEXT['Print']; ?></a>
								<a id="PrintBTN1" data-mce-href="#" class="but_excel" href="javascript:void(0);" onclick="printfunction_excel()"><i data-mce-bootstrap="1" class="fa fa-file-excel-o fa-lg">&nbsp;</i> <?php echo $TEXT['Excel']; ?></a>
								<a id="PDFBTN" data-mce-href="#" class="but_pdf" href="javascript:void(0);" onclick="printfunction_pdf()"><i data-mce-bootstrap="1" class="fa fa-file-pdf-o fa-lg">&nbsp;</i> <?php echo $TEXT['PDF']; ?></a>
							</label>							   
						</span>				
					</div>

				
					<div class="panel-body">
						<div class="clearfix list-panel" id="tbl-fic">
							<table class="table table-striped table-bordered display table-hover" id="tbl-facility-inventory-control">
								<thead></thead>
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


<script>

function print_function(type){
	var tableId = 'tbl-facility-inventory-control';
	var reportSaveName = 'National_Inventory_Control'; //Not allow any type of special character of cahrtName
	var reportHeaderList = new Array();
	var chart = -1;
	
	var reportHeaderName = TEXT['National Inventory Control Report'];
	reportHeaderList[0] = reportHeaderName;
	reportHeaderList[1] = $('#country-list option[value='+$('#country-list').val()+']').text()+' - '+ $('#item-group-list option[value='+$('#item-group-list').val()+']').text()+' - '+ $('#report-by option[value='+$('#report-by').val()+']').text()+ ' - ' + $('#month-list option[value='+$('#month-list').val()+']').text()+', '+ $('#year-list option[value='+$('#year-list').val()+']').text();

	
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
				printfunction1();
				/*
					window.open("<?php echo $baseUrl;?>report/excel_master_dynamic_column.php?jBaseUrl=<?php echo $jBaseUrl; ?>"
					+"&lan=<?php echo $lan;?>"
					+"&reportSaveName="+reportSaveName 
					+"&reportHeaderList="+reportHeaderList
					+"&chart=" + chart
					);
					*/
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


function printfunction_excel()
{
	window.open("<?php echo $baseUrl; ?>report/t_national_inventory_control_excel.php?jBaseUrl=<?php echo $jBaseUrl; ?>&lan=<?php echo $lan;?>
	&CountryId="+$('#country-list').val()+"&MonthId="+$('#month-list').val()+"&MosTypeId="+gMosTypeId+"&YearId="+$('#year-list').val()
	+"&ItemGroupId="+$('#item-group-list').val()+"&OwnerTypeId="+$('#report-by').val()
    +"&CountryName="+$('#country-list option[value='+$('#country-list').val()+']').text()
    +"&MonthName="+$('#month-list option[value='+$('#month-list').val()+']').text()
    +"&Year="+$('#year-list option[value='+$('#year-list').val()+']').text()
	+"&OwnerType="+$('#report-by option[value='+$('#report-by').val()+']').text()
    +"&ItemGroupName="+$('#item-group-list option[value='+$('#item-group-list').val()+']').text());					 
}

function printfunction_pdf()
{ 
   	$.ajax({
		url: baseUrl + 'report/r_national_inventory_control_pdf.php',
		type: 'post',
		data: {
			operation: 'generateFacilityInventoryReport',
            lan: lan, 
			MosTypeId : gMosTypeId,	
            MonthId: $('#month-list').val(),
            MonthName: $("#month-list option:selected").text(),
            ItemGroupName: $("#item-group-list option:selected").text(),
           	OwnerTypeId: $('#report-by').val(),
			OwnerType: $("#report-by option:selected").text(),
			YearId: $('#year-list').val(),
            Year: $("#year-list option:selected").text(),
            CountryId: $('#country-list').val(),
            CountryName: $("#country-list option:selected").text(),
            ItemGroupId: $('#item-group-list').val()              			
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
</script>

<script src="<?php echo $baseUrl; ?>lib/fnc-lib.js" type="text/javascript"></script>
<link href="<?php echo $baseUrl; ?>media/datatable-bootstrap/dataTables.bootstrap.css" rel="stylesheet"/>
<link href="<?php echo $baseUrl; ?>css/jquery.dataTables.custom.css" rel="stylesheet"/>
<script src='<?php echo $baseUrl; ?>media/datatable/js/jquery.dataTables.min.js'></script>
<script src='<?php echo $baseUrl; ?>media/highcharts/highcharts.js'></script>
<script src='<?php echo $baseUrl; ?>media/datatable-bootstrap/dataTables.bootstrap.min.js'></script>
<script src='<?php echo $baseUrl; ?>national_inventory_control.js'></script>

<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_en.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_fr.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_switcher.js"></script>