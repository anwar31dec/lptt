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


<script>
    var baseUrl = '<?php echo $baseUrl; ?>';
    var lan='<?php echo $lan;?>';

</script>

<script type="text/javascript">
	var vLang = '<?php echo $vLang; ?>';
</script>

<?php
$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());
mysql_query('SET CHARACTER SET utf8');

function getMOSbox(){

	$user = JFactory::getUser();
	$baseUrl = JURI::base() . 'administrator/components/com_jcode/source/';
	$jBaseUrl = JURI::base();
	$lang = JFactory::getLanguage();
	$lan = $lang->getTag();

 if($lan == 'en-GB'){
           $mosTypeName = 'MosTypeName';
		   $mos = 'MOS';
        }else{
            $mosTypeName = 'MosTypeNameFrench';
			$mos = 'MSD';
        }
    
    $sql = "SELECT $mosTypeName MosTypeName, MosLabel, ColorCode FROM t_mostype ORDER BY MosTypeId";
   	$result = mysql_query($sql);
	$total = mysql_num_rows($result);
    	
	$x = "<table style='margin: 0px auto;'><tr>";	
    $z = "</tr><tr>"; 
    $y = "</tr><tr>";   
	if($total>0){
		while($row=mysql_fetch_object($result)){
            $x.="<td><div style='width:100%;background-color:".$row->ColorCode.";'>&nbsp;</div></td>";
            $z.="<td>".$row->MosTypeName."</td>";
			$y.="<td> ".$mos.": ".$row->MosLabel."</td>";
		}                    
	}    
	$x = $x.$z.$y."</tr></table>";
    
	$x = str_replace("\n", '', $x);
	$x = str_replace("\r", '', $x);
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
													<?php echo user_all_test();?>
													</select>
												</td>
												<td>&nbsp;&nbsp;</td>
												
												<td><?php echo $TEXT['Product Group']; ?>&nbsp;:&nbsp;&nbsp;</td>
												<td>
												<select class="form-control" id="item-group"></select>
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
			
			<div class="panel panel-default">
			<div class="panel-body">
				<div id="barchart-container">
					<div id="bar-chart" width='100%'></div>
						<center>	
							<div class="panel-footer">
								<div id="barchartlegend" class="legend-80">
									<?php echo getMOSbox(); ?>
								</div>
							</div>	
						</center>			
					</div>
				</div>           
			</div>       
			<div id="cparams-panel" class="panel panel-default">
				<div class="panel-heading clearfix">
					<?php echo $TEXT['National Stock Summary List']; ?>
					<span class="pull-right">
							<label>					
							<a id="PrintBTN" data-mce-href="#" class="but_print" href="javascript:void(0);" onclick="print_function('print')"><i data-mce-bootstrap="1" class="fa fa-print fa-lg">&nbsp;</i> <?php echo $TEXT['Print']; ?></a>
							<a id="PrintBTN1" data-mce-href="#" class="but_excel" href="javascript:void(0);" onclick="print_function('excel')"><i data-mce-bootstrap="1" class="fa fa-file-excel-o fa-lg">&nbsp;</i> <?php echo $TEXT['Excel']; ?></a>
							<a id="PDFBTN" data-mce-href="#" class="but_pdf" href="javascript:void(0);" onclick="print_function('pdf')"><i data-mce-bootstrap="1" class="fa fa-file-pdf-o fa-lg">&nbsp;</i> <?php echo $TEXT['PDF']; ?></a>
						</label>
					</span>
				</div>
				<div class="panel-body">
					<div id="list-panel">
						<table  id="tbl-national-sum-products" class="table table-striped table-bordered display table-hover" cellspacing="0">
							<thead>
								<tr>
									<th style="width:10%">SL.</th>
									<th style="width:10%"><?php echo $TEXT['Products']; ?></th>
									<th style="width:10%"><?php echo $TEXT['Reported Consumption']; ?></th>
									<th style="width:10%"><?php echo $TEXT['Reported Closing Balance']; ?></th>
									<th style="width:80%"><?php echo $TEXT['AMC']; ?></th>
									<th style="width:80%"><?php echo $TEXT['MOS']; ?></th>
								</tr>
							</thead>
							<tbody>	</tbody>
						</table>			                   
					</div>
				</div>
			</div>
			    
		</div>		
	</div>
</div>
<script>


function print_function(type){

//console.log($('#tbl-national-sum-products').dataTable());

//var data=$('#tbl-national-sum-products').dataTable();
//console.log(data[0].clientWidth);
//console.log($('#barchartlegend').html());


	var tableId = 'tbl-national-sum-products';
	var reportSaveName = 'National_Stock_Summary'; //Not allow any type of special character of cahrtName
	var reportHeaderList = new Array();
	var dataAlignment = new Array();
	var cellWidth = new Array();
	var chart = 1;
	
	var reportHeaderName = TEXT['National Stock Summary Report'];
	reportHeaderList[0] = reportHeaderName;
	reportHeaderList[1] = $('#country-list option[value='+$('#country-list').val()+']').text()+' - '+ $('#item-group option[value='+$('#item-group').val()+']').text()+' - '+$('#report-by option[value='+$('#report-by').val()+']').text();

	dataAlignment = ["center","left","right","right","right"];
	cellWidth = ["10","82","25","13","13"];
	 
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
					var tableHeaderColWidthList = [];					
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
								  //tableHeaderColWidthList[r].push( new Array(rows[r].length));
						//}	  
						for(var c = 0; c < rows[r].cells.length; c++){	
							tableHeaderList[r][c] = rows[r].cells[c].textContent;//topTds[m].textContent;
							tableHeaderColSpanList[r][c] = rows[r].cells[c].colSpan;
							tableHeaderRowSpanList[r][c] = rows[r].cells[c].rowSpan;
							//tableHeaderColWidthList[r][c] = rows[r].cells[c].clientWidth;
							//console.log(rows[r].cells[c].clientWidth);
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
					//console.log(tableHeaderColWidthList);
					
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
							//console.log(topThs[m].children[n].clientWidth);
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
						},
						success: function(response) {
							window.open( baseUrl + 'report/pdfslice/' + response);
						}
					});					
				}			
			}
		});	
}

