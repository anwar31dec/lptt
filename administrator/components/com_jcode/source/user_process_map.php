<?php
$user = JFactory::getUser();
$baseUrl = JURI::base() . 'administrator/components/com_jcode/source/';
$jBaseUrl = JURI::base();
$lang = JFactory::getLanguage();
$lan = $lang->getTag();
?>

<script>
	var userid = '<?php echo $user;?>'; 
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
				<div class="col-md-6"></div>
				<div class="col-md-6">
					<div class="tbl-header1 pull-right">
						<label>					
							<a id="PrintBTN" data-mce-href="#" class="but_print" id="PrintBTN" onclick="print_function()"><i data-mce-bootstrap="1" class="fa fa-print fa-lg">&nbsp;</i> <?php echo $TEXT['Print']; ?></a>
							<a id="PrintBTN1" data-mce-href="#" class="but_excel" id="PrintBTN1" onclick="excel_function()"><i data-mce-bootstrap="1" class="fa fa-file-excel-o fa-lg">&nbsp;</i> <?php echo $TEXT['Excel']; ?></a>
						</label>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-6">
		<div id="list-panel">
			<div class="panel-heading" style="margin-bottom:10px;">
				<div class="clearfix">
				<?php echo $TEXT['User List']; ?>
				</div>
			</div>			
            <table  id="gridDataUser" class="table table-striped table-bordered display table-hover" cellspacing="0">
                <thead>
                    <tr>
						<th><?php echo $TEXT['User Id']; ?></th>
						<th style="text-align: center;">SL#</th>
						<th style="text-align: left;"><?php echo $TEXT['User Name']; ?></th>
						<th style="text-align: left;"></th>
						<th style="text-align: left;"><?php echo $TEXT['User Group']; ?></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>			                   
        </div>
	</div>
	
	<div class="col-md-6">		
			<div role="tabpanel"  style="margin-top:15px;">
				  <!-- Nav tabs -->
				  <ul class="nav nav-tabs" role="tablist">
					<li role="presentation" class="active"><a href="#Country" aria-controls="Country" role="tab" data-toggle="tab"><?php echo 'Process List'; ?></a></li>					
				  </ul>

				  <!-- Tab panes -->
				  <div class="tab-content">
					<div role="tabpanel" class="tab-pane active" id="Country">
						 <div class="panel panel-default table-responsive" id="grid_country">
							<div class="padding-md clearfix">       
								<table class="table table-striped table-bordered display table-hover" cellspacing="0" id="gridDataCountry">
									<thead>
										<tr>
											<th><?php echo 'Process Id'; ?></th>
											<th style="text-align:left;"><?php echo 'Process Name'; ?></th>
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

<script>
function print_function(){
	var currentSearch = $('#gridDataUser_filter').find('input').val();
	window.open("<?php echo $baseUrl; ?>report/t_user_country_map_print.php?jBaseUrl=<?php echo $jBaseUrl; ?>&lan=<?php echo $lan;?>&sSearch="+currentSearch);
    	 					 
} 		
function excel_function(){
	var currentSearch = $('#gridDataUser_filter').find('input').val();
	window.open("<?php echo $baseUrl; ?>report/t_user_country_map_excel.php?jBaseUrl=<?php echo $jBaseUrl; ?>&lan=<?php echo $lan;?>&sSearch="+currentSearch);
    		 				  
} 	
		
</script>

<style>
    table.display tr.even.row_selected td {
    	background-color: #4DD4FD;
    }
    
    table.display tr.odd.row_selected td {
    	background-color: #4DD4FD;
    }
    .SL{
        text-align: center !important;
    }
    td.Users{
        cursor: pointer;
    }      
</style>

<link href="<?php echo $baseUrl; ?>media/datatable-bootstrap/dataTables.bootstrap.css" rel="stylesheet"/>
<link href="<?php echo $baseUrl; ?>css/jquery.dataTables.custom.css" rel="stylesheet"/>
<script src='<?php echo $baseUrl; ?>media/datatable/js/jquery.dataTables.min.js'></script>
<script src='<?php echo $baseUrl; ?>media/datatable-bootstrap/dataTables.bootstrap.min.js'></script>

<script src='<?php echo $baseUrl; ?>user_process_map.js'></script>

<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_en.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_fr.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_switcher.js"></script>
