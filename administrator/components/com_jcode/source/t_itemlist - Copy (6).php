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

<div class="container">
	<div class="row tbl-header">
		<div class="col-sm-6">
			<div class="tbl-header1" id="itemTable_length1">
				<label><?php echo $TEXT['Product Group']; ?>
					<select class="form-control" id="item-group">
                        <option selected="" value=""><?php echo $TEXT['All']; ?></option>
                    </select>
                    </label>
			</div>
		</div>
		<div class="col-sm-6">
			<div class="tbl-header1 pull-right">
				<label>					
					<a data-mce-href="#" class="btn-list but_back" href="javascript:void(0);" onClick="onListPanel()"><i data-mce-bootstrap="1" class="fa fa-reply fa-lg">&nbsp;</i> <?php echo $TEXT['Back to List']; ?></a>					
					<a data-mce-href="#" class="btn-form but_add" href="javascript:void(0);" onClick="onFormPanel()"><i data-mce-bootstrap="1" class="fa fa-plus fa-lg">&nbsp;</i> <?php echo $TEXT['Add Record']; ?></a>
					<a id="PrintBTN" data-mce-href="#" class="but_print" href="javascript:void(0);" onclick="print_function('print')"><i data-mce-bootstrap="1" class="fa fa-print fa-lg">&nbsp;</i> <?php echo $TEXT['Print']; ?></a>
					<a id="PrintBTN1" data-mce-href="#" class="but_excel" href="javascript:void(0);" onclick="print_function('excel')"><i data-mce-bootstrap="1" class="fa fa-file-excel-o fa-lg">&nbsp;</i> <?php echo $TEXT['Excel']; ?></a>
				</label>
			</div>
		</div>
	</div>
	
	
	<div class="row row-magin-top" id="list-panel">	   
	        <div class="panel panel-default">  	
	            <div class="panel-body">
	                <div class="clearfix list-panel" >
	                    <table  id="itemTable" class="table table-striped table-bordered display" cellspacing="0">
	                        <thead>
	                            <tr>
	                                <th><?php echo $TEXT['Product No']; ?></th>
	                                <th style="text-align: center;">SL.</th>
	                                <th><?php echo $TEXT['Product Code']; ?></th>
	                                <th><?php echo $TEXT['Product Name']; ?></th>
	                                <th><?php echo $TEXT['Short Name']; ?></th>
	                                <th style="text-align: left;"><?php echo $TEXT['Key Product']; ?></th>
	                                <th><?php echo $TEXT['Product Subgroup']; ?></th>
	                                <th style="text-align: left;"><?php echo $TEXT['Common Basket']; ?></th>
	                                <th style="text-align: center;"><?php echo $TEXT['Action']; ?></th>
	                                <th><?php echo $TEXT['Group Name']; ?></th>
	                            </tr>
	                        </thead>
	                        <tbody></tbody>
	                    </table>
	                   
	                </div>
	            </div>	       
	    </div> 
	</div>
	
	<div class="row row-magin-top" id="form-panel">
	    <div class="panel panel-default">
	        <div class="panel-heading">
	            <?php echo $TEXT['Product Group Form']; ?>           
	        </div>
	        <div class="panel-body">
	            <form novalidate="" data-validate="parsley" id="itemlist_form" class="form-horizontal form-border no-margin">
	
	                <div class="form-group">
	                    <label class="control-label col-lg-6" for="ItemGroup"><?php echo $TEXT['Product Group']; ?>*</label>
	                    <div class="col-lg-7">
	                        <select class="form-control" name="ItemGroupId" id="ItemGroupId" data-required="true" >
	                            <option selected="true" value=""><?php echo $TEXT['Select Product Group']; ?></option></select>
	                    </div>
	                </div>
	
	                <div class="form-group">
	                    <label class="control-label col-lg-6" for="ProductSubGroupId"><?php echo $TEXT['Product Subgroup']; ?>*</label>
	                    <div class="col-lg-7">
	                        <select class="form-control" name="ProductSubGroupId" id="ProductSubGroupId" data-required="true" >
	                           
	                        </select>
	                    </div>
	                </div>
	
	                <div class="form-group">
	                    <label class="control-label col-lg-6" for="ItemCode"><?php echo $TEXT['Product Code']; ?>*</label>
	                    <div class="col-lg-7">
	                        <input class="form-control input-sm parsley-validated" maxlength="100" type="text" name="ItemCode" id="ItemCode" data-required="true" placeholder="input here..."/>
	                    </div>
	                </div>
	
	                <div class="form-group">
	                    <label class="control-label col-lg-6" for="ItemName"><?php echo $TEXT['Product Name']; ?>*</label>
	                    <div class="col-lg-7">
	                        <input class="form-control input-sm parsley-validated" maxlength="150" type="text" name="ItemName" id="ItemName" data-required="true" placeholder="input here..."/>
	                    </div>
	                </div>
	
	                <div class="form-group">
	                    <label class="control-label col-lg-6" for="ShortName"><?php echo $TEXT['Short Name']; ?>*</label>
	                    <div class="col-lg-7">
	                        <input class="form-control input-sm parsley-validated" maxlength="20" type="text" name="ShortName" id="ShortName" data-required="true" placeholder="input here..."/>
	                    </div>
	                </div>
	
	                <div class="form-group">
	                    <label class="control-label col-lg-2" for="KeyItem"><?php echo $TEXT['Key Product']; ?></label>
	                    <div class="col-lg-2">
	                        <input type="checkbox" name="bKeyItem" id="bKeyItem" onclick="bKey()"/>
	                        <span class="custom-checkbox"></span> 
	                    </div>
	
	                    <label class="control-label col-lg-2" for="CommonBasket"><?php echo $TEXT['Common Basket']; ?></label>
	                    <div class="col-lg-2">
	                        <input type="checkbox" name="bCommonBasket" id="bCommonBasket" onclick="bCommon()"/>
	                        <span class="custom-checkbox"></span> 
	                    </div>
	
	                </div>
	
	                <div class="form-group">
	                    <input type="text" value="insertUpdateItemListData" id="action" name="action" style="display: none;"/>
	                    <input type="text" id="ItemNo" name="ItemNo" style="display: none;"/>
	                    <input type="hiidden" style="display:none;" id="userId" value="<?php echo $user->name; ?>"/>
	                    <input type="hiidden" style="display:none;" id="en-GBId" value="<?php echo $lan; ?>"/>
	                    <label class="col-lg-2 control-label"></label>
	                    <div class="col-lg-10">
	                        <a href="javascript:void(0);" class="btn btn-success btn-form-success" id="submitItemList"><?php echo $TEXT['Submit']; ?></a>
	                        <a href="javascript:void(0);" class="btn btn-default" onClick="onListPanel()"><?php echo $TEXT['Cancel']; ?></a>
	                    </div>
	                </div>
	            </form>    
	        </div>      
	    </div>
	</div>
