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
include_once ('init_month_year.php');
include_once ('function_lib.php');
include_once ('combo_script.php');
include_once ('language/lang_en.php');
include_once ('language/lang_fr.php');
include_once ('language/lang_switcher.php');
?>
<div class="nav-data">
	<div class="row">				
		<div class="col-md-6 col-padding">					
			
		</div>
		<div class="col-md-6 col-padding">
			<div class="tbl-header1 pull-right">
				<label>					
					<a id="PrintBTN" data-mce-href="#" class="but_print" href="javascript:void(0);" onclick="print_function()"><i data-mce-bootstrap="1" class="fa fa-print fa-lg">&nbsp;</i> <?php echo $TEXT['Print']; ?></a>
					<a id="PrintBTN1" data-mce-href="#" class="but_excel" href="javascript:void(0);" onclick="print_function()"><i data-mce-bootstrap="1" class="fa fa-file-excel-o fa-lg">&nbsp;</i> <?php echo $TEXT['Excel']; ?></a>
				</label>
			</div>
		</div>
	</div>
</div>

<div class="nav-data" style="margin-top:10px;">
	<div class="row">
	    <div class="col-md-2 col-padding">
	        <select class="form-control chzn-select" name="ACountryId" id="ACountryId"></select>
	    </div>
		  <div class="col-md-2 col-padding">
	        <select class="form-control" id="item-group"></select>
	    </div>
	    <div class="col-md-3 col-padding">
	        <select class="form-control chzn-select" name="AFundingSourceId" id="AFundingSourceId"><option value=""><?php echo $TEXT['All Funding Source']; ?></option></select>   
	    </div>
		  <div class="col-md-2 col-padding">
	        <select class="form-control chzn-select" name="OwnerType" id="OwnerType"><option value=""><?php echo $TEXT['All Owner Type']; ?></option></select>
	    </div>
	    <div class="col-md-3 col-padding">
	        <select class="form-control chzn-select" name="ASStatusId" id="ASStatusId"><option value=""><?php echo $TEXT['All Status']; ?></option></select> 
	    </div>             
	</div>  
</div>
      
<div class="row" id="list-panel">
    <div class="col-md-12">
        <div class="panel panel-default table-responsive">
            <div class="panel-heading">
                <?php echo $TEXT['Shipment List']; ?>               
            </div>	
            <div class="padding-md clearfix">
                <table  id="shipmentEntryTable" class="table table-striped table-bordered display table-hover" cellspacing="0">
                    <thead>
                        <tr>
                            <th>AgencyShipment Id</th>
                            <th style="text-align: center;">SL.</th>   
                            <th><?php echo $TEXT['Product Group']; ?></th>
                            <th><?php echo $TEXT['Item Name']; ?></th>                   
                            <th><?php echo $TEXT['Shipment Status']; ?></th>                       
                            <th style="text-align: center;"><?php echo $TEXT['Shipment Date']; ?></th>
                            <th style="text-align: left;"><?php echo $TEXT['Owner Type']; ?></th>
                            <th style="text-align: right;"><?php echo $TEXT['Quantity']; ?></th>
                            <th style="display:none;"><?php echo $TEXT['Action']; ?></th>
                            <th>Agency</th>							
                            <th>Product Group</th> 							
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>    
</div>

<style>
    .SL, .Action, .ShipmentStatus, .Date{
        text-align: center !important;
    }
    .Quantity, #Qty{
        text-align: right !important;
    }
    .OwnerTypeName{
        text-align: right !important;
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

	var currentSearch = $("#shipmentEntryTable_filter input").val();
	currentSearch = currentSearch.replaceAll("+", "|");
	var ItemGroupName = $("#item-group option:selected").text();
	var OwnerTypeName = $("#OwnerType option:selected").text();
	var pCountryName = $("#ACountryId option:selected").text();
	var pFundingSourceNameName = $("#AFundingSourceId option:selected").text();
	var pShipmentStatusDesc = $("#ASStatusId option:selected").text();
	window.open("<?php echo $baseUrl; ?>report/t_agency_shipment_print.php?jBaseUrl=<?php echo $jBaseUrl; ?>&lan=<?php echo $lan; ?>&ACountryId=" + $('#ACountryId').val()
			+ "&AFundingSourceId=" + $('#AFundingSourceId').val() + "&ItemGroup=" + $('#item-group').val() + "&OwnerType=" + $('#OwnerType').val()
			+ "&ASStatusId=" + $('#ASStatusId').val()
			+ "&CountryName=" + pCountryName
			+ "&FundingSourceName=" + pFundingSourceNameName
			+ "&ShipmentStatusDesc=" + pShipmentStatusDesc
			+ "&ItemGroupName=" + ItemGroupName
			+ "&OwnerTypeName=" + OwnerTypeName
			+ "&sSearch=" + currentSearch);
}

function excel_function() {

	var currentSearch = $("#shipmentEntryTable_filter input").val();
	currentSearch = currentSearch.replaceAll("+", "|");
	var ItemGroupName = $("#item-group option:selected").text();
	var OwnerTypeName = $("#OwnerType option:selected").text();
	var pCountryName = $("#ACountryId option:selected").text();
	var pFundingSourceNameName = $("#AFundingSourceId option:selected").text();
	var pShipmentStatusDesc = $("#ASStatusId option:selected").text();
	window.open("<?php echo $baseUrl; ?>report/t_agency_shipment_excel.php?jBaseUrl=<?php echo $jBaseUrl; ?>&lan=<?php echo $lan; ?>&ACountryId=" + $('#ACountryId').val()
			+ "&AFundingSourceId=" + $('#AFundingSourceId').val() + "&ItemGroup=" + $('#item-group').val() + "&OwnerType=" + $('#OwnerType').val()
			+ "&ASStatusId=" + $('#ASStatusId').val()
			+ "&CountryName=" + pCountryName
			+ "&FundingSourceName=" + pFundingSourceNameName
			+ "&ShipmentStatusDesc=" + pShipmentStatusDesc
			+ "&ItemGroupName=" + ItemGroupName
			+ "&OwnerTypeName=" + OwnerTypeName
			+ "&sSearch=" + currentSearch);
}
</script>

<link href="<?php echo $baseUrl; ?>media/datepicker/datepicker.css" rel="stylesheet"/>
<link href="<?php echo $baseUrl; ?>media/datatable-bootstrap/dataTables.bootstrap.css" rel="stylesheet"/>
<link href="<?php echo $baseUrl; ?>css/jquery.dataTables.custom.css" rel="stylesheet"/>

<script src='<?php echo $baseUrl; ?>media/datatable/js/jquery.dataTables.min.js'></script>
<script src='<?php echo $baseUrl; ?>media/datepicker/bootstrap-datepicker.min.js'></script>
<script src='<?php echo $baseUrl; ?>media/datatable-bootstrap/dataTables.bootstrap.min.js'></script>
<script src='<?php echo $baseUrl; ?>js/jquery.force.numeric.js'></script>
<script src='<?php echo $baseUrl; ?>t_agency_shipment_view.js'></script>

