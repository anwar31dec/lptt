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
    var lan = '<?php echo $lan;?>';
    var userName = '<?php echo $userName;?>';
</script>

<?php 
	include_once ('language/lang_en.php');
	include_once ('language/lang_fr.php');
	include_once ('language/lang_switcher.php');
?>

<div class="page-title">
<div class="clearfix">
    <h3 class="no-margin"><?php echo $TEXT['Country Products']; ?></h3>
    <span class="pull-right" >
                <button class="btn btn-info" type="button" id="PrintBTN" onclick="print_function()" > <?php echo $TEXT['Print']; ?> </button>&nbsp;
                <button class="btn btn-info" type="button" id="PrintBTN1" onclick="excel_function()" > <?php echo $TEXT['Excel']; ?> </button>&nbsp;
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
    						<th style="text-align: left;">SL#</th>
    						<th style="text-align: left;"><?php echo $TEXT['Country Name']; ?></th>
    					</tr>
    				</thead>
    				<tbody></tbody>
    			</table>
            </div>
		</div>  
    </div>   
   
    <div class="col-md-7">
        <div class="panel panel-default table-responsive" id="grid_product">
            <div class="panel-heading">
                <?php echo $TEXT['Product List']; ?> 
                <span class="pull-right" id="sselSec"><input type='checkbox' id="ssel" onclick="showSelected()"/><span class='custom-checkbox'></span><?php echo $TEXT['Show Selected'];?></span>               
            </div>
            <div class="padding-md clearfix">       
    			<table class="table table-striped display" id="gridDataProduct">
    				<thead>
    					<tr>
    						<th><?php echo $TEXT['Product Id']; ?></th>
    						<th><?php echo $TEXT['Product Code']; ?></th>
                            <th><?php echo $TEXT['Product Name']; ?></th>
                            <th><?php echo $TEXT['Product Group']; ?></th>
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
		var currentSearch =  $("#gridDataProduct_filter input").val();
		currentSearch=currentSearch.replaceAll("+", "|");	
		window.open("<?php echo $baseUrl; ?>report/t_country_product_print.php?jBaseUrl=<?php echo $jBaseUrl; ?>&lan=<?php echo $lan;?>
		&SelCountryId="+SelCountryId+"&SelCountryName="+SelCountryName+"&ShowSelected=true&sSearch="+currentSearch);			 
	} else{
		alert('Please select a country');
	}	
}	
function excel_function(){
	if(SelCountryName != ''){
	var currentSearch =  $("#gridDataProduct_filter input").val();
	currentSearch=currentSearch.replaceAll("+", "|");	
	window.open("<?php echo $baseUrl; ?>report/t_country_product_excel.php?jBaseUrl=<?php echo $jBaseUrl; ?>&lan=<?php echo $lan;?>
	&SelCountryId="+SelCountryId+"&SelCountryName="+SelCountryName+"&ShowSelected=true&sSearch="+currentSearch);	
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
<script src='<?php echo $baseUrl; ?>country_product_view.js'></script>      

           
           
           
           
                       