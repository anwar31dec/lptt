<?php
$user = JFactory::getUser();
$baseUrl = JURI::base() . 'administrator/components/com_jcode/source/';
$jBaseUrl = JURI::base();
//$jUserId = $user
$lang = JFactory::getLanguage();
$lan = $lang->getTag();
?>
<script>
	var baseUrl =  '<?php echo $baseUrl; ?>';
	var jUserId = <?php echo $user->id; ?>; ;
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
	<h3 class="no-margin"><?php echo $TEXT['National Level Patient And Stock Status']; ?></h3>	
    <span class="pull-right" >
                <button class="btn btn-info" type="button" id="PrintBTN" onclick="print_function()" > <?php echo $TEXT['Print']; ?> </button>&nbsp;
                <button class="btn btn-info" type="button" id="PrintBTN" onclick="excel_function()" > <?php echo $TEXT['Excel']; ?> </button>&nbsp;
    </span>    
        
</div>

<br />

<div class="row" id="root-id">
	<div class="col-md-12 col-sm-12 col-sx-12">
		<div class="panel panel-default">
			<!-- <div class="panel-heading clearfix">				
			</div> -->

			<div class="panel-body">
				<div class="clearfix list-panel" >
					<div id="content" class="ext-content">
						<div id="panel-params"></div>
						<div id="panelCombo"></div>
						<div id="panelMaster" style="padding:5px 10px; height:40px; background-color:#E4EBF6; color:#888888; font: 11px arial,tahoma,helvetica,sans-serif;">
							<!-- <div style="float:left;width: 150px">								
								<div id = "txtReportIdDiv" style="float:left;"></div>
								<div id = "txtSubmitStatusDiv" style="clear:left"></div>
							</div>
							<div style="float:left;width: 250px">								
								<div id = "txtCreatedByDiv" style="float:left;"></div>
								<div style="clear:left;"></div>								
								<div id = "txtCreatedDtDiv" style="float:left;"></div>
							</div>
							<div style="float:left;width: 250px">								
								<div id = "txtLastUpdateByDiv" style="float:left;"></div>
								<div style="clear:left;"></div>								
								<div id = "txtLastUpdateDtDiv" style="float:left;"></div>
							</div>
							<div style="float:left;width: 250px">								
								<div id = "txtLastSubmittedByDiv" style="float:left;"></div>
								<div style="clear:left;"></div>								
								<div id = "txtLastSubmittedDtDiv" style="float:left;"></div>
							</div>
							<div style="float:left;width: 250px">								
								<div id = "submitted-date" style="float:left;"></div>
								<div style="clear:left;"></div>								
								<div id = "accepted-date" style="float:left;"></div>
							</div> -->
							
							<div style="float:left;width: 150px">								
								<div id = "txtReportIdDiv" style="float:left;"></div>
								<div id = "txtSubmitStatusDiv" style="clear:left"></div>
							</div>
							<div style="float:left;width: 250px">								
								<div id = "created-date" style="line-height:2em;float:left;"></div>
								<div style="clear:left;"></div>								
								<div id = "submitted-date" style="line-height:2em;float:left;"></div>
							</div>
							<div style="float:left;width: 250px">								
								<div id = "accepted-date" style="line-height:2em;float:left;"></div>
								<div style="clear:left;"></div>								
								<div id = "published-date" style="line-height:2em;float:left;"></div>
							</div>
							
							
						</div>
						<div id="spaceDiv" style="height:10px; background-color:#E4EBF6"></div>
						<div id="tabArvdata" style="padding-bottom:2%"></div>
						<div id="panelSubmit"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>



<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl; ?>lib/extjs/resources/css/ext-all.css" />
<!-- 		<link rel="stylesheet" type="text/css" href="lib/extjs/examples/shared/examples.css" /> -->
<!-- <link rel="stylesheet" type="text/css" href="plugins/ux/css/GroupSummary.css" /> -->

<script type="text/javascript" src="<?php echo $baseUrl; ?>lib/extjs/adapter/ext/ext-base.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>lib/extjs/ext-all.js"></script>
<!-- <script type="text/javascript" src="http://extjs.cachefly.net/ext-3.3.1/ext-all.js"></script> -->
<script type="text/javascript" src="<?php echo $baseUrl; ?>lib/plugins/searchfield.js"></script>
<!-- 		<script type="text/javascript" src="lib/extjs/examples/shared/examples.js"></script> -->
<script type="text/javascript" src="<?php echo $baseUrl; ?>universal_function_lib.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>t_national_level_monthlystatus_ext_view.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>lib/plugins/js/Ext.ux.grid.Search.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>lib/plugins/ux/GroupSummary.js"></script>
<!-- <script type="text/javascript" src="plugins/ux/GridSummary.js "></script> -->
<!-- <link rel="stylesheet" type="text/css" href="buttons.css"/> -->
<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_en.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_fr.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_switcher.js"></script>

