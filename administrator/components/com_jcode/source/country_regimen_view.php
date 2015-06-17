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
    var userName = '<?php echo $userName;?>';
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

<div class="page-title">
	<div class="clearfix">
        <h3 class="no-margin"><?php echo $TEXT['Country Regimen']; ?></h3>
         <span class="pull-right">
            <button class="btn btn-info" type="button" id="PrintBTN" onclick="print_function()" > <?php echo $TEXT['Print']; ?> </button>
            <button class="btn btn-info" type="button" id="PrintBTN1" onclick="excel_function()" > <?php echo $TEXT['Excel']; ?> </button>
        </span>
    </div>
</div>

<br /> 
   
<div class="row">

    <div class="col-md-5">
        <div class="panel panel-default table-responsive" id="grid_country">
            <div class="panel-heading">
                <?php echo $TEXT['Country List']; ?>             
            </div>            	
           	<div class="padding-md clearfix">
    			<table class="table table-striped display" id="gridDataCountry">
    				<thead>
    					<tr>
    						<th><?php echo $TEXT['Country Id']; ?></th>
    						<th style="text-align: center;">SL#</th>
    						<th style="text-align: left;"><?php echo $TEXT['Country Name']; ?></th>
    					</tr>
    				</thead>
    				<tbody></tbody>
    			</table>
            </div>
		</div>  
    </div>   

    <div class="col-md-7">
        <div class="panel panel-default table-responsive" id="grid_regimen">
            <div class="panel-heading">
                <?php echo $TEXT['Regimen List']; ?> 			
                <span class="pull-right" id="sselSec">
                    <table class="nav-data">
    					<tbody>
    						<tr>
    							<td>
                                    <?php echo $TEXT['Product Group'];?>&nbsp;&nbsp;</td><td valign="middle" align="left">
    								<select class="form-control" name="ItemGroupId" id="ItemGroupId"></select>
    							</td>
                                <td>
                                    &nbsp;&nbsp;&nbsp;
                                    <input type='checkbox' id="ssel" onclick="showSelected()"/><span class='custom-checkbox'></span><?php echo $TEXT['Show Selected'];?>
                                </td>
    						</tr>
    					</tbody>
    				</table>                 
                </span>               
            </div>  
            <div class="padding-md clearfix">       
    			<table class="table table-striped display" id="gridDataRegimen">
    				<thead>
    					<tr>
    						<th><?php echo $TEXT['Regimen Id']; ?></th>
    						<th><?php echo $TEXT['Regimen Name']; ?></th>
                            <th><?php echo $TEXT['Formulation Name']; ?></th>
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
    td.Countries{
        cursor: pointer;
    }    
    .panel-heading {
        padding: 10px 10px 23px 15px !important;
    }  
</style> 

<script>
String.prototype.replaceAll = function( token, newToken, ignoreCase ) {
    var _token;
    var str = this + "";
    var i = -1;

    if ( typeof token === "string" ) {

        if ( ignoreCase ) {

            _token = token.toLowerCase();

            while( (
                i = str.toLowerCase().indexOf(
                    token, i >= 0 ? i + newToken.length : 0
                ) ) !== -1
            ) {
                str = str.substring( 0, i ) +
                    newToken +
                    str.substring( i + token.length );
            }

        } else {
            return this.split( token ).join( newToken );
        }

    }
return str;
};
function print_function(){
	if(SelCountryName != ''){
		var currentSearch = $('#gridDataRegimen_filter').find('input').val();
		currentSearch=currentSearch.replaceAll("+", "|");		
		window.open("<?php echo $baseUrl; ?>report/t_country_regimen_print.php?jBaseUrl=<?php echo $jBaseUrl;?>&lan=<?php echo $lan;?>
		&SelCountryId="+SelCountryId+"&SelCountryName="+SelCountryName+"&ShowSelected=true&ItemGroupId="+$('#ItemGroupId').val()+"&sSearch="+currentSearch);		 		
	} else{
		 alert('Please select a country');
	 }	 

} 		
function excel_function(){
if(SelCountryName != ''){
	var currentSearch = $('#gridDataRegimen_filter').find('input').val();
	currentSearch=currentSearch.replaceAll("+", "|");		
    window.open("<?php echo $baseUrl; ?>report/t_country_regimen_excel.php?jBaseUrl=<?php echo $jBaseUrl;?>&lan=<?php echo $lan;?>&SelCountryId="+SelCountryId+"&SelCountryName="+SelCountryName+"&ShowSelected=true&ItemGroupId="+$('#ItemGroupId').val()+"&sSearch="+currentSearch);		 		
	} else{
		alert('Please select a country');
	}	 
} 		


</script>
    
<link href="<?php echo $baseUrl; ?>css/custom.css" rel="stylesheet"/>
<link href="<?php echo $baseUrl; ?>lib/chosen/chosen.css" rel="stylesheet"/>
<link href="<?php echo $jBaseUrl; ?>/templates/protostar/endless/css/jquery.dataTables_themeroller.css" rel="stylesheet"/>
<link href="<?php echo $jBaseUrl; ?>/templates/protostar/endless/css/endless.min.css" rel="stylesheet"/>
 
<script src='<?php echo $jBaseUrl; ?>/templates/protostar/endless/js/parsley.min.js'></script>
<script src='<?php echo $jBaseUrl; ?>/templates/protostar/endless/js/jquery.dataTables.min.js'></script>
<script src='<?php echo $baseUrl; ?>lib/chosen/chosen.jquery.js'></script>      
<script src='<?php echo $baseUrl; ?>country_regimen_view.js'></script>      

           
           
           
           
                       