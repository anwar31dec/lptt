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

<div class="page-title">	
	<h3 class="no-margin"><?php echo $TEXT['Number of Patients by Product']; ?></h3>
	
</div>

<br />

<div class="row">
	<div class="col-md-12 col-sm-12 col-sx-12">
		<div class="panel panel-default">       
			<div class="panel-heading clearfix">
				<div class="row">
                    <div class="col-md-6 col-sm-12 col-sx-12">
						<table id="month-year">
							<tbody>
								<tr>
									<td valign="middle" align="right">
									<button class="btn btn-info" type="button" id="left-arrow"><span class="glyphicon icon-arrow-left"> </span></button></td>
									<td>&nbsp;&nbsp;</td><td valign="middle" align="left"><select class="form-control" id="month-list"></select></td>
									<td>&nbsp;&nbsp;</td><td valign="middle" align="left"><select class="form-control" id="year-list"></select></td>
									<td>&nbsp;&nbsp;</td><td width="" valign="middle" align="left">
									<button class="btn btn-info" type="button" id="right-arrow"><span class="glyphicon icon-arrow-right"></span></button></td>
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
										<td><select class="form-control" id="country-list"></select></td>
                                        <td>&nbsp;&nbsp;</td>
                                        <td><?php echo $TEXT['Product Group']; ?>&nbsp;:&nbsp;&nbsp;</td>
										<td><select class="form-control" id="item-group-list"></select></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>                   
				</div>
			</div>
            
		</div>
    </div>
</div> 

<div class="row">
	<div class="col-md-12 col-sm-12 col-sx-12">
		<div class="panel panel-default">
			<div class="panel-heading clearfix">
                <?php echo $TEXT['Number of Patients by Product']; ?>
                <span class="pull-right">
                    <button class="btn btn-info" type="button" id="PDFBTN" onclick="pdf_function()" > <?php echo $TEXT['PDF']; ?> </button>
                    <button class="btn btn-info" type="button" id="PrintBTN" onclick="print_function()" > <?php echo $TEXT['Print']; ?> </button>&nbsp;
                    <button class="btn btn-info" type="button" id="PrintBTN1" onclick="excel_function()" > <?php echo $TEXT['Excel']; ?> </button>
                  </span>
			</div>
			<div class="panel-body">
				<div class="clearfix list-panel" id="tbl-pf">
					<table class="table table-hover table-striped" id="numberpatientTable">
						<thead></thead>
						<thead>
							<tr>								
								<th style="width:5%">SL#</th>
								<th style="width:25%;"><?php echo $TEXT['Product Name']; ?></th>
								<th style="width:10%; text-align: right;"><?php echo $TEXT['Total Patients']; ?></th>
								<th style="width:10%; text-align: right;"><?php echo $TEXT['Available Stock']; ?></th>
								<th style="width:10%; text-align: right;"><?php echo $TEXT['MOS(Available)']; ?></th>
								<th style="width:10%; text-align: right;"><?php echo $TEXT['Stock on Order']; ?></th>
								<th style="width:10%; text-align: right;"><?php echo $TEXT['MOS(Ordered)']; ?></th>
								<th style="width:10%; text-align: right;"><?php echo $TEXT['Total MOS']; ?></th>
								<th style="width:10%; text-align: right;"><?php echo $TEXT['Projected Date']; ?></th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<style>

.TotalPatients, .AMC, .ClStock, .StockOnOrder,.StockOnOrderMOS,.TotalMOS,.ProjectedDate{
    text-align: right !important;
}

</style>
<script>
function pdf_function(){
    var pMonthName = $("#month-list option:selected").text();
	var pCountryName = $("#country-list option:selected").text();
    //var pServiceTypeName = $("#servicetype-list option:selected").text();
	//var pYear = $("#year-list option:selected").text();
    
   	$.ajax({
		url: baseUrl + 'report/r_number_patients_product_pdf.php',
		type: 'post',
		data: {
			operation: 'generateNumberPatientReport',
            lan: lan,  	
        	CountryName: pCountryName,
            MonthName: pMonthName,
            MonthId: $('#month-list').val(),
           	YearId: $('#year-list').val(),
            CountryId: $('#country-list').val(),
            ItemGroupId: $('#item-group-list').val()                     			
		},
		success: function(response) {
			if (response == 'Processing Error') {
				alert('Pdf genaration failed.');
			} else {
				window.location = baseUrl + 'report/pdfslice/' + response;
                
			}
		}
	});			 
}
function print_function()
{
	window.open("<?php echo $baseUrl; ?>report/t_number_of_patients_by_product_print.php?jBaseUrl=<?php echo $jBaseUrl; ?>&lan=<?php echo $lan;?>
	&MonthId="+$('#month-list').val()+"&Year="+$('#year-list').val()+"&CountryId="+$('#country-list').val()+"&ItemGroupId="+$('#item-group-list').val()
    +"&CountryName="+$('#country-list option[value='+$('#country-list').val()+']').text()
    +"&MonthName="+$('#month-list option[value='+$('#month-list').val()+']').text()
    +"&Year="+$('#year-list option[value='+$('#year-list').val()+']').text()
    +"&ItemGroupName="+$('#item-group-list option[value='+$('#item-group-list').val()+']').text());			 
 }   
 function excel_function()
{
	window.open("<?php echo $baseUrl; ?>report/t_number_of_patients_by_product_excel.php?jBaseUrl=<?php echo $jBaseUrl; ?>&lan=<?php echo $lan;?>
	&MonthId="+$('#month-list').val()+"&Year="+$('#year-list').val()+"&CountryId="+$('#country-list').val()+"&ItemGroupId="+$('#item-group-list').val()
    +"&CountryName="+$('#country-list option[value='+$('#country-list').val()+']').text()
    +"&MonthName="+$('#month-list option[value='+$('#month-list').val()+']').text()
    +"&Year="+$('#year-list option[value='+$('#year-list').val()+']').text()
    +"&ItemGroupName="+$('#item-group-list option[value='+$('#item-group-list').val()+']').text());			
 } 
		

     

</script>

<link href="<?php echo $jBaseUrl; ?>/templates/protostar/endless/css/jquery.dataTables_themeroller.css" rel="stylesheet"/>
<link href="<?php echo $jBaseUrl; ?>/templates/protostar/endless/css/endless.min.css" rel="stylesheet"/>
<link href="<?php echo $baseUrl; ?>css/custom.css" rel="stylesheet"/>

<script src="<?php echo $baseUrl; ?>lib/fnc-lib.js" type="text/javascript"></script>
<script src='<?php echo $jBaseUrl; ?>/templates/protostar/endless/js/jquery.dataTables.min.js'></script>
<script src="<?php echo $baseUrl; ?>lib/highcharts/highcharts.js" type="text/javascript"></script>
<script src='<?php echo $baseUrl; ?>number_patients_product.js'></script>

