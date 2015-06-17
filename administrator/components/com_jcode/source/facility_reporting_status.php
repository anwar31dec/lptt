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
<script type="text/javascript">
	var vLang = '<?php echo $vLang; ?>';
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
													<td><select class="form-control chzn-select" name="country-list" id="country-list"></select></td>
													
													<td>&nbsp;&nbsp;</td>
													<td><?php echo $TEXT['Region']; ?>&nbsp;:&nbsp;&nbsp;</td>
													<td><select class="form-control" id="Region-list"><?php echo user_all_test();?></select></td>
													
													 <td>&nbsp;&nbsp;</td>
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
							<div class="clearfix"><br></div>
							<div class="row">
								<div class="col-md-12 col-sm-12 col-sx-12">
									<center>
										<table id="nav-country">
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
													<td><span id="Total"></span></td>
													
													<td>&nbsp;&nbsp;</td>
													<td><?php echo $TEXT['Facility Level']; ?>:&nbsp;&nbsp;</td>
													<td><span id="Facility"></span></td>
													
													 <td>&nbsp;&nbsp;</td>
													<td><?php echo $TEXT['District Level']; ?>:&nbsp;&nbsp;</td>
													<td><span id="District"></span></td>
													
													 <td>&nbsp;&nbsp;</td>
													<td><?php echo $TEXT['Region Level']; ?>:&nbsp;&nbsp;</td>
													<td><span  id="Region"></span></td>
													
													 <td>&nbsp;&nbsp;</td>
													<td><?php echo $TEXT['ppm Central']; ?>:&nbsp;&nbsp;</td>
													<td><span id="Central"></span></td>
												</tr>
											</tbody>
										</table>
									<center>
								</div>       
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="clearfix"><br></div>			
			
			<div class="panel-body">	
				<div class="row">
					<div class="col-md-4 col-sm-12 col-sx-12">
						<div class="panel panel-default">
							<div class="panel-heading clearfix text-center">								
								<?php echo $TEXT['Reports Entered (%)']; ?>
							</div>
							<span id="chart-entered" class="chart center-block" data-percent="0"> <span id="percent-entered" class="percent"></span> </span>
						</div>
					</div>
					<div class="col-md-4 col-sm-12 col-sx-12">
						<div class="panel panel-default">
							<div class="panel-heading clearfix text-center">
								<?php echo $TEXT['Reports Submitted (%)']; ?>								
							</div>
							<span id="chart-submitted" class="chart center-block" data-percent="0"> <span id="percent-submitted" class="percent"></span> </span>
						</div>
					</div>
					<!--
					<div class="col-md-3 col-sm-12 col-sx-12">
						<div class="panel panel-default">
							<div class="panel-heading clearfix text-center">								
								<?php echo $TEXT['Reports Accepted (%)']; ?>
							</div>
							<span id="chart-accepted" class="chart center-block" data-percent="0"> <span id="percent-accepted" class="percent"></span> </span>
						</div>
					</div>
					-->
					<div class="col-md-4 col-sm-12 col-sx-12">
						<div class="panel panel-default">
							<div class="panel-heading clearfix text-center">
								<?php echo $TEXT['Reports Published (%)']; ?>
							</div>
							<span id="chart-published" class="chart center-block" data-percent="0"> <span id="percent-published" class="percent"></span> </span>
						</div>
					</div>
				</div>			    
			</div>
			<div class="clearfix"><br></div>	
			
			<div class="row">
			<div class="col-md-12 col-sm-12 col-sx-12">
				<div id="cparams-panel" class="panel panel-default">
					<div class="panel-heading clearfix">
						<?php echo $TEXT['Facility Reporting Status Data List']; ?>
						<span class="pull-right">
								<label>					
								<a id="PrintBTN" data-mce-href="#" class="but_print" href="javascript:void(0);" onclick="print_function()"><i data-mce-bootstrap="1" class="fa fa-print fa-lg">&nbsp;</i> <?php echo $TEXT['Print']; ?></a>
								<a id="PrintBTN1" data-mce-href="#" class="but_excel" href="javascript:void(0);" onclick="excel_function()"><i data-mce-bootstrap="1" class="fa fa-file-excel-o fa-lg">&nbsp;</i> <?php echo $TEXT['Excel']; ?></a>
								<a id="PDFBTN" data-mce-href="#" class="but_pdf" href="javascript:void(0);" onclick="pdf_function()"><i data-mce-bootstrap="1" class="fa fa-file-pdf-o fa-lg">&nbsp;</i> <?php echo $TEXT['PDF']; ?></a>
							</label>
						</span>
					</div>
					<div class="panel-body">
						<div class="clearfix list-panel" >
							<table id="tbl-facility-reporting-status" class="table table-striped table-bordered display table-hover" cellspacing="0">
								<thead>
									<tr>		
									<th width="5%">SL</th>							
									<th width="0%">FacilityId</th>								
									<th width="5%"><?php echo $TEXT['Facility Code']; ?></th>
									<th width="18%"><?php echo $TEXT['Facility Name']; ?></th>
									<th width="9%"><?php echo $TEXT['Entered']; ?></th>
									<th width="9%"><?php echo $TEXT['Entry Date']; ?></th>
									<th width="9%"><?php echo $TEXT['Submitted']; ?></th>
									<th width="9%"><?php echo $TEXT['Submitted Date']; ?></th>
									<th width="9%"><?php echo $TEXT['Accepted']; ?></th>
									<th width="9%"><?php echo $TEXT['Accepted Date']; ?></th>
									<th width="9%"><?php echo $TEXT['Published']; ?></th>
									<th width="9%"><?php echo $TEXT['Published Date']; ?></th>
									<th width="9%"><?php echo $TEXT['Facility Level']; ?></th>
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
    
    var currentSearch = $('#tbl-facility-reporting-status_filter').find('input').val();
    
   	$.ajax({
		url: baseUrl + 'report/r_facility_reporting_status_pdf.php',
		type: 'post',
		data: {
			operation: 'generateFacilityReport',
            lan: lan,	
            CountryName: $('#country-list option:selected').text(),
            MonthName: $('#month-list option:selected').text(),
            //ItemGroupName: $('#item-group-list option:selected').text(),
            RegionName: $('#Region-list option:selected').text(),
            DistrictName: $('#District-list option:selected').text(),
            OwnerTypeName: $('#OwnerType option:selected').text(),
            MonthId: $('#month-list').val(),
           	Year: $('#year-list').val(),
            CountryId: $('#country-list').val(),
            //ItemGroupId: $('#item-group-list').val(),
            RegionId: $('#Region-list').val(),
            OwnerTypeId: $('#OwnerType').val(),
            DistrictId: $('#District-list').val(),
            sSearch: currentSearch          			
		},
		success: function(response) {
		  
			if (response == 'Processing Error') {
				alert('Pdf genaration failed.');
			} else {
			console.log(response);
                //window.location =  baseUrl + 'report/pdfslice/' + response.trim();   
                    window.open( baseUrl + 'report/pdfslice/' + response.trim(), '_blank');                
			}
		}
	});			 
}
function print_function()
{
	var currentSearch = $('#tbl-facility-reporting-status_filter').find('input').val();
	window.open("<?php echo $baseUrl; ?>report/t_facility_reporting_status_print.php?jBaseUrl=<?php echo $jBaseUrl; ?>&lan=<?php echo $lan;?>
	&MonthId="+$('#month-list').val()+"&Year="+$('#year-list').val()+"&CountryId="+$('#country-list').val()
	+"&RegionId="+$('#Region-list').val()+"&DistrictId="+$('#District-list').val()+"&OwnerTypeId="+$('#OwnerType').val()
    +"&CountryName="+$('#country-list option[value='+$('#country-list').val()+']').text()
    +"&MonthName="+$('#month-list option[value='+$('#month-list').val()+']').text()
    +"&Year="+$('#year-list option[value='+$('#year-list').val()+']').text()
   // +"&ItemGroupName="+$('#item-group-list option[value='+$('#item-group-list').val()+']').text()
    +"&RegionName="+$('#Region-list option[value='+$('#Region-list').val()+']').text()
    +"&DistrictName="+$('#District-list option[value='+$('#District-list').val()+']').text()
    +"&OwnerTypeName="+$('#OwnerType option[value='+$('#OwnerType').val()+']').text()
     +"&sSearch="+currentSearch+"");			 
 }   
 function excel_function()
{
	var currentSearch = $('#tbl-facility-reporting-status_filter').find('input').val();
	window.open("<?php echo $baseUrl; ?>report/t_facility_reporting_status_excel.php?jBaseUrl=<?php echo $jBaseUrl; ?>&lan=<?php echo $lan;?>
	&MonthId="+$('#month-list').val()+"&Year="+$('#year-list').val()+"&CountryId="+$('#country-list').val()
    +"&RegionId="+$('#Region-list').val()+"&DistrictId="+$('#District-list').val()+"&OwnerTypeId="+$('#OwnerType').val()
    +"&CountryName="+$('#country-list option[value='+$('#country-list').val()+']').text()
    +"&MonthName="+$('#month-list option[value='+$('#month-list').val()+']').text()
    +"&Year="+$('#year-list option[value='+$('#year-list').val()+']').text()
    //+"&ItemGroupName="+$('#item-group-list option[value='+$('#item-group-list').val()+']').text()
    +"&RegionName="+$('#Region-list option[value='+$('#Region-list').val()+']').text()
    +"&DistrictName="+$('#District-list option[value='+$('#District-list').val()+']').text()
    +"&OwnerTypeName="+$('#OwnerType option[value='+$('#OwnerType').val()+']').text()
     +"&sSearch="+currentSearch+"");		 
 } 
		
 </script>
     


