<?php 
$user = JFactory::getUser();
$baseUrl = JURI::base() . 'administrator/components/com_jcode/source/';
$jBaseUrl = JURI::base();
$lang = JFactory::getLanguage();
$lan = $lang->getTag();
?>

<script>
var baseUrl =  '<?php echo $baseUrl; ?>';
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
	<h3 class="no-margin"><?php echo $TEXT['Country Profile Entry']; ?></h3>
	<span><?php echo $TEXT['Welcome to WARP EWS Dashboard']; ?></span>
    <span class="pull-right" id="Btn">
               	    <button class="btn btn-info" type="button" id="PrintBTN" onclick="printfunction()" > <?php echo $TEXT['Print']; ?> </button>
                    <button class="btn btn-info" type="button" id="PrintBTN" onclick="printfunction1()" > <?php echo $TEXT['Excel']; ?> </button>
                </span>
</div>

<br />
<br />
<div class="row">
	<div class="col-md-8 col-sm-12 col-sx-12">
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="center-block" id="month-year-block" >
					<table id="month-year">
						<tbody>
							<tr>

								<td>&nbsp;&nbsp;</td><td valign="middle" align="left"><select class="form-control" name="Year" id="Year"></select></select></td>

							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-4 col-sm-12 col-sx-12">
		<div class="panel panel-default">
			<div class="panel-body">
				<table id="month-year1" class="pull-right">
					<tbody>
						<tr>
							<td><?php echo $TEXT['Select Country']; ?>:&nbsp;</td><td valign="middle" align="left">
							<select class="form-control chzn-select" name="CountryName" id="CountryName" onchange="countryOnchange(this.value)">
								<option value=""><?php echo $TEXT['Country']; ?></option>
							</select>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="panel panel-default" >
	<div class="panel-body">
		<div class="clearfix list-panel country-form-wizard" >
			<div class="panel panel-default">
				<div class="form-horizontal no-margin form-border" id="country_form_wizard" novalidate>
					<div class="panel-heading">
						<?php echo $TEXT['Country Profile Wizard']; ?>
					</div>
					<div class="panel-tab">
						<ul class="wizard-steps wizard-demo" id="wizardDemo1">
							<li class="active">
								<a href="#wizardContent1" data-toggle="tab" onClick="onFirstTab()" ><?php echo $TEXT['Basic Information']; ?></a>
							</li>
							<li>
								<a href="#wizardContent2" data-toggle="tab" onClick="onSecondTab()"><?php echo $TEXT['Regimens/Patients']; ?></a>
							</li>
							<li>
								<a href="#wizardContent3" data-toggle="tab" onClick="onThirdTab()"><?php echo $TEXT['Funding Requirements']; ?></a>
							</li>
							<li>
								<a href="#wizardContent4" data-toggle="tab" onClick="onFourthTab()"><?php echo $TEXT['Pledged Funding']; ?></a>
							</li>
						</ul>
					</div>

					<div class="panel-body" id="table-div">
						<div class="tab-content">
							<div class="tab-pane fade in active" id="wizardContent1">								
								<!-- <a href="javascript:void(0);" class="btn btn-warning btn-sm" onClick="onClearWizardInformation(1)" style="float:right;"><?php echo $TEXT['Clear Basic Information']; ?></a> -->
								<table class="table table-striped" id="profileTable" >
									<thead>
										<tr>
											<th><?php echo $TEXT['Profile Id']; ?></th>
											<th style="text-align: center;">SL#</th>
											<th><?php echo $TEXT['Parameter Name']; ?></th>
											<th><?php echo $TEXT['Value']; ?></th>
											<th><?php echo $TEXT['Param Id']; ?></th>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
							<div class="tab-pane fade" id="wizardContent2">								
								<!-- <a href="javascript:void(0);" class="btn btn-warning btn-sm" onClick="onClearWizardInformation(2)" style="float:right;"><?php echo $TEXT['Clear Regimens/Patients']; ?></a> -->
								<p class='clearfix' style="padding:3px;">&nbsp;</p>
								<table class="table table-hover table-striped" id="tbl-yc-regimen-patient">
									<thead>
										<tr>
											<th>SL</th>
											<th>YearlyRegPatientId</th>
											<th>Regimen</th>
											<th>Formulation</th>
											<th>Patients</th>											
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
							<div class="tab-pane fade" id="wizardContent3">
								<!-- <a href="javascript:void(0);" class="btn btn-warning btn-sm" onClick="onClearWizardInformation(3)" style="float:right;"><?php echo $TEXT['Clear Funding Requirements']; ?></a> -->								
								<p class='clearfix' style="padding:3px;">&nbsp;</p>
								<table class="table table-hover table-striped" id="tbl-yc-funding-requirement">
									<thead>
										<tr>
											<th>SL</th>
											<th>FundingReqId</th>
											<th>Product</th>											
											<th>Formulation</th>
											<th><span class="cYear">2013</span></th>
											<th><span class="nYear">2014</span></th>
											<th><span class="nnYear">2015</span></th>
											<th>Total</th>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
							<div class="tab-pane fade" id="wizardContent4">
								<!-- <a href="javascript:void(0);" class="btn btn-warning btn-sm" onClick="onClearWizardInformation(4)" style="float:right;">Clear Pledged Funding</a> -->
								<p class='clearfix' style="padding:3px;">&nbsp;</p>
								<div class="btn-group" style="float:right;">
									<button class="btn btn-default pf" onClick="onRequirementYear(1)" type="button">2013</button>
									<button class="btn btn-default pf" onClick="onRequirementYear(2)" type="button">2013</button>
									<button class="btn btn-default pf" onClick="onRequirementYear(3)" type="button">2013</button>
								</div>
								<!--
								<a href="javascript:void(0);" class="btn btn- btn-sm pf" onClick="onRequirementYear(3)" style="float:right;"><span class="nnYear">2013</span></a>
								<a href="javascript:void(0);" class="btn btn- btn-sm pf" onClick="onRequirementYear(2)" style="float:right;margin-right:10px;"><span class="nYear">2013</span></a>								
								<a href="javascript:void(0);" class="btn btn- btn-sm pf" onClick="onRequirementYear(1)" style="float:right;margin-right:10px;"><span class="cYear">2013</span></a>
								-->
								<p class='clearfix' style="padding:3px;">&nbsp;</p>
								<div id="yc_pledged_funding"></div>
							</div>
						</div>
					</div>
					<div class="panel-footer clearfix">
						<div class="pull-left">
							<button class="btn btn-success btn-sm disabled" id="prevStep1" disabled>
								<?php echo $TEXT['Previous']; ?>
							</button>
							<button type="submit" class="btn btn-sm btn-success" id="nextStep1">
								<?php echo $TEXT['Next']; ?>
							</button>
						</div>

						<div class="pull-right" style="width:30%">
							<div class="progress progress-striped active m-top-sm m-bottom-none">
								<div class="progress-bar progress-bar-success" id="wizardProgress" style="width:25%;"></div>
							</div>
						</div>
					</div>
				</div>
			</div><!-- /panel -->

		</div>
	</div>
