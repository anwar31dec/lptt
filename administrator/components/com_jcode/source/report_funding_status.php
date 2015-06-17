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
					
						<div class="panel-heading clearfix">
							<div class="row">
							
								<div class="col-md-6 col-sm-12 col-sx-12">
									<table id="month-year">
										<tbody>
											<tr>
												<td>&nbsp;&nbsp;</td><td valign="middle" align="left"><select class="form-control" id="year-list"></select></td>
											</tr>
										</tbody>
									</table>
								</div>	
								<div class="col-md-6 col-sm-12 col-sx-12">
									<div class="pull-right">
										<table id="nav-country">
											<tbody>
												<tr>
													<td><?php echo $TEXT['Country']; ?>&nbsp;:&nbsp;&nbsp;</td>
													<td>
														<select class="form-control" id="country-list"></select>
													</td>
													<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
													<td><?php echo $TEXT['Product Group']; ?>&nbsp;:&nbsp;&nbsp;</td>								
													<td>
														<select class="form-control" id="item-group"></select>
													</td>                                        
												</tr>
											</tbody>
										</table>
									</div>
								</div>
								
							</div>
						</div>
						<div class="panel-body">
							<div id="barchart-container">
							<div id="FundingStatusBarChart" width='100%'></div> 					
								<center>	
									<div class="panel-footer">
										<div id="barchartlegend" class="legend-80">
										</div>
									</div>	
								</center>	               
							</div>
						</div>
					</div>
					<div class="panel panel-default">
						<div class="panel-heading clearfix">
							<a class="panel-title"><?php echo $TEXT['Funding Status']; ?></a>
							<span class="pull-right">
								 <label>					
									<a id="PrintBTN" data-mce-href="#" class="but_print" href="javascript:void(0);" onclick="print_function()"><i data-mce-bootstrap="1" class="fa fa-print fa-lg">&nbsp;</i> <?php echo $TEXT['Print']; ?></a>
									<a id="PrintBTN1" data-mce-href="#" class="but_excel" href="javascript:void(0);" onclick="excel_function()"><i data-mce-bootstrap="1" class="fa fa-file-excel-o fa-lg">&nbsp;</i> <?php echo $TEXT['Excel']; ?></a>
									<a id="PDFBTN" data-mce-href="#" class="but_pdf" href="javascript:void(0);" onclick="pdf_function()"><i data-mce-bootstrap="1" class="fa fa-file-pdf-o fa-lg">&nbsp;</i> <?php echo $TEXT['PDF']; ?></a>
								</label>
							</span> 
						</div>
						<div class="panel-body">
							<div id="dt_example" class="table-responsive example_alt_pagination clearfix">
								<table class="table table-striped table-bordered display table-hover" id="tbl-funding-status">
									<thead>
										<tr>
											<th style="width:10%">SL.</th>
											<th style="width:10%"><?php echo $TEXT['Products']; ?></th>
											<th style="width:10%"><?php echo $TEXT['Category']; ?></th>
											<th style="width:10%"><?php echo $TEXT['Requirements (USD)']; ?></th>
											<th style="width:80%"><?php echo $TEXT['Committed (USD)']; ?></th>								
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

<script>