function print_function_15012015(type){

//console.log($('#tbl-national-sum-products').dataTable());

//var data=$('#tbl-national-sum-products').dataTable();
//console.log(data[0].clientWidth);



	var tableId = 'tbl-national-sum-products';
	//var htmlTable ='<table  class="table table-striped display" width="100%" border="0.5" style="margin:0 auto;"> '+ $("#"+tableId).html()+' </table>';
	var reportHeaderList = new Array();
	var chart = 1;
	
	var reportHeaderName = TEXT['National Stock Summary Report'];
	reportHeaderList[0] = reportHeaderName;
	reportHeaderList[1] = TEXT['Country']+ ': ' + $('#country-list option[value='+$('#country-list').val()+']').text()+',  '+TEXT['Product Group']+ ': ' + $('#item-group option[value='+$('#item-group').val()+']').text();
	reportHeaderList[2] = TEXT['Report By']+ ': ' + $('#report-by option[value='+$('#report-by').val()+']').text();
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
					var columns = datatable.dataTableSettings[0].aoColumns;
					//var columns = datatable.dataTableSettings[tblId].aoColumns;
					var totalColumn=0;
					var totalWidth = datatable[0].clientWidth;//1192;
					$.each(columns, function(i,v) {
					//console.log(v);
						if(v.bVisible){
							totalColumn++;
						}
					});				
					
					var topThs = $("#"+tableId+" tHead tr");
					//console.log(topThs);
					for(var m = 0; m < topThs.length; m++){
					
						for(var n = 0; n < topThs[m].children.length; n++){
							tableHeaderList[n] = topThs[m].children[n].textContent;
							//alert(topThs[m].children[n].style.textAlign);
							
						}
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
					}
					 
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
					var datatable =$('#'+tableId).dataTable();
					var rows = datatable.dataTableSettings[0].nTBody.rows;
					//var rows = datatable.dataTableSettings[tblId].nTBody.rows;
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
					});	
					/**/	
				}
				else if(type == 'pdf'){		
					
					var datatable =$('#'+tableId).dataTable();				
					var columns = datatable.dataTableSettings[0].aoColumns;
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
  
function print_function_back_06012015(type){
	var tableId = 'tbl-national-sum-products';
	//var htmlTable ='<table  class="table table-striped display" width="100%" border="0.5" style="margin:0 auto;"> '+ $("#"+tableId).html()+' </table>';
	var reportHeaderList = new Array();
	var chart = 1;
	
	var reportHeaderName = TEXT['National Stock Summary Report'];
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




function print_function_31_12_2014(type){
//var htmlString = $("#tbl-national-sum-products").html();
console.log($("#tbl-national-sum-products").dataTable());
//var htmlTable =$("#tbl-national-sum-products").html();//
var htmlTable ='<table  class="table table-striped display" width="100%" border="0.5" style="margin:0 auto;"> '+ $("#tbl-national-sum-products").html()+' </table>';
//alert(htmlTable);
/*
<?php
$file = fopen("D:/xampp/htdocs/ospsante/administrator/components/com_jcode/source/report/pdfslice/htmlTable.txt","w");
echo fwrite($file,'<table  class="table table-striped display" width="100%" border="0.5" style="margin:0 auto;"> <thead><tr role="row"><th aria-label="SL#: activate to sort column ascending" colspan="1" aria-controls="tbl-facility-sum-list" tabindex="0" role="columnheader" class="ui-state-default" rowspan="3" style="text-align: center; width: 75px;"><div class="DataTables_sort_wrapper">SL#<span class="DataTables_sort_icon css_right ui-icon ui-icon-carat-2-n-s"></span></div></th><th aria-label="Products: activate to sort column descending" aria-sort="ascending" colspan="1" aria-controls="tbl-facility-sum-list" tabindex="0" role="columnheader" rowspan="3" class="productname ui-state-default" style="text-align: center; width: 126px;"><div class="DataTables_sort_wrapper">Products<span class="DataTables_sort_icon css_right ui-icon ui-icon-triangle-1-n"></span></div></th><th aria-label="AMC: activate to sort column ascending" colspan="1" aria-controls="tbl-facility-sum-list" tabindex="0" role="columnheader" class="ui-state-default" rowspan="3" style="text-align: center; width: 84px;"><div class="DataTables_sort_wrapper">AMC<span class="DataTables_sort_icon css_right ui-icon ui-icon-carat-2-n-s"></span></div></th><th aria-label="Available Stock: activate to sort column ascending" colspan="1" aria-controls="tbl-facility-sum-list" tabindex="0" role="columnheader" class="ui-state-default" rowspan="3" style="text-align: center; width: 184px;"><div class="DataTables_sort_wrapper">Available Stock<span class="DataTables_sort_icon css_right ui-icon ui-icon-carat-2-n-s"></span></div></th><th aria-label="MOS(Available): activate to sort column ascending" colspan="1" aria-controls="tbl-facility-sum-list" tabindex="0" role="columnheader" class="ui-state-default" rowspan="3" style="text-align: center; width: 186px;"><div class="DataTables_sort_wrapper">MOS(Available)<span class="DataTables_sort_icon css_right ui-icon ui-icon-carat-2-n-s"></span></div></th><th rowspan="1" colspan="4" style="text-align:center;">Shipment Qty</th><th aria-label="Total MOS: activate to sort column ascending" colspan="1" aria-controls="tbl-facility-sum-list" tabindex="0" role="columnheader" class="ui-state-default" rowspan="3" style="text-align: center; width: 140px;"><div class="DataTables_sort_wrapper">Total MOS<span class="DataTables_sort_icon css_right ui-icon ui-icon-carat-2-n-s"></span></div></th></tr><tr role="row"><th rowspan="1" colspan="2" style="text-align:center;">GFATM</th><th rowspan="1" colspan="2" style="text-align:center;">Government</th></tr><tr role="row"><th aria-label="Qty: activate to sort column ascending" colspan="1" rowspan="1" aria-controls="tbl-facility-sum-list" tabindex="0" role="columnheader" class="ui-state-default" style="text-align: center; width: 74px;"><div class="DataTables_sort_wrapper">Qty<span class="DataTables_sort_icon css_right ui-icon ui-icon-carat-2-n-s"></span></div></th><th aria-label="MOS: activate to sort column ascending" colspan="1" rowspan="1" aria-controls="tbl-facility-sum-list" tabindex="0" role="columnheader" class="ui-state-default" style="text-align: center; width: 86px;"><div class="DataTables_sort_wrapper">MOS<span class="DataTables_sort_icon css_right ui-icon ui-icon-carat-2-n-s"></span></div></th><th aria-label="Qty: activate to sort column ascending" colspan="1" rowspan="1" aria-controls="tbl-facility-sum-list" tabindex="0" role="columnheader" class="ui-state-default" style="text-align: center; width: 74px;"><div class="DataTables_sort_wrapper">Qty<span class="DataTables_sort_icon css_right ui-icon ui-icon-carat-2-n-s"></span></div></th><th aria-label="MOS: activate to sort column ascending" colspan="1" rowspan="1" aria-controls="tbl-facility-sum-list" tabindex="0" role="columnheader" class="ui-state-default" style="text-align: center; width: 86px;"><div class="DataTables_sort_wrapper">MOS<span class="DataTables_sort_icon css_right ui-icon ui-icon-carat-2-n-s"></span></div></th></tr></thead><tbody aria-relevant="all" aria-live="polite" role="alert"><tr class="odd"><td class="">1</td><td class="">Arthémether + Luméfantrine /Plq de 12,</td><td class="">3,250</td><td class="">18,450</td><td class="">5.7</td><td class="">100,000</td><td class="">30.8</td><td class=""></td><td class=""></td><td class="">36.4</td></tr><tr class="even"><td class="">2</td><td class="">Arthémether + Luméfantrine /Plq de 18,</td><td class="">4,300</td><td class="">25,800</td><td class="">6.0</td><td class=""></td><td class=""></td><td class=""></td><td class=""></td><td class="">6.0</td></tr><tr class="odd"><td class="">3</td><td class="">Arthémether + Luméfantrine /Plq de 24,</td><td class="">3,200</td><td class="">67,500</td><td class="">21.1</td><td class=""></td><td class=""></td><td class=""></td><td class=""></td><td class="">21.1</td></tr><tr class="even"><td class="">4</td><td class="">Arthémether + Luméfantrine /Plq de 6,</td><td class="">1,200</td><td class="">49,400</td><td class="">41.2</td><td class=""></td><td class=""></td><td class=""></td><td class=""></td><td class="">41.2</td></tr><tr class="odd"><td class="">5</td><td class="">AS/AQ 100mg/270mg</td><td class="">2,100</td><td class="">91,400</td><td class="">43.5</td><td class=""></td><td class=""></td><td class=""></td><td class=""></td><td class="">43.5</td></tr><tr class="even"><td class="">6</td><td class="">AS/AQ 25mg/67.5mg</td><td class="">1,500</td><td class="">52,100</td><td class="">34.7</td><td class=""></td><td class=""></td><td class=""></td><td class=""></td><td class="">34.7</td></tr><tr class="odd"><td class="">7</td><td class="">AS/AQ 50mg/135mg</td><td class="">900</td><td class="">29,800</td><td class="">33.1</td><td class=""></td><td class=""></td><td class=""></td><td class=""></td><td class="">33.1</td></tr><tr class="even"><td class="">8</td><td class="">Atesunate </td><td class="">3,100</td><td class="">59,200</td><td class="">19.1</td><td class=""></td><td class=""></td><td class=""></td><td class=""></td><td class="">19.1</td></tr><tr class="odd"><td class="">9</td><td class="">Nets</td><td class="">4,300</td><td class="">66,700</td><td class="">15.5</td><td class=""></td><td class=""></td><td class=""></td><td class=""></td><td class="">15.5</td></tr><tr class="even"><td class="">10</td><td class="">Quinine 200mg 2ml</td><td class="">3,800</td><td class="">99,600</td><td class="">26.2</td><td class=""></td><td class=""></td><td class="">35,000</td><td class="">9.2</td><td class="">35.4</td></tr><tr class="odd"><td class="">11</td><td class="">Quinine 300mg</td><td class="">4,300</td><td class="">63,400</td><td class="">14.7</td><td class=""></td><td class=""></td><td class=""></td><td class=""></td><td class="">14.7</td></tr><tr class="even"><td class="">12</td><td class="">Quinine 400mg 4ml</td><td class="">1,950</td><td class="">71,450</td><td class="">36.6</td><td class=""></td><td class=""></td><td class=""></td><td class=""></td><td class="">36.6</td></tr><tr class="odd"><td class="">13</td><td class="">RDTs</td><td class="">1,050</td><td class="">64,450</td><td class="">61.4</td><td class=""></td><td class=""></td><td class=""></td><td class=""></td><td class="">61.4</td></tr><tr class="even"><td class="">14</td><td class="">SP</td><td class="">4,100</td><td class="">42,400</td><td class="">10.3</td><td class=""></td><td class=""></td><td class=""></td><td class=""></td><td class="">10.3</td></tr><tr class="odd"><td class="">15</td><td class="">SP+Amodiaquine 500md/25md/150mg</td><td class="">5,600</td><td class="">67,800</td><td class="">12.1</td><td class=""></td><td class=""></td><td class=""></td><td class=""></td><td class="">12.1</td></tr></tbody> </table>');
fclose($file);
?>
*/

//alert(htmlTable.length);
//alert(htmlTable.length);
//alert(htmlTable.length);
//alert(htmlTable);
//console.log($('#tbl-national-sum-products').dataTable());

	//var currentSearch = $('#tbl-national-sum-products_filter').find('input').val();
	var reportHeaderList = new Array();
	var reportHtmlTable = new Array();
	//var tableHeaderList = new Array();
	//var tableHeaderWidth = new Array();
	//var dataType = new Array();
	//var sqlParameterList = new Array();
	//var groupBySqlIndex = -1;
	var chart = 1;
	//var columns = $('#tbl-national-sum-products').dataTable().dataTableSettings[0].aoColumns;
	//$.each(columns, function(i,v) {
	//	if(v.bVisible){
	//		tableHeaderList.push(v.sTitle);
	//		tableHeaderWidth.push(v.sWidth);
	//		dataType.push(v.sType);
	//	}
	//});
	
	//var tableHtmlLength =htmlTable.length;
	////alert(htmlTable);
	//reportHtmlTable[0] = htmlTable.substring(0,3000);
	//reportHtmlTable[1] = htmlTable.substring(3001,6000);
	//reportHtmlTable[2] = htmlTable.substring(6001,7682);
	//var i=0;
	//while(i <= tableHtmlLength){
	//	reportHtmlTable[i] = htmlTable.substring();
	//i=i+3000;
	//}
	//for(i=0; i<=tableHtmlLength; i=i+3000){
	//
	//}
	//str.substring(1, 4); reportHtmlTable
	
	var reportHeaderName = 'National Stock Pipeline Information Report';
	reportHeaderList[0] = reportHeaderName;
	//reportHeaderList[1] = 'Country Name: ' + $('#country-list option[value='+$('#country-list').val()+']').text()+', Product Group: ' + $('#item-group option[value='+$('#item-group').val()+']').text();
	//reportHeaderList[2] = 'Month: ' + $('#month-list option[value='+$('#month-list').val()+']').text()+', Year: ' + $('#year-list option[value='+$('#year-list').val()+']').text();
    		
	//sqlParameterList[0]= (($('#item-group').val() == '') ? 0 : $('#item-group').val());
	//sqlParameterList[1]= (($('#month-list').val() == '') ? 0 : $('#month-list').val());
//	sqlParameterList[2]= (($('#year-list').val() == '') ? 0 : $('#year-list').val());
	//sqlParameterList[3] =(($('#country-list').val() == '') ? 0 : $('#country-list').val());
	//sqlParameterList[4]= (($('#report-by').val() == '') ? 0 : $('#report-by').val());
	
	reportHeaderList = JSON.stringify(reportHeaderList);
	reportHtmlTable = JSON.stringify(reportHtmlTable);
	//dataType = JSON.stringify(dataType);
	//tableHeaderList = JSON.stringify(tableHeaderList);
	//tableHeaderWidth = JSON.stringify(tableHeaderWidth);
	//sqlParameterList = JSON.stringify(sqlParameterList);
	
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
	if(chart == 1){
	 //var chart = $('#bar-chart').highcharts();
	 $.ajax({
		    type: "POST",
			url: baseUrl + "report/chart_generate_svg.php",
			async: false,
			datatype: "json",
			cache: true,
			data: {
				jBaseUrl :baseUrl,
				svgName : reportSaveName,//'National_Stock_Summary_Report_21_12_2014_01_01',
				html: $('#bar-chart svg').parent().html(), 
				alavel: $('#barchartlegend').html(),
				htmlTable: htmlTable
				
			},
			success: function(response) {
			//console.log(response);
			//if(response)
			//alert('hi rubel');
			if(type == 'print'){
				window.open("<?php echo $baseUrl;?>report/print_master_dynamic_column.php?jBaseUrl=<?php echo $jBaseUrl; ?>"
				+"&lan=<?php echo $lan;?>"
				+"&reportSaveName="+reportSaveName 
				+"&reportHeaderList="+reportHeaderList
				+"&chart=" + chart
				);			
			}
			else if(type == 'pdf1'){
				window.open("<?php echo $baseUrl;?>report/print_master_dynamic_column.php?jBaseUrl=<?php echo $jBaseUrl; ?>"
				+"&lan=<?php echo $lan;?>"
				+"&reportSaveName="+reportSaveName 
				+"&reportHeaderList="+reportHeaderList
				+"&chart=" + chart
				);
			
			}
			
			/*
			switch (type)
			{
				case 'print' :
					print_function(reportSaveName,reportHeaderList,chart);
				break;
				case 'pdf' :
					test();
				break;
				case 'excel' :
					test();
				break;
				default:
				allert(' ');
				break;
				
			}
			*/
			
			}
		});		
	}
	
  if(type == 'print'){
  //console.log(reportHeaderList);
  var x=10;
  //alert(htmlTable.length);
		/*window.open("<?php echo $baseUrl;?>report/print_master_dynamic_column.php?jBaseUrl=<?php echo $jBaseUrl; ?>"
		+"&lan=<?php echo $lan;?>"
		+"&reportSaveName="+reportSaveName 
		+"&reportHeaderList="+reportHeaderList
		+"&chart=" + chart
		);
		
		*/
		//+"&reportHtmlTable=" + reportHtmlTable
//var myWindow = window.open("", "MsgWindow", "width=200, height=100");
//myWindow.document.write("<p>This is 'MsgWindow'. I am 200px wide and 100px tall!</p>");

//myWindow=window.open('','',"_blank",'width=200,height=100')
//myWindow.document.write("<p>This is 'myWindow'</p>")
//myWindow.focus();

//myWindow = window.open("data:text/html," + encodeURIComponent(data),"_blank", "width=200,height=100");

	/*				   
		$.ajax({
			url: baseUrl + 'report/print_master_dynamic_column.php',
			type: 'post',
			data: {
				jBaseUrl: "<?php echo $jBaseUrl; ?>",
				lan: lan,
				reportSaveName : reportSaveName,
				reportHeaderList: reportHeaderList,
				chart : chart,
				htmlTable : htmlTable				
			},
			success: function(response) {
			
		window.open("<?php echo $baseUrl;?>report/print_master_dynamic_column.php");	
		
		*/
			//console.log(response);
			//window.open(response)
	//		document.write(response);
				//if (response == 'Processing Error') {
				//	alert('No Record Found.');
				//} else {
			//		window.open(response);
					
			//	}
	//		}
	//	});
	
/*
		window.open("<?php echo $baseUrl;?>report/print_master_dynamic_column.php?action=getNationalStockSummary"
		+"&jBaseUrl=<?php	echo $jBaseUrl; ?>"
		+"&lan=<?php echo $lan;?>"
		+"&reportSaveName="+reportSaveName 
		+"&reportHeaderList="+reportHeaderList
		+"&chart=" + chart
		+"&htmlTable=" + htmlTable
		);
		*/
		/*

$jBaseUrl = $_REQUEST['jBaseUrl'];
$reportSaveName = $_REQUEST['reportSaveName'];
$reportHeaderList = json_decode($_REQUEST['reportHeaderList'], true );
array_unshift($reportHeaderList,'Health Commodity Dashboard');

$htmlTable = $_REQUEST['htmlTable'];
$lan = $_REQUEST['lan'];
$chart = $_REQUEST['chart'];
*/


		/*window.open("<?php echo $baseUrl;?>report/print_master_dynamic_column.php?action=getNationalStockSummary"
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
		+"&groupBySqlIndex=" + groupBySqlIndex
		+"&chart=" + chart
		+"&htmlTable=" + htmlTable
		);	*/
  }
  else if(type == 'pdf'){
  
  
  // oTable = $('#tbl-national-sum-products').dataTable();

  //    var secondCellArray=[];
  //    $.each( oTable.fnGetData(), function(i, row){
 //         secondCellArray.push( row);
 //   })

 //    console.log( secondCellArray)
  
 //  var cells = new Array();
//$('#tbl-national-sum-products tr td').each(function(){
//	cells.push($(this).html());
	
//});
//console.log(cells);
  
  
  ////Create datatable
//var trs = $('#tbl-national-sum-products').dataTable();

////Get all rows from the trs table object.
//var rows = trs.$("td");
//console.log(rows);

//console.log($('#tbl-national-sum-products').dataTable());
//var tableObj = $("#tbl-national-sum-products").dataTable();
//console.log($(tableObj.clientWidth);

var columns = $('#tbl-national-sum-products').dataTable().dataTableSettings[0].aoColumns;
//	console.log(columns.length);
	var totalColumn=0;
	var totalWidth = 1192;
	//console.log(columns);
	$.each(columns, function(i,v) {
		if(v.bVisible){
			//tableHeaderList.push(v.sTitle);
			//tableHeaderWidth.push(v.sWidth);
			totalColumn++;
			//totalWidth+= v.sWidth;
			console.log(v.sWidth);
		}
	});
	
	
//var test = $("#tbl-national-sum-products").html();
//console.log(test);




var htmlTable ='<table width="100%" border="0.5" style="margin:0 auto;"><thead><tr role="row">';

var topThs = $("#tbl-national-sum-products th");

for(var m = 0; m < topThs.length; m++){
var tmpWidth = Math.round((topThs[m].clientWidth*100)/totalWidth);
htmlTable+='<th colspan="1" rowspan="1" role="columnheader" class="'+topThs[m].className+'" style="width: '+tmpWidth+'%">'+topThs[m].textContent+'</th>';
//<th colspan="1" rowspan="1" role="columnheader" class="'+sorting_disabled+'" style="width: 35%;">Products</th>
//<th colspan="1" rowspan="1" role="columnheader" class="sorting_disabled right-aln" style="width: 19%;">Reported Closing Balance</th>
//<th colspan="1" rowspan="1" role="columnheader" class="sorting_disabled right-aln" style="width: 19%;">Average Monthly Consumption</th>
//<th colspan="1" rowspan="1" role="columnheader" class="sorting_disabled right-aln" style="width: 19%;">MOS</th>
//</tr></thead>



//console.log(tmpWidth);
//htmlTable+='<td width="'+tmpWidth+'%" class="'+topTds[m].className+'"'+'>'+topTds[m].textContent+'</td>';
//console.log(topTds[m], "client: ", topTds[m].clientWidth, "offset ", topTds[m].offsetWidth, "scroll ", topTds[m].scrollWidth,topTds[m].textContent);
	
}

htmlTable+='</tr></thead><tbody>';

//console.log(htmlTable);




//console.log(totalWidth);	


var topTds = $("#tbl-national-sum-products tr td");
var i=0;

for(var m = 0; m < topTds.length; m++){
i++
if(i==1){
	htmlTable+='<tr>';
}
var tmpWidth = Math.round((topTds[m].clientWidth*100)/totalWidth);

htmlTable+='<td width="'+tmpWidth+'%" class="'+topTds[m].className+'"'+'>'+topTds[m].textContent+'</td>';
//console.log(topTds[m], "client: ", topTds[m].clientWidth, "offset ", topTds[m].offsetWidth, "scroll ", topTds[m].scrollWidth,topTds[m].textContent);

if(i==totalColumn){
	htmlTable+='</tr>';
	i = 0;
}
	
	
}
htmlTable+='</tbody></table>';



console.log(htmlTable);
//var topTds = $("#tbl-national-sum-products tr td");

//console.log(topTds.length);
//for(var m = 0; m < topTds.length; m++){
//    console.log(topTds[m], "client: ", topTds[m].clientWidth, "offset ", topTds[m].offsetWidth, "scroll ", topTds[m].scrollWidth,topTds[m].textContent);
//}
 // //alert(htmlTable);
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
				//if (response == 'Processing Error') {
				//	alert('No Record Found.');
				//} else {
					window.open( baseUrl + 'report/pdfslice/' + response);
					
			//	}
			}
		});
	

}

	
}   



