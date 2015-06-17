<?php
$user = JFactory::getUser();
$baseUrl = JURI::base() . 'administrator/components/com_jcode/source/';
$jBaseUrl = JURI::base();
$lang = JFactory::getLanguage();
$lan = $lang->getTag();
$userName = $user->username;
?>

<script>
    var baseUrl =  '<?php echo $baseUrl; ?>';
    var lan = '<?php echo $lan;?>';
    var userid = '<?php echo $userName;?>'; 
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
<div class="page-title">
	<h3 class="no-margin"><?php echo $TEXT['Country Profile']; ?></h3><br />
    <div class="clearfix">
	<!--
        <span class="pull-right">
            <button class="btn btn-info" type="button" id="PrintBTN" onclick="pdf_function()" > <?php echo $TEXT['PDF']; ?> </button>
        </span>-->
    </div>
</div>

<div class="row">
	<div class="col-md-12 col-sm-12 col-sx-12">
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="row">
					<div class="col-md-4 col-sm-12 col-sx-12">
						<div class="pull-right">
							<table id="nav-year">
								<tbody>
									<tr>
										<td width="" valign="middle" align="right">
										<button class="btn btn-info" type="button" id="left-arrow">
											<span class="fa fa-arrow-left"> </span>
										</button></td>
										<td>&nbsp;&nbsp;</td><td valign="middle" align="left"><select class="form-control" id="year-list"></select></td>
										<td>&nbsp;&nbsp;</td><td width="" valign="middle" align="left">
										<button class="btn btn-info" type="button" id="right-arrow">
											<span class="fa fa-arrow-right"></span>
										</button></td>
									</tr>
								</tbody>
							</table>

						</div>
					</div>
					<div class="col-md-6 col-sm-12 col-sx-12">
						<div class="pull-right">
							<table id="nav-country">
								<tbody>
									<tr>
										<td><?php echo $TEXT['Country']; ?>&nbsp;:&nbsp;&nbsp;</td>
										<td><select class="form-control chzn-select" name="CountryName" id="CountryName"></select></select>
										</td>		
										<td>&nbsp;&nbsp;&nbsp;</td>
										<td><?php echo $TEXT['Product Group']; ?>&nbsp;:&nbsp;&nbsp;</td>
										<td>
											<select class="form-control" id="item-group"></select>
										</td>
								
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<div class="col-md-2 col-sm-12 col-sx-12">
						<div class="pull-right country-flag">
							<img src="<?php echo $baseUrl; ?>dashboard/images/flag-benin.jpg" width="50px" alt="Benin Flag" />
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<!-- <div class="col-md-4 col-sm-12 col-sx-12">
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="clearfix list-panel" >
					<img src="<?php echo $baseUrl; ?>dashboard/images/benin.jpg" alt="Benin" />
				</div>
			</div>
		</div>
	</div> -->
	<div class="col-md-7 col-sm-12 col-sx-12">
		<div class="panel panel-default">
			<div class="panel-heading clearfix">
				<?php echo $TEXT['Parameter List']; ?>
				<span class="pull-right">
                <button class="btn btn-info" type="button" id="PrintBTN" onclick="print_function('print',0)" > <?php echo $TEXT['Print']; ?> </button>
                <!--<button class="btn btn-info" type="button" id="PrintBTN1" onclick="print_function('excel',0)" > <?php echo $TEXT['Excel']; ?> </button>-->
                <button class="btn btn-info" type="button" id="PrintBTN1" onclick="excel_function_cprofile_parameter_list()" > <?php echo $TEXT['Excel']; ?> </button>
            </span>
				
			</div>
			<div class="panel-body">
				<div class="clearfix list-panel" >
					<table class="table table-striped table-bordered display table-hover" cellspacing="0" id="tbl-country-profile">
						<thead>
							<tr>
								<th>SL</th>
								<th><?php echo $TEXT['Parameter']; ?></th>
								<th><?php echo $TEXT['Value']; ?></th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>				
			</div>
		</div>	
		<div class="panel panel-default">	
			<div class="panel-heading clearfix">
				<?php echo $TEXT['Funding Requirements'].$TEXT['MonetaryTitle']; ?>
				<span class="pull-right">
                <button class="btn btn-info" type="button" id="PrintBTN" onclick="print_function('print',2)" > <?php echo $TEXT['Print']; ?> </button>
                <!--<button class="btn btn-info" type="button" id="PrintBTN1" onclick="print_function('excel',2)" > <?php echo $TEXT['Excel']; ?> </button>-->
                <button class="btn btn-info" type="button" id="PrintBTN1" onclick="excel_function_cprofile_funding_requirements()" > <?php echo $TEXT['Excel']; ?> </button>
            </span>
				
			</div>
			<div class="panel-body">				
				<div class="clearfix list-panel" >
					<table class="table table-striped table-bordered display table-hover" cellspacing="0" id="tbl-yc-funding-requirement">
						<thead>
							<tr>
								<th>SL</th>								
								<th><?php echo $TEXT['Product']; ?></th>
								<th><?php echo $TEXT['Formulation']; ?></th>
								<th><span id="cYear"><?php echo $TEXT['2013']; ?></span></th>
								<th><span id="nYear"><?php echo $TEXT['2014']; ?></span></th>
								<th><span id="nnYear"><?php echo $TEXT['2015']; ?></span></th>
								<th><?php echo $TEXT['Total']; ?></th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-5 col-sm-12 col-sx-12">
		
		<div class="panel panel-default">
			<div class="panel-body">
				<div id="map" >					
				</div>
			</div>
		</div>
		
		<div class="panel panel-default"  id="MalariaCases">
			<div class="panel-heading clearfix">
					<?php echo $TEXT['ART Protocols with Patient Count']; ?>
					
				<span class="pull-right">
                <button class="btn btn-info" type="button" id="PrintBTN2" onclick="print_function('print',1)" > <?php echo $TEXT['Print']; ?> </button>
                <!--<button class="btn btn-info" type="button" id="PrintBTN3" onclick="print_function('excel',1)" > <?php echo $TEXT['Excel']; ?> </button>-->
                <button class="btn btn-info" type="button" id="PrintBTN3" onclick="excel_function_cprofile_malaria_cases()" > <?php echo $TEXT['Excel']; ?> </button>
            </span>
					
					
			</div>	
			<div class="panel-body">			
				<div class="clearfix list-panel" >
					<table class="table table-striped table-bordered display table-hover" cellspacing="0" id="tblycregimenpatient">
						<thead>
							<tr>
								<th style="padding:1px;">SL</th>										
								<th><?php echo $TEXT['Formulation']; ?></th>
								<th><?php echo$TEXT['(0-4 Years)']; ?></th>
								<th><?php echo$TEXT['(5-14 Years)']; ?></th>
								<th><?php echo$TEXT['(15+ Years)']; ?></th>
								<th><?php echo$TEXT['Pregnant Women']; ?></th>	
							</tr>							
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12 col-sm-12 col-sx-12">
		<div class="panel panel-default">
			<div class="panel-heading clearfix">
				<?php echo $TEXT['Pledged Funding'].$TEXT['MonetaryTitle']; ?>
				<span class="pull-right">
                <button class="btn btn-info" type="button" id="PrintBTN" onclick="print_function('print',3)" > <?php echo $TEXT['Print']; ?> </button>
                <button class="btn btn-info" type="button" id="PrintBTN1" onclick="excel_function_cprofile()" > <?php echo $TEXT['Excel']; ?> </button>
            </span>
			
			</div>
			<div class="panel-body">				
				<div class="clearfix list-panel" >
					<div class="btn-group" style="float:right;">
						<button class="btn btn-default pf" onClick="onRequirementYear(1)" type="button">2013</button>
						<button class="btn btn-default pf" onClick="onRequirementYear(2)" type="button">2013</button>
						<button class="btn btn-default pf" onClick="onRequirementYear(3)" type="button">2013</button>
					</div>
					<div id="tbl-pf" class="clearfix">
						<table class="table table-striped table-bordered display table-hover" cellspacing="0" id="tbl-pledgedfundings">
							<thead></thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>

