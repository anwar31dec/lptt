<?php
$user = JFactory::getUser();
$baseUrl = JURI::base() . 'administrator/components/com_jcode/source/';
$jBaseUrl = JURI::base();
//print_r($user->groups);
$jUserId = $user->username;
$lang = JFactory::getLanguage();
$lan = $lang->getTag();
?>
<script>
	var baseUrl =  '<?php echo $baseUrl; ?>';
	var jBaseUrl =  '<?php echo $jBaseUrl; ?>';
	var jUserId = '<?php echo $user->username; ?>';
	var lan = '<?php echo $lan;?>'; 
	var jUserGroups = JSON.parse('<?php echo  json_encode($user->groups); ?>');
		
	//console.log(jUserGroups);
	
	var ENTRY_ADMIN = 7;
	var ENTRY_MANAGER = 12;
	var ENTRY_OPERATOR = 15;
	
	//console.log(jUserGroups);
	
	jUserGroups[ENTRY_ADMIN] = jUserGroups[ENTRY_ADMIN] == undefined? 0 : jUserGroups[ENTRY_ADMIN];
	jUserGroups[ENTRY_OPERATOR] = jUserGroups[ENTRY_OPERATOR] == undefined? 0 : jUserGroups[ENTRY_OPERATOR];	   
	jUserGroups[ENTRY_MANAGER] = jUserGroups[ENTRY_MANAGER] == undefined? 0 : jUserGroups[ENTRY_MANAGER];	
	//console.log(jUserGroups);
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

<script type="text/javascript">
	var vLang = '<?php echo $vLang; ?>';	
</script>

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
														
							<div style="float:left;width: 150px">								
								<div id = "txtReportIdDiv" style="float:left;"></div>
								<div id = "txtSubmitStatusDiv" style="clear:left"></div>
							</div>
							<div style="float:left;width: 250px">								
								<div id = "created-by" style="line-height:2em;float:left;"></div>
								<div style="clear:left;"></div>								
								<div id = "created-date" style="line-height:2em;float:left;"></div>
							</div>
							<div style="float:left;width: 250px">								
								<div id = "updated-by" style="line-height:2em;float:left;"></div>
								<div style="clear:left;"></div>								
								<div id = "updated-date" style="line-height:2em;float:left;"></div>
							</div>
							<div style="float:left;width: 250px">								
								<div id = "submitted-by" style="line-height:2em;float:left;"></div>
								<div style="clear:left;"></div>								
								<div id = "submitted-date" style="line-height:2em;float:left;"></div>
							</div>
							<div style="float:left;width: 250px">								
								<div id = "published-by" style="line-height:2em;float:left;"></div>
								<div style="clear:left;"></div>								
								<div id = "published-date" style="line-height:2em;float:left;"></div>
							</div>							
							
						</div>
						<div id="spaceDiv" style="height:10px; background-color:#E4EBF6"></div>
						<div style="clear: both;"></div>
						<div style="float: left;width:100%;height:100%">
							<div id="grid-facility" style="float: left;width: 20%;"></div>
							<div id="tabArvdata" style="padding-bottom:2%; float: left;width:80%;"></div>
						</div>
						<div style="clear: both;"></div>
						<div id="panelSubmit"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>



<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl; ?>lib/extjs/resources/css/ext-all.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl; ?>lib/plugins/ux/css/GridSummary.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl; ?>lib/plugins/ux/css/GroupSummary.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl; ?>lib/plugins/ux/css/ColumnHeaderGroup.css" />


<script type="text/javascript" src="<?php echo $baseUrl; ?>lib/extjs/adapter/ext/ext-base.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>lib/extjs/ext-all-debug.js"></script>
<!-- <script type="text/javascript" src="http://extjs.cachefly.net/ext-3.3.1/ext-all.js"></script> -->
<script type="text/javascript" src="<?php echo $baseUrl; ?>lib/plugins/searchfield.js"></script>
<!-- 		<script type="text/javascript" src="lib/extjs/examples/shared/examples.js"></script> -->
<script type="text/javascript" src="<?php echo $baseUrl; ?>universal_function_lib.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>t_facility_level_monthlystatus_ext_debug.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>lib/plugins/js/Ext.ux.grid.Search.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>lib/plugins/ux/GridSummary.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>lib/plugins/ux/GroupSummary.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>lib/plugins/ux/ColumnHeaderGroup.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>lib/plugins/js/cellRenderer.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_en.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_fr.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_switcher.js"></script>


<script>

/*function print_function(){

 	window.open("<?php echo $baseUrl;?>report/t_facility_data_entry_print.php?jBaseUrl=<?php echo $jBaseUrl; ?>");	  		 
} */

/*function print_function(){

	//var currentSearch = $('#RegimenTable_filter').find('input').val();
    //currentSearch=currentSearch.replaceAll("+", "|");	
 	var pItemGroupName = $("#ItemGroupId option:selected").val();
 	window.open("<?php //echo $baseUrl;?>report/t_facility_data_entry_print.php?jBaseUrl=<?php //echo $jBaseUrl; ?>
	&facilityId=" + pFacilityId
    +"&pCountryId=" + pCountryId
	+"&monthId=" + pMonthId
	+"&year=" + pYearId
    +"&itemGroupId=" + pItemGroupName);	  		 
}*/

/*function excel_function(){

 	window.open("<?php echo $baseUrl;?>report/t_facility_data_entry_excel.php?jBaseUrl=<?php echo $jBaseUrl; ?>");	  		 
}*/ 