function excel_function(){
	var pMonthName = $("#month-list option:selected").text();
	var pYear = $("#year-list option:selected").text();
	var pItemName = $("#item-list option:selected").text();
	var pCountryName = $("#country-list option:selected").text();
	var pItemGroupName = $("#item-group option:selected").text();
	
	window.open("<?php echo $baseUrl; ?>report/r_report_national_summary_excel.php?jBaseUrl=<?php echo $jBaseUrl; ?>&lan=<?php echo $lan;?>
	&Country="+$('#country-list').val()+"&ItemGroupId="+$('#item-group').val()
	+"&Month="+$('#month-list').val()+"&Year="+$('#year-list').val()
	+"&MonthName=" + pMonthName
	+"&Year=" + pYear
	+"&CountryName=" + pCountryName
	+"&ItemGroupName=" + pItemGroupName);			  
} 		
</script>
<script src="<?php echo $baseUrl; ?>lib/fnc-lib.js" type="text/javascript"></script>
<link href="<?php echo $baseUrl; ?>media/datatable-bootstrap/dataTables.bootstrap.css" rel="stylesheet"/>
<link href="<?php echo $baseUrl; ?>css/jquery.dataTables.custom.css" rel="stylesheet"/>
<script src='<?php echo $baseUrl; ?>media/datatable/js/jquery.dataTables.min.js'></script>
<script src='<?php echo $baseUrl; ?>media/highcharts/highcharts.js'></script>
<script src='<?php echo $baseUrl; ?>media/datatable-bootstrap/dataTables.bootstrap.min.js'></script>

<script src='<?php echo $baseUrl; ?>report_national_summary.js'></script>

<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_en.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_fr.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_switcher.js"></script>