/*function print_function(type,tblId){
	var tableId = '';
	var reportHeaderName = '';
	if(tblId == 0){
		tableId = 'tbl-country-profile';
		reportHeaderName = TEXT['Parameter List'];
	}
	else if(tblId == 1){
		tableId = 'tblycregimenpatient';
		reportHeaderName = TEXT['Malaria Cases'];
	}
	else if(tblId == 2){
		tableId = 'tbl-yc-funding-requirement';
		reportHeaderName = TEXT['Funding Requirements (Expressed in Euro)'];
	}
	else if(tblId == 3){
		tableId = 'tbl-pledgedfundings';
		reportHeaderName = TEXT['Pledged Funding (Expressed in Euro)'];
	}

	var reportHeaderList = new Array();
	var chart = -1;
	
	console.log($('#'+tableId).dataTable());
	
	
	//var reportHeaderName = TEXT['National Stock Summary Report'];
	reportHeaderList[0] = reportHeaderName;
	reportHeaderList[1] = TEXT['Country']+ ': ' + $('#CountryName option[value='+$('#CountryName').val()+']').text()+',  '+TEXT['Product Group']+ ': ' + $('#item-group option[value='+$('#item-group').val()+']').text();
	reportHeaderList[2] = TEXT['Year']+ ': ' + $('#year-list option[value='+$('#year-list').val()+']').text();
	
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
					var tableHeaderList = new Array();
					var dataList = new Array();
					
					//var datatable =$('#'+tableId).dataTable();
					//var columns = datatable.dataTableSettings[0].aoColumns;
					//var columns = datatable.dataTableSettings[tblId].aoColumns;
					//var totalColumn=0;
					//var totalWidth = datatable[0].clientWidth;//1192;
					//$.each(columns, function(i,v) {
					////console.log(v);
					//	if(v.bVisible){
					//		totalColumn++;
					//	}
					//});				
					//alert(totalColumn);
					var topThs = $("#"+tableId+" tHead tr");
					for(var m = 0; m < topThs.length; m++){
					
						for(var n = 0; n < topThs[m].children.length; n++){
							tableHeaderList[n] = topThs[m].children[n].textContent;
							//alert(topThs[m].children[n].style.textAlign);
							
						}*/
						/*htmlTable+= '<tr role="row">';
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
						*/
					//}
					 
						/*
						 var arr = [];
						var rows=5;
						var cols=3;
						  // Creates all lines:
						  for(var i=0; i < rows; i++){

							  // Creates an empty line
							  arr.push([]);

							  // Adds cols to the empty line:
							  arr[i].push( new Array(cols));

							  for(var j=0; j < cols; j++){
								// Initializes:
								arr[i][j] = j;
							  }
						  }
							
						arr = JSON.stringify(arr);
						alert(arr);
*/

