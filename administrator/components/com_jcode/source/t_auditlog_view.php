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
	<div class="col-md-8">		
		<div id="list-panel">
            <table  id="auditLogTable" class="table table-striped table-bordered display table-hover" cellspacing="0">
                <thead>
                    <tr>
						<th style="text-align: left;">SL#</th>
						<th>Id</th>
						<th>Date</th>
						<th>User</th>
						<th>Remote IP</th>
						<th>Query Type</th>
						<th>Tabele Name</th>
						<th>Sql Text</th>
						<th></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>			                   
        </div>
	</div>
	<div class="col-md-4">		
		<div id="list-panel">
            <table  id="auditLogTableList" class="table table-striped table-bordered display table-hover" cellspacing="0">
                <thead>
                    <tr>
						<th style="text-align: left;">SL#</th>
						<th>Field Name</th>
						<th>Old Value</th>
						<th>New Value</th> 
                    </tr>
                </thead>
                <tbody></tbody>
            </table>			                   
        </div>
	</div>
</div>

<style>
    .SL, .Action {
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
<link href="<?php echo $baseUrl; ?>css/jquery.dataTables.custom.css" rel="stylesheet"/>
<script src='<?php echo $baseUrl; ?>media/datatable/js/jquery.dataTables.min.js'></script>
<script src='<?php echo $baseUrl; ?>media/datatable-bootstrap/dataTables.bootstrap.min.js'></script>

<script src='<?php echo $baseUrl; ?>t_auditlog_view.js'></script>

<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_en.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_fr.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_switcher.js"></script>
