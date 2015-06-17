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
	include_once ('language/lang_en.php');
	include_once ('language/lang_fr.php');
	include_once ('language/lang_switcher.php');
?>

<div class="page-title">
    <h3 class="no-margin"><?php echo $TEXT['User Country Map']; ?></h3>
</div>

<br /> 
   
<div class="row">

    <div class="col-md-7">
        <div class="panel panel-default table-responsive" id="grid_user">
            <div class="panel-heading">
                <?php echo $TEXT['User List']; ?>&nbsp;&nbsp;
				<button class="btn btn-info" type="button" id="PrintBTN" onclick="printfunction()" > <?php echo $TEXT['Print']; ?> </button>&nbsp;
				<button class="btn btn-info" type="button" id="PrintBTN1" onclick="printfunction1()" > <?php echo $TEXT['Excel']; ?> </button>				
            </div>	
           	<div class="padding-md clearfix">
    			<table class="table table-striped display" id="gridDataUser">
    				<thead>
    					<tr>
    						<th><?php echo $TEXT['User Id']; ?></th>
    						<th style="text-align: center;">SL#</th>
    						<th style="text-align: left;"><?php echo $TEXT['User Name']; ?></th>
    					</tr>
    				</thead>
    				<tbody></tbody>
    			</table>
            </div>
		</div>  
    </div>   

    <div class="col-md-5">
        <div class="panel panel-default table-responsive" id="grid_country">
            <div class="panel-heading">
                <?php echo $TEXT['Country List']; ?>&nbsp;&nbsp;
            </div>
            <div class="padding-md clearfix">       
    			<table class="table table-striped display" id="gridDataCountry">
    				<thead>
    					<tr>
    						<th><?php echo $TEXT['Country Id']; ?></th>
    						<th><?php echo $TEXT['Country Name']; ?></th>
    					</tr>
    				</thead>
    				<tbody></tbody>
    			</table> 
            </div>           
		</div>     
	</div>
    
</div>
    
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


<link href="<?php echo $baseUrl; ?>css/custom.css" rel="stylesheet"/>
<link href="<?php echo $baseUrl; ?>lib/chosen/chosen.css" rel="stylesheet"/>
<link href="<?php echo $jBaseUrl; ?>/templates/protostar/endless/css/jquery.dataTables_themeroller.css" rel="stylesheet"/>
<link href="<?php echo $jBaseUrl; ?>/templates/protostar/endless/css/endless.min.css" rel="stylesheet"/>
 
<script src='<?php echo $jBaseUrl; ?>/templates/protostar/endless/js/parsley.min.js'></script>
<script src='<?php echo $jBaseUrl; ?>/templates/protostar/endless/js/jquery.dataTables.min.js'></script>
<script src='<?php echo $baseUrl; ?>lib/chosen/chosen.jquery.js'></script>      
<script src='<?php echo $baseUrl; ?>user_country_map_view.js'></script>      

           
           
           
           
                       