/*
					var topTds = $("#"+tableId+" tr td");
					var dataList = [];
					var index=0;
					var i=0;
					for(var m = 0; m < topTds.length; m++){					
						if(i==0){
						// Creates an empty line
								  dataList.push([]);
						 // Adds cols to the empty line:
								  dataList[index].push( new Array(totalColumn));
						}	  
						
						dataList[index][i] = topTds[m].textContent;
						i++;
						if(i==totalColumn){
								index++;
								i = 0;
							}					
					}
	*/				
					/*var datatable =$('#'+tableId).dataTable();
					//var rows = datatable.dataTableSettings[0].nTBody.rows;
					var rows = datatable.dataTableSettings[tblId].nTBody.rows;
					//console.log(rows);
					//console.log(rows.length);
					//console.log(rows[0].children[0].colSpan);
					//console.log(rows[0].textContent);					
					//console.log(rows[1].cells);
					//console.log(rows[1].cells.length);
					
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
										
					tableHeaderList = JSON.stringify(tableHeaderList);
					dataList = JSON.stringify(dataList);
					dataColSpanList = JSON.stringify(dataColSpanList);
					console.log(dataList);
					console.log(dataColSpanList);
					
					$.ajax({
						url: baseUrl + 'report/excel_master_dynamic_column.php',
						type: 'post',
						data: {
							jBaseUrl: "<?php echo $jBaseUrl; ?>",
							lan: lan,
							reportSaveName : reportSaveName,
							reportHeaderList : reportHeaderList,
							tableHeaderList : tableHeaderList,
							dataList : dataList,
							dataColSpanList:dataColSpanList,
							chart : chart	
						},
						success: function(response) {
						window.open( baseUrl + 'report/media/'+reportSaveName+'.xlsx');
						}
					});	*/
					/**/	
				/*}
				else if(type == 'pdf'){		
					
					var datatable =$('#'+tableId).dataTable();	
					var columns = datatable.dataTableSettings[0].aoColumns;					
					var columns = datatable.dataTableSettings[tblId].aoColumns;
					var totalColumn=0;
					var totalWidth = datatable[0].clientWidth; //1192
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
							
							//alert(topThs[m].children[n].style.textAlign);
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
}*/