</div>
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

    function print_function(type) {
        var dataTableId = 'itemTable';
        var reportSaveName = 'Products'; //Not allow any type of special character of cahrtName
        var currentSearch = $('#' + dataTableId + '_filter').find('input').val();
        var reportHeaderList = new Array();
        var tableHeaderList = new Array();
        var tableHeaderWidth = new Array();
        var dataType = new Array();
        var sqlParameterList = new Array();
        var groupBySqlIndex = -1;
        var colorCodeIndex = Array();
        var checkBoxIndex = new Array();
        var columns = $('#' + dataTableId).dataTable().dataTableSettings[0].aoColumns;
        $.each(columns, function(i, v) {
            if (v.bVisible) {
                tableHeaderList.push(v.sTitle);
                tableHeaderWidth.push(v.sWidth);
                dataType.push(v.sType);
            }
        });

        var reportHeaderName = TEXT['Products List'];
        reportHeaderList[0] = reportHeaderName;
        reportHeaderList[1] = TEXT['Product Group'] + ': ' + $('#item-group option[value=' + $('#item-group').val() + ']').text();

        sqlParameterList[0] = (($('#item-group').val() == '') ? 0 : $('#item-group').val());
        groupBySqlIndex = 7;
        //colorCodeIndex[0] = 5;
        checkBoxIndex[0] = 4;
        checkBoxIndex[1] = 6;

        reportHeaderList = JSON.stringify(reportHeaderList);
        dataType = JSON.stringify(dataType);
        tableHeaderList = JSON.stringify(tableHeaderList);
        tableHeaderWidth = JSON.stringify(tableHeaderWidth);
        sqlParameterList = JSON.stringify(sqlParameterList);
        colorCodeIndex = JSON.stringify(colorCodeIndex);
        checkBoxIndex = JSON.stringify(checkBoxIndex);
        //Get current date time
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1; //January is 0!
        var yyyy = today.getFullYear();
        var hh = today.getHours();
        var min = today.getMinutes();
        var sec = today.getSeconds();
        today = dd + '_' + mm + '_' + yyyy + '_' + hh + '_' + min + '_' + sec;
        reportSaveName = reportSaveName + '_' + today;

        if (type == 'print') {
            window.open("<?php echo $baseUrl; ?>report/print_master.php?action=getItemListData"
                    + "&jBaseUrl=<?php echo $jBaseUrl; ?>"
                    + "&lan=<?php echo $lan; ?>"
                    + "&reportSaveName=" + reportSaveName
                    + "&sSearch=" + currentSearch
                    + "&reportHeaderList=" + reportHeaderList
                    + "&tableHeaderList=" + tableHeaderList
                    + "&tableHeaderWidth=" + tableHeaderWidth
                    + "&useSl=" + false
                    + "&dataType=" + dataType
                    + "&sqlParameterList=" + sqlParameterList
                    + "&reportType=" + type
                    + "&groupBySqlIndex=" + groupBySqlIndex
                    + "&colorCodeIndex=" + colorCodeIndex
                    + "&checkBoxIndex=" + checkBoxIndex);
        }
        else if (type == 'excel') {
            window.open("<?php echo $baseUrl; ?>report/excel_master.php?action=getItemListData"
                    + "&jBaseUrl=<?php echo $jBaseUrl; ?>"
                    + "&lan=<?php echo $lan; ?>"
                    + "&reportSaveName=" + reportSaveName
                    + "&sSearch=" + currentSearch
                    + "&reportHeaderList=" + reportHeaderList
                    + "&tableHeaderList=" + tableHeaderList
                    + "&tableHeaderWidth=" + tableHeaderWidth
                    + "&useSl=" + false
                    + "&dataType=" + dataType
                    + "&sqlParameterList=" + sqlParameterList
                    + "&reportType=" + type
                    + "&groupBySqlIndex=" + groupBySqlIndex
                    + "&colorCodeIndex=" + colorCodeIndex
                    + "&checkBoxIndex=" + checkBoxIndex);

        }
        else if (type == 'pdf') {
            $.ajax({
                url: baseUrl + 'report/pdf_master.php',
                type: 'post',
                data: {
                    action: 'getItemListData',
                    jBaseUrl: "<?php echo $jBaseUrl; ?>",
                    lan: lan,
                    reportSaveName: reportSaveName,
                    sSearch: currentSearch,
                    reportHeaderList: reportHeaderList,
                    tableHeaderList: tableHeaderList,
                    tableHeaderWidth: tableHeaderWidth,
                    useSl: false,
                    dataType: dataType,
                    sqlParameterList: sqlParameterList,
                    reportType: type,
                    groupBySqlIndex: groupBySqlIndex,
                    colorCodeIndex: colorCodeIndex,
                    checkBoxIndex: checkBoxIndex
                },
                success: function(response) {
                    if (response == 'Processing Error') {
                        alert('No Record Found.');
                    } else {
                        window.open(baseUrl + 'report/pdfslice/' + response);

                    }
                }
            });
        }

    }

</script>

<style>
	#itemlist_form select, #itemlist_form input{
		max-width: 300px;
	}
</style>

<link href="<?php echo $baseUrl; ?>media/datatable-bootstrap/dataTables.bootstrap.css" rel="stylesheet"/>
<link href="<?php echo $baseUrl; ?>css/jquery.dataTables.custom.css" rel="stylesheet"/>
<script src='<?php echo $baseUrl; ?>media/datatable/js/jquery.dataTables.min.js'></script>
<script src='<?php echo $baseUrl; ?>media/datatable-bootstrap/dataTables.bootstrap.min.js'></script>

<script src='<?php echo $baseUrl; ?>t_itemlist.js'></script>

<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_en.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_fr.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>language/lang_switcher.js"></script>