</div>

<style>
	.SL, .Value {
		text-align: center !important;
	}
	
	.datacell, .right-aln {
		text-align: right !important;
	}
	.datacell.yc_11, .datacell.yc_12 {
		text-align: left;
	}
	input[type="checkbox"], input[type="radio"]{
		opacity:1 !important;
	}
	.multiselect{
		float:left;
	}
	
</style>

<script>
// $('#profileTable input:text').click(function() {
    // var row = $(this).closest('th');
// 
    // row.find('input:text').attr('disabled', true);
//    
// });
//$('#editbox').attr('disabled',true);
function printfunction(){
	window.open("<?php echo $baseUrl; ?>report/printProcessing.php?jBaseUrl=<?php echo $jBaseUrl; ?>&lan=<?php echo $lan;?>&action=getYcProfileData&RequirementYear=1&country=1&year=2014");			 
} 		
function printfunction1(){
	window.open("<?php echo $baseUrl; ?>report/ExcelProcessing.php?jBaseUrl=<?php echo $jBaseUrl; ?>&lan=<?php echo $lan;?>&action=getYcProfileData&RequirementYear=1&country=1&year=2014");			  
} 		
</script>

<link href="<?php echo $baseUrl; ?>css/custom.css" rel="stylesheet"/>
<link href="<?php echo $jBaseUrl; ?>/templates/protostar/endless/css/jquery.dataTables_themeroller.css" rel="stylesheet"/>
<link href="<?php echo $jBaseUrl; ?>/templates/protostar/endless/css/endless.min.css" rel="stylesheet"/>
<link href="<?php echo $jBaseUrl; ?>/templates/protostar/endless/css/chosen/chosen.min.css" rel="stylesheet"/>
<link href="<?php echo $baseUrl; ?>lib/handsontable/dist/jquery.handsontable.css" rel="stylesheet"/>

<script src='<?php echo $jBaseUrl; ?>/templates/protostar/endless/js/parsley.min.js'></script>
<script src='<?php echo $jBaseUrl; ?>/templates/protostar/endless/js/jquery.dataTables.min.js'></script>
<script src='<?php echo $jBaseUrl; ?>/templates/protostar/endless/js/chosen.jquery.min.js'></script>
<script src='<?php echo $baseUrl; ?>lib/handsontable/dist/jquery.handsontable.full.js'></script>
<script src='<?php echo $baseUrl; ?>t_ycprofile_viewentry.js'></script>

