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

<script type="text/javascript">
    var vLang = '<?php echo $vLang; ?>';
</script>

<div class="row"> 
	<div class="col-md-12">
		<div class="nav-data">
			<div class="row columns">				
				<div class="col-md-4 col-padding">					
					<div class="tbl-header1" id="itemTable_length1">
						<label><?php echo $TEXT['Facility Level']; ?>
							<select class="form-control" id="facility-level-list"></select>
		                </label>
					</div>
				</div>
				<div class="col-md-4 col-padding">					
					<div class="tbl-header1" id="itemTable_length1">
						<label><?php echo $TEXT['Country']; ?>
							<select class="form-control" id="country-list"></select>
		                </label>
					</div>
				</div>
				<div class="col-md-4 col-padding">
					<div class="tbl-header1 pull-right">
						<label>					
							<a id="PrintBTN" data-mce-href="#" class="but_print" href="javascript:void(0);" onclick="print_function()"><i data-mce-bootstrap="1" class="fa fa-print fa-lg">&nbsp;</i> <?php echo $TEXT['Print']; ?></a>
							<a id="PrintBTN1" data-mce-href="#" class="but_excel" href="javascript:void(0);" onclick="excel_function()"><i data-mce-bootstrap="1" class="fa fa-file-excel-o fa-lg">&nbsp;</i> <?php echo $TEXT['Excel']; ?></a>
						</label>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row"> 
	<div class="col-md-12">
		<div id="list-panel">
			<table  id="MOStypeFacilityTable" class="table table-striped table-bordered display table-hover" cellspacing="0">
				<thead>
					<tr>
						<th>id</th>
						<th style="text-align: center;">SL.</th>
						<th><?php echo $TEXT['MOS Type Name']; ?></th>
						<th><?php echo $TEXT['Minimum MOS']; ?></th>
						<th><?php echo $TEXT['Maximum MOS']; ?></th>
						<th style="text-align: left;"><?php echo $TEXT['Color Code']; ?></th>
						<th style="text-align: left;"><?php echo $TEXT['Icon Mos']; ?></th>
						<th style="text-align: left;"><?php echo $TEXT['Icon Mos Width']; ?></th>
						<th style="text-align: left;"><?php echo $TEXT['Icon Mos Height']; ?></th>
						<th style="text-align: left;"><?php echo $TEXT['MosLabel']; ?></th>
						<th style="display:none;"><?php echo $TEXT['Action']; ?></th>
						<th>Country Id</th>
						<th>Facility Id</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>			                   
		</div>

		<div id="form-panel" style="display:none;">
			<div class="panel-heading">
				<?php echo $TEXT['MOS Type Master Form']; ?>           
			</div>
			<div class="panel-body">
				<form novalidate="" data-validate="parsley" id="t_MOSType_form" class="form-horizontal form-border no-margin">
					<div class="form-group">
						<div class="col-md-4">
							<input type="text" style="display:none;" id="MostypeFacilityId" name="MostypeFacilityId"/>
						</div>
					</div>
				</form>    
			</div>      
		</div>
	</div>
</div>			
<!--panel1-->
<div class="row"> 
	<div class="col-md-12">	
		<div id="list-panel1">
			<table  id="MOStypeFacilityDetailsTable" class="table table-striped table-bordered display table-hover" cellspacing="0">
				<thead>
					<tr>
						<th>id</th>
						<th style="text-align: center;">SL.</th>
						<th><?php echo $TEXT['MOS Type Name']; ?></th>
						<th><?php echo $TEXT['Minimum MOS']; ?></th>
						<th><?php echo $TEXT['Maximum MOS']; ?></th>
						<th style="text-align: left;"><?php echo $TEXT['Color Code']; ?></th>
						<th style="text-align: left;"><?php echo $TEXT['Icon Mos']; ?></th>
						<th style="text-align: left;"><?php echo $TEXT['Icon Mos Width']; ?></th>
						<th style="text-align: left;"><?php echo $TEXT['Icon Mos Height']; ?></th>
						<th style="text-align: left;"><?php echo $TEXT['MosLabel']; ?></th>
						<th style="display:none;"><?php echo $TEXT['Action']; ?></th>
						<th>Country Id</th>
						<th>Facility Id</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>			                   
		</div>
	</div>
</div>

<script>
  function print_function() {
	var MostypeFacilityId = $('#MostypeFacilityId').val();
	var currentSearch = $('#MOStypeFacilityTable_filter').find('input').val();
	window.open("<?php echo $baseUrl; ?>report/t_mostype_facility_print.php?jBaseUrl=<?php echo $jBaseUrl; ?>&lan=<?php echo $lan; ?>&CountryId=" + $('#country-list').val() + "&FacilityLevel=" + $('#facility-level-list').val() + "&sSearch=" + currentSearch
			+ "&CountryName=" + $('#country-list option[value=' + $('#country-list').val() + ']').text()
			+ "&FacilityLevelName=" + $('#facility-level-list option[value=' + $('#facility-level-list').val() + ']').text() + "&MostypeFacilityId=" + MostypeFacilityId);
}
function excel_function() {
	var MostypeFacilityId = $('#MostypeFacilityId').val();
	var currentSearch = $('#MOStypeFacilityTable_filter').find('input').val();
	window.open("<?php echo $baseUrl; ?>report/t_mostype_facility_excel.php?jBaseUrl=<?php echo $jBaseUrl; ?>&lan=<?php echo $lan; ?>&CountryId=" + $('#country-list').val() + "&FacilityLevel=" + $('#facility-level-list').val() + "&sSearch=" + currentSearch
			+ "&CountryName=" + $('#country-list option[value=' + $('#country-list').val() + ']').text()
			+ "&FacilityLevelName=" + $('#facility-level-list option[value=' + $('#facility-level-list').val() + ']').text() + "&MostypeFacilityId=" + MostypeFacilityId);
}
</script> 

<style>
	#t_MOSType_form select, #t_MOSType_form input, .colorpicker3 input-group{
		max-width: 300px;
	}
	#t_MOSType_details_form select, #t_MOSType_details_form input{
		max-width: 300px;
	}
	.colorpicker3 {
		max-width: 300px;
	}
	#colorpicker3 {
		max-width: 300px;
	}	
	.input-group-addon:last-child {
		border-left: 0 none;
	}
	.SL, .Action, .MinMos, .MaxMos, .IconMos_Width, .IconMos_Height {
		text-align: center !important;
	}
	#lightCustomModal{
		width: 50%;
		height: auto;

	}
	#lightCustomModal h4{ font-size: 14px;}
	table tbody tr.even.row_selected td, table tbody tr.odd.row_selected td {
		background-color: #9AD268;
		color: #fff;
	}	
</style>

<link href="<?php echo $baseUrl; ?>media/datatable-bootstrap/dataTables.bootstrap.css" rel="stylesheet"/>
<link href="<?php echo $baseUrl; ?>css/main.css" rel="stylesheet"/>
<link href="<?php echo $baseUrl; ?>css/jquery.dataTables.custom.css" rel="stylesheet"/>
<script src='<?php echo $baseUrl; ?>media/datatable/js/jquery.dataTables.min.js'></script>
<script src='<?php echo $baseUrl; ?>media/datatable-bootstrap/dataTables.bootstrap.min.js'></script>
<script src='<?php echo $baseUrl; ?>t_mostype_facility_view.js'></script>
<script src='<?php echo $baseUrl; ?>js/jquery.force.numeric.js'></script>
<script src='<?php echo $baseUrl; ?>js/plugins/bootstrap-colorpicker/bootstrap-colorpicker.js'></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_en.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_fr.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_switcher.js"></script>
	
	