/*function excel_function(){
	var currentSearch = $('#RegimenTable_filter').find('input').val();
	currentSearch=currentSearch.replaceAll("+", "|");	
    var pItemGroupName = $("#ItemGroupId option:selected").text();
 	window.open("<?php //echo $baseUrl;?>report/t_facility_data_entry_excel.php?jBaseUrl=<?php //echo $jBaseUrl; ?>
	&ItemGroupId="+$('#ItemGroupId').val()
    +"&ItemGroupName=" + pItemGroupName
    +"&sSearch=" + currentSearch);		 	
}*/

function pdf_function(){
   var pMonthName = $("#month-list option:selected").text();
	var pItemGroupName = $("#item-group option:selected").text();
    var pRegionName = $("#region-list option:selected").text();
    		
   	$.ajax({
		url: baseUrl + 'report/t_facility_data_entry_pdf.php',
		type: 'post',
		data: {
			action: 'FacilityDataEntryReportPDF',
        	MonthName: pMonthName,
        	ItemGroupName: pItemGroupName
           	
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
/*
function pdf_function(){
    var pMonthName = $("#month-list option:selected").text();
	var pItemGroupName = $("#item-group option:selected").text();
    var pRegionName = $("#region-list option:selected").text();
    		
   	$.ajax({
		url: baseUrl + 'report/t_facility_data_entry_pdf.php',
		type: 'post',
		data: {
			action: 'generateNationalAdjustmentReport',
        	MonthName: pMonthName,
        	RegionName: pRegionName,
        	ItemGroupName: pItemGroupName,
            pBKeyItem: pBKeyItem,
            ItemGroup: $('#item-group').val(),
            Year: $('#year-list').val(),
            Month: $('#month-list').val(),
            RegionId: $('#region-list').val(),
            CountryId: $('#country-list').val()
           	
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
*/
</script> 

<style type=text/css>
	.row {
	  margin-left: 10px;
	  margin-right: 10px;
	  margin-top: 0;
	}
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
	
	select, textarea, input[type="text"], input[type="password"], input[type="datetime"], input[type="datetime-local"], input[type="date"], input[type="month"], input[type="time"], input[type="week"], input[type="number"], input[type="email"], input[type="url"], input[type="search"], input[type="tel"], input[type="color"], .uneditable-input {
		border-radius: 0;
		color: #555555;
		display: inline-block;
		padding: 0;
	}
		
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
	.multilineColumn .x-grid3-cell-inner, .x-grid3-hd-inner {
    	white-space: normal !important;
	}
	.x-action-col-cell img.NewEntry {
	    background-image: url("<?php echo $baseUrl ?>images/details_open.png");
	    height: 16px;
	    width: 16px;
	}
	.x-action-col-cell img.EditEntry {
	    background-image: url("<?php echo $baseUrl ?>images/reported.png");
	    height: 16px;
	    width: 16px;
	}
	.x-action-col-cell img.DeleteReocord {
	    background-image: url("<?php echo $baseUrl ?>images/i_drop.png");
	    height: 16px;
	    width: 16px;
	}
	.x-action-col-cell img.Trans {
	    background-image: url("<?php echo $baseUrl ?>images/i_trans.png");
	    height: 16px;
	    width: 16px;
	}
	.x-action-col-cell img.Submit-S {
	    background-image: url("<?php echo $baseUrl ?>images/submit_s.png");
	    height: 16px;
	    width: 16px;
	}
	.x-action-col-cell img.Submit-A {
	    background-image: url("<?php echo $baseUrl ?>images/submit_a.png");
	    height: 16px;
	    width: 16px;
	}
	.x-action-col-cell img.Submit-P {
	    background-image: url("<?php echo $baseUrl ?>images/submit_p.png");
	    height: 16px;
	    width: 16px;
	}
	.x-form-twin-triggers{
		display: none;
	}
	
	.col-xs-1, .col-sm-1, .col-md-1, .col-lg-1, .col-xs-2, .col-sm-2, .col-md-2, .col-lg-2, .col-xs-3, .col-sm-3, .col-md-3, .col-lg-3, .col-xs-4, .col-sm-4, .col-md-4, .col-lg-4, .col-xs-5, .col-sm-5, .col-md-5, .col-lg-5, .col-xs-6, .col-sm-6, .col-md-6, .col-lg-6, .col-xs-7, .col-sm-7, .col-md-7, .col-lg-7, .col-xs-8, .col-sm-8, .col-md-8, .col-lg-8, .col-xs-9, .col-sm-9, .col-md-9, .col-lg-9, .col-xs-10, .col-sm-10, .col-md-10, .col-lg-10, .col-xs-11, .col-sm-11, .col-md-11, .col-lg-11, .col-xs-12, .col-sm-12, .col-md-12, .col-lg-12 {
	  min-height: 1px;
	  padding-left: 0;
	  padding-right: 0;
	  position: relative;
	}
	
	.panel-body {
	  padding: 0;
	}
	.padding-md {
	  padding: 0 !important;
	}
	#panelCombo select,  #panelCombo input[type="text"]{
	  margin-bottom: 0;
	}
	#grid-facility input[type="text"]{	 
	  margin-bottom: 0;
	}
	
	.page_title2{
		padding: 10px 0 10px;
	}
	
	#adult-regimens-id .x-form-field, #patient-overview-id .x-form-field, #gridArvDataId .x-form-field{
		 margin-top: -6px;
	}
</style>