function pdf_function(){
		svgversion=$('#FundingStatusBarChart svg').attr('version');
		svgstyle=$('#FundingStatusBarChart svg').attr('style');
		svgxmlns=$('#FundingStatusBarChart svg').attr('xmlns');
		svgwidth=$('#FundingStatusBarChart svg').attr('width');
		svgheight=$('#FundingStatusBarChart svg').attr('height');
		svgHTML="<svg version='"+svgversion+"' style='"+svgstyle+"' xmlns='"+svgxmlns+"' width='"+svgwidth+"' height='"+svgheight+"'>"+$('#FundingStatusBarChart svg').html()+"</svg>";

		$.ajax({
		    type: "POST",
			url: baseUrl + "report/r_report_funding_status_pdf.php",
			async: false,
			datatype: "json",
			cache: true,
			data: {
				//html: $('#FundingStatusBarChart svg').parent().html(),
				html: svgHTML,
				alavel: $('#barchartlegend').html(),
				action: 'prepareFundingStatusReport',
                Year: $('#year-list').val(),
                Country: $('#country-list').val(),
				ItemGroupId:$('#item-group').val(),
				ItemGroup:$('#item-group option:selected').text()
			},
			success: function(response) {
			     pdf_generate();
			}
		});
}
function pdf_generate(){ 
    $.ajax({
		url: baseUrl + 'report/r_report_funding_status_pdf.php',
		type: 'post',
		data: {
			action: 'generateFundingStatusReport',
            lan: lan,
            CountryName: $('#country-list option:selected').text(),
           	Year: $('#year-list').val(),
            Country: $('#country-list').val(),
            ItemGroup: $('#item-group option:selected').text(),
			ItemGroupId: $('#item-group').val()
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
function print_function()
{
var reportSaveName='Funding Status';
svgversion=$('#FundingStatusBarChart svg').attr('version');
		svgstyle=$('#FundingStatusBarChart svg').attr('style');
		svgxmlns=$('#FundingStatusBarChart svg').attr('xmlns');
		svgwidth=$('#FundingStatusBarChart svg').attr('width');
		svgheight=$('#FundingStatusBarChart svg').attr('height');
		svgHTML="<svg version='"+svgversion+"' style='"+svgstyle+"' xmlns='"+svgxmlns+"' width='"+svgwidth+"' height='"+svgheight+"'>"+$('#FundingStatusBarChart svg').html()+"</svg>";
		
		var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth()+1; //January is 0!
		var yyyy = today.getFullYear();
		var hh = today.getHours();
		var min = today.getMinutes();
		var sec = today.getSeconds();
		today = dd+'_'+mm+'_'+yyyy+'_'+hh+'_'+min+'_'+sec;
		var reportSaveName = reportSaveName+'_'+today;// reportHeaderList[0].str.replace(/ /g, '_')+'_'+today;
		
	 $.ajax({
		    type: "POST",
			url: baseUrl + "report/chart_generate_svg.php",
			async: false,
			datatype: "json",
			cache: true,
			data: {
				baseUrl :baseUrl,
				svgName : reportSaveName,
				html: svgHTML, 
				//alavel: $('#barchartlegend').html(),
				htmlTable: '',
				chart: 1			
			},
			success: function(response) {
				window.open("<?php echo $baseUrl; ?>report/r_report_funding_status_print.php?jBaseUrl=<?php echo $jBaseUrl; ?>&lan=<?php echo $lan;?>
				&Year="+$('#year-list').val()+"&Country="+$('#country-list').val()
				+"&CountryName="+$('#country-list option[value='+$('#country-list').val()+']').text()
				+"&ItemGroup="+$('#item-group option[value='+$('#item-group').val()+']').text()
				+"&ItemGroupId="+$('#item-group').val()+"&reportSaveName="+reportSaveName);
			}
			
			});	
			 
 } 
function excel_function()
{
	window.open("<?php echo $baseUrl; ?>report/r_report_funding_status_excel.php?jBaseUrl=<?php echo $jBaseUrl; ?>&lan=<?php echo $lan;?>
	&Year="+$('#year-list').val()+"&Country="+$('#country-list').val()
	+"&CountryName="+$('#country-list option[value='+$('#country-list').val()+']').text()
	+"&ItemGroup="+$('#item-group option[value='+$('#item-group').val()+']').text()+"&ItemGroupId="+$('#item-group').val());		 
 } 
</script>
<style type="text/css">
.table tbody > tr > td.groupTotal{		
	background-color: #FE9929 !important;
	font-size: 1.2em;
}
.table tbody > tr > td.supergroupTotal{		
	background-color: #50ABED !important;
	color:white;
	font-size: 1.2em;
}
.panel-heading {
        padding: 10px 10px 10px 15px !important;
		background-color: #eeeeee !important;
    }
</style>
<script src="<?php echo $baseUrl; ?>lib/fnc-lib.js" type="text/javascript"></script>
<link href="<?php echo $baseUrl; ?>media/datatable-bootstrap/dataTables.bootstrap.css" rel="stylesheet"/>
<link href="<?php echo $baseUrl; ?>css/jquery.dataTables.custom.css" rel="stylesheet"/>
<script src='<?php echo $baseUrl; ?>media/datatable/js/jquery.dataTables.min.js'></script>
<script src='<?php echo $baseUrl; ?>media/highcharts/highcharts.js'></script>
<script src='<?php echo $baseUrl; ?>media/datatable-bootstrap/dataTables.bootstrap.min.js'></script>
<script src='<?php echo $baseUrl; ?>report_funding_status.js'></script>		

<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_en.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_fr.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_switcher.js"></script>