<script>
function print_function(){
	
window.open("<?php echo $baseUrl; ?>report/t_national_level_monthlystatus_ext_view_print.php?jBaseUrl=<?php echo $jBaseUrl; ?>&lan=<?php echo $lan;?>
&CountryId="+pCountryId+"&MonthId="+pMonthId+"&Year="+pYearId+"&ItemGroupId="+pItemGroupId
+"&CountryName="+vFacility+"&ItemGroupName="+vItem+"&MonthName="+vMonth
+"&ReportId="+pReportId);
}

function excel_function(){
	
window.open("<?php echo $baseUrl; ?>report/t_national_level_monthlystatus_ext_view_excel.php?jBaseUrl=<?php echo $jBaseUrl; ?>&lan=<?php echo $lan;?>
&CountryId="+pCountryId+"&MonthId="+pMonthId+"&Year="+pYearId+"&ItemGroupId="+pItemGroupId
+"&CountryName="+vFacility+"&ItemGroupName="+vItem+"&MonthName="+vMonth
+"&ReportId="+pReportId);
}
</script>

<style type=text/css>
	#fi-button-msg {
		border: 2px solid #ccc;
		padding: 5px 10px;
		background: #eee;
		margin: 5px;
		float: left;
	}
	#logbar {
		list-style-type: none;
	}
	#logbar li {
		padding: 0 0 0 5px;
	}
	#logbar li label {
		float: left;
		width: 300px;
		font-size: 12px;
		text-align: left;
		color: #2A3F55;
		font-weight: bold;
		line-height: 25px;
	}
	#logbar li a {
		float: left;
		width: 250px;
		font-size: 12px;
		text-align: right;
		color: #2A3F55;
		font-weight: bold;
	}
	#tabArvdata .arv-oblc {
		background-color: #BDC575;
	}
	#tabArvdata .arv-obla {
		background-color: #FFC0CB;
	}
	#tabArvdata .arv-cblc {
		background-color: #BDC575;
	}
	#tabArvdata .arv-cbla {
		background-color: #FFFFA6;
	}
	#tabArvdata .arv-uneual-less {
		background-color: #F1C6B3;
	}
	#tabArvdata .arv-uneual-greater {
		background-color: #BDC575;
	}
	#tabArvdata .arv-mos {
		background-color: #EBFFAC;
	}
	#tabArvdata .arv-amc {
		background-color: #FFD393;
	}
	#tabArvdata .arv-maxqty {
		background-color: #BDF271;
	}
	#tabArvdata .arv-orderqty {
		background-color: #8FBC8F;
	}
	#tabArvdata .arv-actualqty {
		background-color: #EEB4B4;
	}
	.price-fall {
		background-color: #8FBC8F;
	}
	.price-rise {
		background-color: #BDF271;
	}
	.clsinvalid {
		background-color: #FFA0A0;
	}
	
	/** {
		-moz-box-sizing: inherit !important;
	}
	aside {
		-moz-box-sizing: border-box !important;
	}*/

	select, textarea, input[type="text"], input[type="password"], input[type="datetime"], input[type="datetime-local"], input[type="date"], input[type="month"], input[type="time"], input[type="week"], input[type="number"], input[type="email"], input[type="url"], input[type="search"], input[type="tel"], input[type="color"], .uneditable-input {
		border-radius: 0;
		color: #555555;
		display: inline-block;
		padding: 0;
	}
	
	
	/*#item-group, #country-list{
		-moz-box-sizing: border-box !important;
	}*/
	
	/*#content{
		-moz-box-sizing: inherit;
	}*/
	
	/*#root-id *:not(.ext-content){
		-moz-box-sizing: inherit !important;
		background-color: red;
	}*/
	
	#btn-sub-acc-pub{
		font-size: 1.3em;
		margin-top: -24px;
	}
	
	.x-btn-noicon .x-btn-small .x-btn-text {
		height: 32px;
	}
	#tabArvdataId__tabItemPatientOverviewId span{
		height: 22px;
	}
	.ext-strict .x-small-editor .x-form-text {
	  height: 19px !important;
	}
	.ext-content label{
		display: block;
		margin-bottom:0;
	}
</style>