function print_function(type,tblId){
	var tableId = '';
	var reportHeaderName = '';
	if(tblId == 0){
		tableId = 'tbl-country-profile';
		reportHeaderName = TEXT['Parameter List'];
	}
	else if(tblId == 1){
		tableId = 'tblycregimenpatient';
		reportHeaderName = TEXT['Malaria Cases'];
	}
	else if(tblId == 2){
		tableId = 'tbl-yc-funding-requirement';
		reportHeaderName = TEXT['Funding Requirements (Expressed in Euro)'];
	}
	else if(tblId == 3){
		tableId = 'tbl-pledgedfundings';
		reportHeaderName = TEXT['Pledged Funding (Expressed in Euro)'];
	}

	var reportHeaderList = new Array();
	var chart = -1;
	
	//console.log($('#'+tableId).dataTable());
	
	
	//var reportHeaderName = TEXT['National Stock Summary Report'];
	reportHeaderList[0] = reportHeaderName;
	reportHeaderList[1] = $('#CountryName option[value='+$('#CountryName').val()+']').text()+' - ' + $('#item-group option[value='+$('#item-group').val()+']').text()+' - '+$('#year-list option[value='+$('#year-list').val()+']').text();
	
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
					var tableHeaderList = new Array();
					var dataList = new Array();					
					 
					var datatable =$('#'+tableId).dataTable();
					var rows = datatable.dataTableSettings[0].nTHead.rows;
					//var rows = datatable.dataTableSettings[tblId].nTHead.rows;
					
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
					
					var datatable =$('#'+tableId).dataTable();
					//var rows = datatable.dataTableSettings[0].nTBody.rows;
					var rows = datatable.dataTableSettings[tblId].nTBody.rows;
					
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
										
					tableHeaderList = JSON.stringify(tableHeaderList);
					dataList = JSON.stringify(dataList);
					dataColSpanList = JSON.stringify(dataColSpanList);
					console.log(dataList);
					console.log(dataColSpanList);
					
					
					$.ajax({
						url: baseUrl + 'report/print_master_dynamic_column.php',
						type: 'post',
						data: {
							jBaseUrl: "<?php echo $jBaseUrl; ?>",
							lan: lan,
							reportSaveName : reportSaveName,
							reportHeaderList : reportHeaderList,
							tableHeaderList : tableHeaderList,
							dataList : dataList,
							dataColSpanList:dataColSpanList,
							chart : chart	
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
					var columns = datatable.dataTableSettings[tblId].aoColumns;
					var totalColumn=0;
					var totalWidth = datatable[0].clientWidth; //1192
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
							
							//alert(topThs[m].children[n].style.textAlign);
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
/*
function pdf_function(){  
    pCountryName = $('#CountryName option[value='+$('#CountryName').val()+']').text();
   	$.ajax({
		url: baseUrl + 'report/r_ycprofile_view_pdf.php',
		type: 'post',
		data: {
			operation: 'generateCountryProfileReport',
            lan: lan, 
			//MosTypeId : gMosTypeId,	
            //MonthId: gMonthId,
           	Year: $('#year-list option[value=' + $('#year-list').val() + ']').text(),
            CountryId: $('#CountryName').val(),
            CountryName: pCountryName,
            RequirementYear: RequirementYear
            //FacilityId: gFacilityId,            			
           // FLevelId: gFacilityId == -11? -99 : facilityList[gFacilityId].FLevelId,
            //ItemGroupId: gItemGroupId
		},
		success: function(response) {
			if (response == 'Processing Error') {
				alert('No Record Found');
			} else {
				window.open(baseUrl + 'report/pdfslice/' + response);
                
			}
		}
	});			 
} 
*/
/*
function printfunction(){
	
	window.open("<?php echo $baseUrl; ?>report/printProcessing.php?jBaseUrl=<?php echo $jBaseUrl; ?>&lan=<?php echo $lan;?>
	&action=getCountryProfileParams&CountryId="+$('#CountryName').val()+"&Year="+$('#year-list').val()+"");			 
} 		
function printfunction1(){
	window.open("<?php echo $baseUrl; ?>report/ExcelProcessing.php?jBaseUrl=<?php echo $jBaseUrl; ?>&lan=<?php echo $lan;?>
	&action=getCountryProfileParams&CountryId="+$('#CountryName').val()+"&Year="+$('#year-list').val()+"");			  
} 

function printfunction2(){
	window.open("<?php echo $baseUrl; ?>report/printProcessing.php?jBaseUrl=<?php echo $jBaseUrl; ?>&lan=<?php echo $lan;?>
	&action=getYcRegimenPatient&CountryId="+$('#CountryName').val()+"&Year="+$('#year-list').val()+"");			 
} 		
function printfunction3(){
	window.open("<?php echo $baseUrl; ?>report/ExcelProcessing.php?jBaseUrl=<?php echo $jBaseUrl; ?>&lan=<?php echo $lan;?>
	&action=getYcRegimenPatient&CountryId="+$('#CountryName').val()+"&Year="+$('#year-list').val()+"");			  
} 		

function printfunction4(){
	window.open("<?php echo $baseUrl; ?>report/printProcessing.php?jBaseUrl=<?php echo $jBaseUrl; ?>&lan=<?php echo $lan;?>
	&action=getYcFundingSource&CountryId="+$('#CountryName').val()+"&Year="+$('#year-list').val()+"");			 
} 		
function printfunction5(){
	window.open("<?php echo $baseUrl; ?>report/ExcelProcessing.php?jBaseUrl=<?php echo $jBaseUrl; ?>&lan=<?php echo $lan;?>
	&action=getYcFundingSource&CountryId="+$('#CountryName').val()+"&Year="+$('#year-list').val()+"");			  
} 		

function printfunction6(){
	window.open("<?php echo $baseUrl; ?>report/printProcessing.php?jBaseUrl=<?php echo $jBaseUrl; ?>&lan=<?php echo $lan;?>
	&action=getYcPledgedFunding&CountryId="+$('#CountryName').val()
	+"&RequirementYear="+RequirementYear+"&Year="+$('#year-list').val()
    +"&CountryName="+$('#CountryName option[value='+$('#CountryName').val()+']').text());			 
} 		
function printfunction7(){
	window.open("<?php echo $baseUrl; ?>report/ExcelProcessing.php?jBaseUrl=<?php echo $jBaseUrl; ?>&lan=<?php echo $lan;?>
	&action=getYcPledgedFunding&CountryId="+$('#CountryName').val()
	+"&RequirementYear="+RequirementYear
	+"&Year="+$('#year-list').val()
    +"&CountryName="+$('#CountryName option[value='+$('#CountryName').val()+']').text());			  
} 		
*/
function printfunction7(){
	window.open("<?php echo $baseUrl; ?>report/ExcelProcessing.php?jBaseUrl=<?php echo $jBaseUrl; ?>&lan=<?php echo $lan;?>
	&action=getYcPledgedFunding&CountryId="+$('#CountryName').val()
	+"&RequirementYear="+RequirementYear+"&Year="+$('#year-list option[value='+$('#year-list').val()+']').text()
    +"&CountryName="+$('#CountryName option[value='+$('#CountryName').val()+']').text());			  
} 		

function excel_function_cprofile(){
	/*window.open("<?php echo $baseUrl; ?>report/t_ycprofile_view_excel.php?jBaseUrl=<?php echo $jBaseUrl; ?>&lan=<?php echo $lan;?>
	&CountryId="+$('#CountryName').val()
	+"&RequirementYear="+RequirementYear
	+"&Year="+$('#year-list option[value='+$('#year-list').val()+']').text()
	+"&CountryName="+$('#CountryName option[value='+$('#CountryName').val()+']').text());*/

	window.open("<?php echo $baseUrl; ?>report/t_ycprofile_view_excel.php?jBaseUrl=<?php echo $jBaseUrl; ?>&lan=<?php echo $lan;?>
	&action=getYcPledgedFunding&CountryId="+$('#CountryName').val()
	+"&RequirementYear="+RequirementYear
	+"&ItemGroupId="+$('#item-group').val()
	+"&year="+$('#year-list option[value=' + $('#year-list').val() + ']').text()
	+"&Year="+$('#year-list option[value='+$('#year-list').val()+']').text()
	+"&CountryName="+$('#CountryName option[value='+$('#CountryName').val()+']').text());
	
}
/*function excel_function_cprofile(){
	window.open("<?php echo $baseUrl; ?>report/t_ycprofile_view_excel.php?jBaseUrl=<?php echo $jBaseUrl; ?>&lan=<?php echo $lan;?>
	&CountryId="+$('#CountryName').val()
    +"&Year="+$('#Year').val()
	+"&ItemGroupId="+$('#item-group').val()
    +"&RequirementYear="+RequirementYear
    +"&CountryName="+$('#CountryName option[value='+$('#CountryName').val()+']').text());		  
}*/	
function excel_function_cprofile_parameter_list(){

	window.open("<?php echo $baseUrl; ?>report/t_country_profile_parameter_list_excel.php?jBaseUrl=<?php echo $jBaseUrl; ?>&lan=<?php echo $lan;?>
	&CountryId="+$('#CountryName').val()
    +"&Year="+$('#year-list option[value=' + $('#year-list').val() + ']').text()
	+"&ItemGroupId="+$('#item-group').val()
	+"&ItemGroupName="+$('#item-group option[value=' + $('#item-group').val() + ']').text()
    +"&RequirementYear="+RequirementYear
    +"&CountryName="+$('#CountryName option[value='+$('#CountryName').val()+']').text());		  
}
function excel_function_cprofile_funding_requirements(){

	window.open("<?php echo $baseUrl; ?>report/t_country_profile_funding_requirements_excel.php?jBaseUrl=<?php echo $jBaseUrl; ?>&lan=<?php echo $lan;?>
	&CountryId="+$('#CountryName').val()
    +"&Year="+$('#year-list option[value=' + $('#year-list').val() + ']').text()
	+"&ItemGroupId="+$('#item-group').val()
	+"&ItemGroupName="+$('#item-group option[value=' + $('#item-group').val() + ']').text()
    +"&RequirementYear="+RequirementYear
    +"&CountryName="+$('#CountryName option[value='+$('#CountryName').val()+']').text());		  
}
function excel_function_cprofile_malaria_cases(){

	window.open("<?php echo $baseUrl; ?>report/t_malaria_cases_excel.php?jBaseUrl=<?php echo $jBaseUrl; ?>&lan=<?php echo $lan;?>
	&CountryId="+$('#CountryName').val()
    +"&Year="+$('#year-list option[value=' + $('#year-list').val() + ']').text()
	+"&ItemGroupId="+$('#item-group').val()
	+"&ItemGroupName="+$('#item-group option[value=' + $('#item-group').val() + ']').text()
    +"&RequirementYear="+RequirementYear
    +"&CountryName="+$('#CountryName option[value='+$('#CountryName').val()+']').text());		  
}
</script>


<style>
.right-aln:nth-of-type(3){
	/*text-align:left !important;*/
}
.tbl_right{
	text-align:right !important;
}
#tblycregimenpatient .right-aln,#tblycregimenpatient .right-aln {
	text-align: right !important;
}
.table tbody > tr > td.column_bg{
	background-color:#F1F1F1 !important;
}
.table tbody > tr > td.groupTotal{		
	background-color: #FE9929 !important;
	font-size: 1.2em;
}
.table tbody > tr > td.supergroupTotal{		
	background-color: #50ABED !important;
	color:white;
	font-size: 1.2em;
}
.format{
	/*width:10%;*/
}
.table > thead > tr > th{
	vertical-align:top !important;
	width:auto !important;
	/*text-align:left !important;*/
}
.states {    
    fill: #FB7922;   
}
</style>
<!--
<link href="<?php echo $baseUrl; ?>css/custom.css" rel="stylesheet"/>
<link href="<?php echo $baseUrl; ?>media/datatable/css/jquery.dataTables_themeroller.css" rel="stylesheet">

<link href="<?php echo $baseUrl; ?>media/datatable/css/endless.min.css" rel="stylesheet">

<script src='<?php echo $jBaseUrl; ?>/templates/protostar/endless/js/parsley.min.js'></script>
<script src='<?php echo $jBaseUrl; ?>/templates/protostar/endless/js/jquery.dataTables.min.js'></script>
<script src='<?php echo $jBaseUrl; ?>/templates/protostar/endless/js/chosen.jquery.min.js'></script>
-->
<link href="<?php echo $baseUrl; ?>media/datatable-bootstrap/dataTables.bootstrap.css" rel="stylesheet"/>
<link href="<?php echo $baseUrl; ?>css/jquery.dataTables.custom.css" rel="stylesheet"/>
<script src='<?php echo $baseUrl; ?>media/datatable/js/jquery.dataTables.min.js'></script>
<script src='<?php echo $baseUrl; ?>media/datatable-bootstrap/dataTables.bootstrap.min.js'></script>


<script src='<?php echo $baseUrl; ?>maps/js/d3.v3.min.js'></script>
<script src='<?php echo $baseUrl; ?>maps/js/topojson.v0.min.js'></script>
<script src='<?php echo $baseUrl; ?>maps/js/queue.min.js'></script>
<script src='<?php echo $baseUrl; ?>maps/js/underscore-min.js'></script>
<link href="<?php echo $baseUrl; ?>maps/css/map-style.css" rel="stylesheet">

<script src='<?php echo $baseUrl; ?>/lib/fnc-lib.js'></script>
<link href="<?php echo $baseUrl; ?>t_ycprofile_view.css" rel="stylesheet">
<script src='<?php echo $baseUrl; ?>t_ycprofile_view_map.js'></script>
<script src='<?php echo $baseUrl; ?>t_ycprofile_view.js'></script>


<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_en.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_fr.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_switcher.js"></script>
