<?php
$user = JFactory::getUser();
$UserId = $user->name;
$baseUrl = JURI::base() . 'administrator/components/com_jcode/source/';
$jBaseUrl = JURI::base();
$lang = JFactory::getLanguage();
$lan = $lang->getTag();
$userName = $user->username;
?>

<script>
    var baseUrl = '<?php echo $baseUrl; ?>';
    var lan = '<?php echo $lan; ?>';
    var userName = '<?php echo $userName; ?>';
    var userid = '<?php echo $UserId; ?>';
</script>

<?php
include_once ('language/lang_en.php');
include_once ('language/lang_fr.php');
include_once ('language/lang_switcher.php');
?>
<div class="nav-data">
	<div class="row">
		<div class="col-md-12 col-padding" style="float: left;">
			<div class="tbl-header1 pull-right">
				<label>		
					<a id="PrintBTN" data-mce-href="#" class="but_print" href="javascript:void(0);" onclick="print_function('print')"><i data-mce-bootstrap="1" class="fa fa-print fa-lg">&nbsp;</i> <?php echo $TEXT['Print']; ?></a>
					<a id="PrintBTN1" data-mce-href="#" class="but_excel" href="javascript:void(0);" onclick="print_function('excel')"><i data-mce-bootstrap="1" class="fa fa-file-excel-o fa-lg">&nbsp;</i> <?php echo $TEXT['Excel']; ?></a>
				</label>
			</div>
		</div>
	</div>
</div>
<div class="row row-margin">
    <div class="col-md-5">
        <div id="grid_country">
            <div class="panel-heading">
                <?php echo $TEXT['Country List']; ?>&nbsp;						
            </div>	
            <div class="grid-wrap">
                <table id="gridDataCountry" class="table table-striped table-bordered display table-hover" cellspacing="0">
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
        <div class="panel panel-default table-responsive" id="grid_product">
            <div class="panel-heading">

                <?php echo $TEXT['Product List']; ?> &nbsp; 
                <span class="pull-right" id="sselSec"><input type='checkbox' id="ssel" onclick="showSelected()"/><span class='custom-checkbox'></span>&nbsp;&nbsp;<?php echo $TEXT['Show Selected']; ?></span>

            </div>
            <div class="grid-wrap">       
                <table id="gridDataProduct" class="table table-striped table-bordered display table-hover" cellspacing="0">
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
    String.prototype.replaceAll = function(token, newToken, ignoreCase) {
        var _token;
        var str = this + "";
        var i = -1;
        if (typeof token === "string") {

            if (ignoreCase) {

                _token = token.toLowerCase();
                while ((
                        i = str.toLowerCase().indexOf(
                        token, i >= 0 ? i + newToken.length : 0
                        )) !== -1
                        ) {
                    str = str.substring(0, i) +
                            newToken +
                            str.substring(i + token.length);
                }

            } else {
                return this.split(token).join(newToken);
            }

        }
        return str;
    };

    function print_function() {
        if (SelCountryName != '') {
            var currentSearch = $("#gridDataProduct_filter input").val();
            currentSearch = currentSearch.replaceAll("+", "|");
            window.open("<?php echo $baseUrl; ?>report/t_country_product_print.php?jBaseUrl=<?php echo $jBaseUrl; ?>&lan=<?php echo $lan; ?>&SelCountryId=" + SelCountryId + "&SelCountryName=" + SelCountryName + "&ShowSelected=true&sSearch=" + currentSearch);
        } else {
            alert('Please select a country');
        }
    }
    function excel_function() {
        if (SelCountryName != '') {
            var currentSearch = $("#gridDataProduct_filter input").val();
            currentSearch = currentSearch.replaceAll("+", "|");
            window.open("<?php echo $baseUrl; ?>report/t_country_product_excel.php?jBaseUrl=<?php echo $jBaseUrl; ?>&lan=<?php echo $lan; ?>&SelCountryId=" + SelCountryId + "&SelCountryName=" + SelCountryName + "&ShowSelected=true&sSearch=" + currentSearch);
        } else {
            alert('Please select a country');
        }
    }

</script>

<link href="<?php echo $baseUrl; ?>media/datatable-bootstrap/dataTables.bootstrap.css" rel="stylesheet"/>
<link href="<?php echo $baseUrl; ?>css/jquery.dataTables.custom.css" rel="stylesheet"/>
<script src='<?php echo $baseUrl; ?>media/datatable/js/jquery.dataTables.min.js'></script>
<script src='<?php echo $baseUrl; ?>media/datatable-bootstrap/dataTables.bootstrap.min.js'></script>   
<script src='<?php echo $baseUrl; ?>country_product.js'></script>      