<style type="text/css">
	.chart{
	  position: relative;
	  display: inline-block;
	  width: 110px;
	  height: 110px;
	  margin-top: 10px;
	  margin-bottom: 10px;
	  text-align: center;
	}
	.chart canvas{
	  position: absolute;
	  top: 0;
	  left: 0;
	}
	.percent {
	  font-size: 1.5em;
	  display: inline-block;
	  line-height: 110px;	 
	  z-index: 2;
	}
	.percent:after {
	  content: '%';
	  margin-left: 0.1em;
	  font-size: 1em;
	}
	.center-block {
    display: block;
    margin-left: auto;
    margin-right: auto;
   }
   .text-center
	{
	    text-align: center !important;
	}
.panel {
    background-color: #fff;
    border: 1px solid transparent;
    border-radius: 4px;
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
    margin-bottom: 20px;
}
	
</style>
<link href="<?php echo $baseUrl; ?>media/datatable/css/jquery.dataTables_themeroller.css" rel="stylesheet">
<link href="<?php echo $baseUrl; ?>media/datatable/css/endless.min.css" rel="stylesheet">
<script src='<?php echo $baseUrl; ?>media/datatable-bootstrap/jquery.dataTables.min.js'></script>

<script src="<?php echo $baseUrl; ?>lib/fnc-lib.js" type="text/javascript"></script>
<link href="<?php echo $baseUrl; ?>media/datatable-bootstrap/dataTables.bootstrap.css" rel="stylesheet"/>
<link href="<?php echo $baseUrl; ?>css/jquery.dataTables.custom.css" rel="stylesheet"/>
<script src='<?php echo $baseUrl; ?>media/datatable/js/jquery.dataTables.min.js'></script>
<script src='<?php echo $baseUrl; ?>media/highcharts/highcharts.js'></script>
<script src='<?php echo $baseUrl; ?>media/datatable-bootstrap/dataTables.bootstrap.min.js'></script>


<script src="<?php echo $baseUrl; ?>/easy-pie-chart/jquery.easing.min.js"></script>
<script src="<?php echo $baseUrl; ?>/easy-pie-chart/jquery.easypiechart.min.js"></script>

<link href="<?php echo $baseUrl; ?>/css/custom.css" rel="stylesheet">
<link href="<?php echo $baseUrl; ?>/facility_reporting_status.css" rel="stylesheet">
<script src='<?php echo $baseUrl; ?>/facility_reporting_status.js'></script>

<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_en.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_fr.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_switcher.js"></script>