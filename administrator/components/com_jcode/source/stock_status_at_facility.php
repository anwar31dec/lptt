<?php
$user = JFactory::getUser();
$baseUrl = JURI::base() . 'administrator/components/com_jcode/source/';
$jBaseUrl = JURI::base();
$lang = JFactory::getLanguage();
$lan = $lang->getTag();
$userName = $user->username; 
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
var vLang = '<?php echo $vLang; ?>';
var userName = '<?php echo $userName; ?>';

</script>


<?php
	
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

<link href="<?php echo $baseUrl; ?>leafletjs/leaflet.css" rel="stylesheet">

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
													<td><?php echo $TEXT['Country']; ?>:&nbsp;</td><td width="120px" valign="middle" align="left">
													<select class="form-control" id="country-list">								
													</select></td>
													<td>&nbsp;</td>	
													<td><?php echo $TEXT['Region']; ?>:&nbsp;</td><td valign="middle" align="left">
													<select class="form-control" id="region-list"><?php echo user_all_test();?>								
													</select></td>
													<td>&nbsp;</td>								
													 <td><?php echo $TEXT['District']; ?>&nbsp;:&nbsp;&nbsp;</td>
													<td><select class="form-control" id="District-list"></select></td>
													 <td>&nbsp;&nbsp;</td>
													<td><?php echo $TEXT['Owner Type']; ?>&nbsp;:&nbsp;&nbsp;</td>
													<td><select class="form-control" id="OwnerType"><?php echo user_all_test();?></select></td>				
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
													<td><?php echo $TEXT['Product Group']; ?>:&nbsp;</td>
													<td><select class="form-control" id="item-group-list">
													</select></td>														
													<td>&nbsp;&nbsp;</td>
													<td><?php echo $TEXT['Facility Level']; ?>:&nbsp;</td>
													<td valign="middle" align="left">
													<select class="form-control" id="facility-level-list">							
													</select></td>
													<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
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
						<div class="clearfix"><br></div>
						<div class="row"> 
							<div class="col-md-12 col-sm-12 col-sx-12">
									<div class="panel-heading clearfix">
										<div id="fic-group-button">
										</div>
										
										
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
				</div>
			</div>
		</div>
		
		<div class="margin_top1"><br></div>
		<div class="clearfix"><br></div>
				
		<div class="row">
			<div class="col-md-6 col-sm-12 col-sx-12">
				<div class="panel panel-default">
					<div class="panel-heading clearfix">
						<?php echo $TEXT['Country map of Mali']; ?>
					</div>
					<div id="trafficWidget" class="panel-body">
						<div id="map-inner">
							<div id="map" style="width: 100%; height: 570px"></div>
							
						</div>
					</div>
					<div class="panel-footer">
						<div id="barchartlegend" class="legend-80"></div>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-sm-12 col-sx-12">
				<div id="cparams-panel" class="panel panel-default">
					<div id="itemname-header" class="panel-heading clearfix">
						<span><?php echo $TEXT['Product']; ?>&nbsp;:&nbsp;</span><span><select class="form-control" id="item-list"></select></span>
					</div>
					<div class="panel-body">
						<div class="clearfix list-panel" >
							<table id="stock-status-at-facility" class="table table-striped table-bordered display table-hover" cellspacing="0">
								<thead>
									<tr>
										<th style="width:10%">SL.</th>
										<th style="width:30%"><?php echo $TEXT['Health Facility']; ?></th>
										<th style="width:20%"><?php echo $TEXT['Balance']; ?></th>
										<th style="width:20%"><?php echo $TEXT['AMC']; ?></th>
										<th style="width:20%"><?php echo $TEXT['MOS']; ?></th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>



			    
		</div>		
	</div>
</div>


<style type="text/css">
	span#itemno-head{
		font-size: 1.3em;
		color: green;
	}
	.states {    
    	fill: #FB7922;   
	}
	svg{		
		max-width: none !important;
		max-height: none !important;
	}
	table tbody tr.even.row_selected td, table tbody tr.odd.row_selected td {
    	background-color: #9AD268;
    	color: #fff;
    }
    #fic-group-button .btn-group .active{
		background-color: #EBEBEB;
	}
	
	div.dataTables_filter input {
		width: 120px !important;
	}

</style>

<script>


