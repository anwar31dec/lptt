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
						<div class="row">
							<div class="col-md-12 col-sm-12 col-sx-12">
								<div class="panel panel-default">
									<center>
										<table>
											<tbody>
												<tr>
													<td><select class="form-control" id="country-list">
													<?php echo user_all_test();?>
													</select></td>
													<td>&nbsp;&nbsp;</td>
													<td><select class="form-control" id="item-group"></select></td>
													<td>&nbsp;&nbsp;</td>
													<td><select class="form-control" id="fundingSource-list">
													<option value="" selected="true"><?php echo $TEXT['All Funding Source'];?></option>
													</select></td>
													<td>&nbsp;&nbsp;</td>
													<td><select class="form-control" id="status-list">
													<option value="" selected="true"><?php echo $TEXT['All Status'];?></option>
													</select></td>
													<td>&nbsp;&nbsp;</td>
													<td><select class="form-control" id="OwnerType">
													</select></td>	
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
									<div class="btn-group">
										<button id="0" class="btn btn-default active" type="button" onclick="threeMonth()"><?php echo $TEXT['3 Months']; ?></button>
										<button id = "1" class="btn btn-default" type="button" onclick="sixMonth()"><?php echo $TEXT['6 Months']; ?></button>
										<button id = "2" class="btn btn-default" type="button" onclick="oneYear()"><?php echo $TEXT['1 Year']; ?></button>
										<button id = "3" class="btn btn-default" type="button" onclick="custom()"><?php echo $TEXT['Custom']; ?></button>
									</div>
								</center>		
							</div>						
						</div>
						
					</div>
				</div>
			</div>
		</div>
		
				
		<div class="row" id="custom-panel" style="display: none;">
			<div class="col-md-12 col-sm-12 col-sx-12">
				<div class="panel panel-default">
					<div class="panel-body">
						<center>				
							<div id="month-year-block">
								<table id="month-year">
									<tbody>
										<tr>
											<td width="" valign="middle" align="right">
											<button class="btn btn-info" type="button" id="left-arrow"><span class="fa fa-arrow-left fntC"></span></button></td>
											<td>&nbsp;&nbsp;</td><td><?php echo $TEXT['Start Month']; ?>&nbsp;:&nbsp;</td>
											<td>&nbsp;&nbsp;</td><td valign="middle" align="left"><select class="form-control" id="start-month-list"></select></td>
											<td>&nbsp;&nbsp;</td><td><?php echo $TEXT['Start Year']; ?>&nbsp;:&nbsp;</td>
											<td>&nbsp;&nbsp;</td><td valign="middle" align="left"><select class="form-control" id="start-year-list"></select></td>
											<td>&nbsp;&nbsp;</td><td><?php echo $TEXT['End Month']; ?>&nbsp;:&nbsp;</td>
											<td>&nbsp;&nbsp;</td><td valign="middle" align="left"><select class="form-control" id="end-month-list"></select></td>
											<td>&nbsp;&nbsp;</td><td><?php echo $TEXT['End Year']; ?>&nbsp;:&nbsp;</td>
											<td>&nbsp;&nbsp;</td><td valign="middle" align="left"><select class="form-control" id="end-year-list"></select></td>
											<td>&nbsp;&nbsp;</td><td width="" valign="middle" align="left">
											<button class="btn btn-info" type="button" id="right-arrow"><span class="fa fa-arrow-right fntC"></span></button></td>
										</tr>
									</tbody>
								</table>					
							</div>
						</center>
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
						<?php echo $TEXT['Shipment Report Data List']; ?>
						<span class="pull-right">
								<label>					
								<a id="PrintBTN" data-mce-href="#" class="but_print" href="javascript:void(0);" onclick="print_function()"><i data-mce-bootstrap="1" class="fa fa-print fa-lg">&nbsp;</i> <?php echo $TEXT['Print']; ?></a>
								<a id="PrintBTN1" data-mce-href="#" class="but_excel" href="javascript:void(0);" onclick="excel_function()"><i data-mce-bootstrap="1" class="fa fa-file-excel-o fa-lg">&nbsp;</i> <?php echo $TEXT['Excel']; ?></a>
								<a id="PDFBTN" data-mce-href="#" class="but_pdf" href="javascript:void(0);" onclick="pdf_function()"><i data-mce-bootstrap="1" class="fa fa-file-pdf-o fa-lg">&nbsp;</i> <?php echo $TEXT['PDF']; ?></a>
							</label>							   
						</span>
					</div>					
					
					<div class="panel-body">
						<div class="clearfix list-panel" id="tbl-pf">
							<table class="table table-striped table-bordered display table-hover" id="shipmentReportTable">
								<thead>
								<tr>
										<th style="text-align: center;">SL.</th>   
										<th><?php echo $TEXT['Product Name']; ?></th>
										<th><?php echo $TEXT['Funding Source']; ?></th>                   
										<th><?php echo $TEXT['Shipment Status']; ?></th>                       
										<th style="text-align: center;"><?php echo $TEXT['Shipment Date']; ?></th>
										<th style="text-align: right;"><?php echo $TEXT['Quantity']; ?></th>
										<th><?php echo $TEXT['Country']; ?></th>
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