function print_function(type){
	var dataTableId = 'stock-status-at-facility';
	var reportSaveName = 'Facility_Stock_Status_by_Product'; //Not allow any type of special character of cahrtName
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
	
	var reportHeaderName = TEXT['Facility Stock Status by Product on'];// + $('#month-list option[value='+$('month-list').val()+']').text()+', '+$('#year-list option[value='+$('year-list').val()+']').text();
	reportHeaderList[0] = reportHeaderName;
	reportHeaderList[1] = TEXT['Country']+ ': ' + $('#country-list option[value='+$('#country-list').val()+']').text()+
						  TEXT['Region']+ ': ' + $('#region-list option[value='+$('#region-list').val()+']').text()+
						  TEXT['District']+ ': ' + $('#District-list option[value='+$('#District-list').val()+']').text()+
						  TEXT['Owner Type']+ ': ' + $('#OwnerType option[value='+$('#OwnerType').val()+']').text()+
						  TEXT['Product Group']+ ': ' + $('#item-group-list option[value='+$('#item-group-list').val()+']').text();
	reportHeaderList[2] = TEXT['Facility Level']+ ': ' + $('#facility-level-list option[value='+$('#facility-level-list').val()+']').text()+
						  TEXT['Product Name']+ ': ' + $('#item-list option[value='+$('#item-list').val()+']').text();		
	/**/
	sqlParameterList[0]= (($('#country-list').val() == '') ? 0 : $('#country-list').val());	
	sqlParameterList[1]= (($('#region-list').val() == '') ? 0 : $('#region-list').val());	
	sqlParameterList[2]= (($('#District-list').val() == '') ? 0 : $('#District-list').val());	
	sqlParameterList[3]= (($('#OwnerType').val() == '') ? 0 : $('#OwnerType').val());	
	sqlParameterList[4]= (($('#item-group-list').val() == '') ? 0 : $('#item-group-list').val());	
	sqlParameterList[5]= (($('#facility-level-list').val() == '') ? 0 : $('#facility-level-list').val());	
	sqlParameterList[6]= (($('#month-list').val() == '') ? 0 : $('#month-list').val());	
	sqlParameterList[7]= (($('#year-list').val() == '') ? 0 : $('#year-list').val());	
	sqlParameterList[8]= (($('#item-list').val() == '') ? 0 : $('#item-list').val());
	sqlParameterList[9]= ((gMosTypeId == '') ? 0 : gMosTypeId);	
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
		window.open("<?php echo $baseUrl;?>report/print_master.php?action=getStockStatusAtFacility"
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
		window.open("<?php echo $baseUrl;?>report/excel_master.php?action=getStockStatusAtFacility"
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
				action: 'getStockStatusAtFacility',
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

/*
	var pMonthName = $("#month-list option:selected").text();
	var pYear = $("#year-list option:selected").text();
	var pItemName = $("#item-list option:selected").text();
	var pCountryName = $("#country-list option:selected").text();
	var pItemGroupName = $("#item-group-list option:selected").text();
	var pItemList = $("#item-list option:selected").text();
	var pRegionName = $("#region-list option:selected").text();
	var pFLevelName = $("#facility-level-list option:selected").text();
	
	window.open("<?php echo $baseUrl; ?>report/t_stock_status_at_facility_print.php?jBaseUrl=<?php echo $jBaseUrl; ?>&lan=<?php echo $lan;?>&CountryId="
	+$('#country-list').val()+"&ItemGroupId="+$('#item-group-list').val()+"&ItemNo="+$('#item-list').val()
    +"&RegionId="+$('#region-list').val()+"&FLevelId="+$('#facility-level-list').val()
	+"&MonthId="+$('#month-list').val()+"&Year="+$('#year-list').val()
	+"&MonthName=" + pMonthName
	+"&Year=" + pYear
	+"&CountryName=" + pCountryName
	+"&ItemGroupName=" + pItemGroupName
	+"&ItemName=" + pItemList
	+"&RegionName=" + pRegionName
	+"&FLevelName=" + pFLevelName);			 
 }
  function excel_function()
{
	var pMonthName = $("#month-list option:selected").text();
	var pYear = $("#year-list option:selected").text();
	var pItemName = $("#item-list option:selected").text();
	var pCountryName = $("#country-list option:selected").text();
	var pItemGroupName = $("#item-group-list option:selected").text();
	var pItemList = $("#item-list option:selected").text();
	var pRegionName = $("#region-list option:selected").text();
	var pFLevelName = $("#facility-level-list option:selected").text();
	
	window.open("<?php echo $baseUrl; ?>report/t_stock_status_at_facility_excel.php?jBaseUrl=<?php echo $jBaseUrl; ?>&lan=<?php echo $lan;?>&CountryId="
	+$('#country-list').val()+"&ItemGroupId="+$('#item-group-list').val()+"&ItemNo="+$('#item-list').val()
    +"&RegionId="+$('#region-list').val()+"&FLevelId="+$('#facility-level-list').val()
	+"&MonthId="+$('#month-list').val()+"&Year="+$('#year-list').val()
	+"&MonthName=" + pMonthName
	+"&Year=" + pYear
	+"&CountryName=" + pCountryName
	+"&ItemGroupName=" + pItemGroupName
	+"&ItemName=" + pItemList
	+"&RegionName=" + pRegionName
	+"&FLevelName=" + pFLevelName);	

 } 
 
 function pdf_function(){
    var currentSearch = $('#stock-status-at-facility_filter').find('input').val();
    var pMonthName = $("#month-list option:selected").text();
	var pYear = $("#year-list option:selected").text();
	var pItemName = $("#item-list option:selected").text();
	var pCountryName = $("#country-list option:selected").text();
	var pItemGroupName = $("#item-group-list option:selected").text();
	var pItemList = $("#item-list option:selected").text();
	var pRegionName = $("#region-list option:selected").text();
	var pFLevelName = $("#facility-level-list option:selected").text();
	
    
   	$.ajax({
		url: baseUrl + 'report/r_stock_status_at_facility_pdf.php',
		type: 'post',
		data: {
			operation: 'generateFacilityStockStatusReport',
            lan: lan, 
            CountryId: $('#country-list').val(),
            MonthId:  $('#month-list').val(),
            YearId : $('#year-list').val(),
            ItemGroupId : $('#item-group-list').val(),
            ItemNo : $('#item-list').val(),
            RegionId : $('#region-list').val(),
            FLevelId : $('#facility-level-list').val(),
            CountryName: pCountryName,
            MonthName: pMonthName,
            ItemGroupName: pItemGroupName,
            ItemName: pItemName,
            RegionName: pRegionName,
	        FLevelName: pFLevelName,
            sSearch: currentSearch
		},
		success: function(response) {
			if (response == 'Processing Error') {
				alert('No Record Found.');
			} else {
			alert(baseUrl + 'report/pdfslice/' + response);
				//window.open( baseUrl + 'report/pdfslice/' + response);
            }
		}
	});			 
}
	*/	
 </script>


<script src="<?php echo $baseUrl; ?>lib/fnc-lib.js" type="text/javascript"></script>
<link href="<?php echo $baseUrl; ?>media/datatable-bootstrap/dataTables.bootstrap.css" rel="stylesheet"/>
<link href="<?php echo $baseUrl; ?>css/jquery.dataTables.custom.css" rel="stylesheet"/>
<script src='<?php echo $baseUrl; ?>media/datatable/js/jquery.dataTables.min.js'></script>
<script src='<?php echo $baseUrl; ?>media/datatable-bootstrap/dataTables.bootstrap.min.js'></script>
<script src='<?php echo $baseUrl; ?>maps/js/d3.v3.min.js'></script>
<script src='<?php echo $baseUrl; ?>maps/js/topojson.v0.min.js'></script>
<script src='<?php echo $baseUrl; ?>maps/js/queue.min.js'></script>
<script src='<?php echo $baseUrl; ?>maps/js/underscore-min.js'></script>
<link href="<?php echo $baseUrl; ?>maps/css/map-style.css" rel="stylesheet">
<link href="<?php echo $baseUrl; ?>maps/css/leaflet.css" rel="stylesheet">
<script src='<?php echo $baseUrl; ?>leafletjs/leaflet.js'></script>
<script src='<?php echo $baseUrl; ?>leafletjs/PruneCluster.js'></script>

<link href="<?php echo $baseUrl; ?>maps/css/leaflet.fullscreen.css" rel="stylesheet">
<script src='<?php echo $baseUrl; ?>leafletjs/Leaflet.fullscreen.js'></script>

<script src='<?php echo $baseUrl; ?>stock_status_at_facility.js'></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_en.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_fr.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_switcher.js"></script>