<script>
function pdf_function(){
    
    var currentSearch = $('#shipmentReportTable_filter').find('input').val();
    
   	$.ajax({
		url: baseUrl + 'report/r_report_shipment_pdf.php',
		type: 'post',
		data: {
			operation: 'generateShipmentReport',
            lan: lan,
            CountryName: $("#country-list option:selected").text(),
            FundingSourceName: $("#fundingSource-list option:selected").text(),
            ASStatusName: $("#status-list option:selected").text(),
            ItemGroupName: $("#item-group option:selected").text(),
            OwnerTypeName: 	$("#OwnerType option:selected").text(),
            MonthNumber: MonthNumber,
            StartMonthId: $('#start-month-list').val(),
            EndMonthId: $('#end-month-list').val(),
            StartYearId: $('#start-year-list').val(),
            EndYearId: $('#end-year-list').val(),
            ACountryId: $('#country-list').val(),
           	AFundingSourceId: $('#fundingSource-list').val(),
            ASStatusId: $('#status-list').val(),
            ItemGroup: $('#item-group').val() ,
            OwnerType: $('#OwnerType').val(),
            sSearch: currentSearch             			
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
</script>

<style>
	.SL, .Action, .ShipmentStatus, .Date{
		text-align: center !important;
	}
    .Quantity, #Qty{
        text-align: right !important;
    }
   
    #panel_segment .panel-body{
        padding: 0px 0px 10px 0px;
    }
	.panel-heading {
        padding: 10px 10px 15px 15px !important;
		background-color: #eeeeee !important;
    }
</style>  
 


<script>
function print_function()
{  
var pfilter =  $("#shipmentReportTable_filter input").val();
    window.open("<?php echo $baseUrl; ?>report/t_shipment_reports_print.php?jBaseUrl=<?php echo $jBaseUrl; ?>&lan=<?php echo $lan;?>
	&ASStatusId="+$('#status-list').val()+"&ACountryId="+$('#country-list').val()
	+"&AFundingSourceId="+$('#fundingSource-list').val()+"&MonthNumber="+MonthNumber
	+"&ItemGroup="+$('#item-group').val()
	+"&OwnerType="+$('#OwnerType').val()
	+"&StartMonthId="+$('#start-month-list').val()+"&EndMonthId="+$('#end-month-list').val()
	+"&StartYearId="+$('#start-year-list').val()+"&EndYearId="+$('#end-year-list').val()
	+"&CountryName="+$('#country-list option[value='+$('#country-list').val()+']').text()
	+"&FundingSourceName="+$('#fundingSource-list option[value='+$('#fundingSource-list').val()+']').text()
	+"&ItemGroupName="+$('#item-group option[value='+$('#item-group').val()+']').text()
	+"&OwnerTypeName="+$('#OwnerType option[value='+$('#OwnerType').val()+']').text()
	+"&ASStatusName="+$('#status-list option[value='+$('#status-list').val()+']').text()
	+"&ShowSelected=true&sSearch="+pfilter+"");			 
 }   
 function excel_function()
{   var pfilter =  $("#shipmentReportTable_filter input").val();
	window.open("<?php echo $baseUrl; ?>report/t_shipment_reports_excel.php?jBaseUrl=<?php echo $jBaseUrl; ?>&lan=<?php echo $lan;?>
	&ASStatusId="+$('#status-list').val()+"&ACountryId="+$('#country-list').val()
	+"&AFundingSourceId="+$('#fundingSource-list').val()+"&MonthNumber="+MonthNumber
	+"&ItemGroup="+$('#item-group').val()
	+"&OwnerType="+$('#OwnerType').val()
	+"&StartMonthId="+$('#start-month-list').val()+"&EndMonthId="+$('#end-month-list').val()
	+"&StartYearId="+$('#start-year-list').val()+"&EndYearId="+$('#end-year-list').val()
	+"&CountryName="+$('#country-list option[value='+$('#country-list').val()+']').text()
	+"&FundingSourceName="+$('#fundingSource-list option[value='+$('#fundingSource-list').val()+']').text()
	+"&ItemGroupName="+$('#item-group option[value='+$('#item-group').val()+']').text()
	+"&OwnerTypeName="+$('#OwnerType option[value='+$('#OwnerType').val()+']').text()
	+"&ASStatusName="+$('#status-list option[value='+$('#status-list').val()+']').text()
    +"&ShowSelected=true&sSearch="+pfilter+"");					
 } 
</script>
<script src="<?php echo $baseUrl; ?>lib/fnc-lib.js" type="text/javascript"></script>
<link href="<?php echo $baseUrl; ?>media/datatable-bootstrap/dataTables.bootstrap.css" rel="stylesheet"/>
<link href="<?php echo $baseUrl; ?>css/jquery.dataTables.custom.css" rel="stylesheet"/>
<script src='<?php echo $baseUrl; ?>media/datatable/js/jquery.dataTables.min.js'></script>
<script src='<?php echo $baseUrl; ?>media/datatable-bootstrap/dataTables.bootstrap.min.js'></script>
<script src='<?php echo $baseUrl; ?>report_shipment.js'></script>

<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_en.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_fr.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_switcher.js"></script>