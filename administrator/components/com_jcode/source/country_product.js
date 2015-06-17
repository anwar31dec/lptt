var oTable_country;
var oTable_product;
var SelCountryId = "";
var mode = 'display';
var checkStatus;
var SelCountryName = '';

var $ = jQuery.noConflict();

function showSelected() {
    checkStatus = $('#ssel').prop('checked');
    if (checkStatus == true) {
        mode = 'display';
        oTable_product.fnDraw();
    } else {
        mode = 'edit';
        oTable_product.fnDraw();
    }
}

$(function() {

    $('#sselSec').hide();

    oTable_country = $("#gridDataCountry").dataTable({
        "bFilter": false,        
        "sPaginationType": "full_numbers",
        "bSort": true,
        "bInfo": true,
        "bPaginate": true,
        "bSortClasses": false,
        "bProcessing": true,
        "bServerSide": true,
        "aLengthMenu": [[25, 50, 100], [25, 50, 100]],
        "iDisplayLength": 25,
        "sAjaxSource": baseUrl + "country_product_datasource.php",
        "aaSorting": [[2, 'asc']],
        "oLanguage": {
            "sLengthMenu": "Display _MENU_ Records",
            "sZeroRecords": "No Record Found",
            "sInfo": "Showing _START_ to _END_ of _TOTAL_ Records",
            "sInfoEmpty": "Showing 0 to 0 of 0 Records",
            "sInfoFiltered": "(filtered from _MAX_ total Records)"
        },
        "fnDrawCallback": function() {
        },
        "fnRowCallback": function(nRow, aData, iDisplayIndex) {
            $(nRow).attr('id', aData[0]);
            $(nRow).attr('cname', aData[2]);
            return nRow;
        },
        "fnServerData": function(sSource, aoData, fnCallback) {
            aoData.push({
                "name": "action",
                "value": "getCountryList"
                });
            aoData.push({
				"name": "lan",
				"value": lan
			});
            aoData.push({
                "name": "userName",
                "value": userName
            });
            $.ajax({
                "dataType": 'json',
                "type": "POST",
                "url": sSource,
                "data": aoData,
                "success": function(json) {
                    fnCallback(json);
                }
            });
        },
        "aoColumns": [{
                "bVisible": false
            }, {
                "sClass": "SL",
                "bSortable": false,
                "sWidth": "10%"
            }, {
                "sClass": "Countries",
                "bSortable": true,
                "sWidth": "85%"
            }]
    });

    $('#gridDataCountry').click(function(event) {
        var id = $(event.target.parentNode).attr('id');
        SelCountryId = $(event.target.parentNode).attr('id');
        SelCountryName = $(event.target.parentNode).attr('cname');
        var aData;
        var t;
        $(oTable_country.fnSettings().aoData).each(function() {
            if ($(this.nTr).attr('id') == id) {
                $(this.nTr).addClass('row_selected');
                aData = oTable_country.fnGetData(this.nTr);
            } else
                $(this.nTr).removeClass('row_selected');
        });
        if (aData) {
            checkStatus = $('#ssel').prop('checked');
            if (checkStatus == true) {
                mode = 'display';
                $('#sselSec').show();
                oTable_product.fnDraw();
            } else {
                mode = 'edit';
                $('#sselSec').show();
                oTable_product.fnDraw();
            }
        }
    });

    oTable_product = $("#gridDataProduct").dataTable({
        "bFilter": true,        
        "sPaginationType": "full_numbers",
        "bSort": true,
        "bInfo": true,
        "bPaginate": true,
        "bSortClasses": false,
        "bProcessing": true,
        "bServerSide": true,
        "aaSorting": [[3, 'asc'], [2, 'asc']],
        "aLengthMenu": [[25, 50, 100], [25, 50, 100]],
        "iDisplayLength": 25,
        "sAjaxSource": baseUrl + "country_product_datasource.php",
        "oLanguage": {
            "sLengthMenu": "Display _MENU_ Records",
            "sZeroRecords": "No Record Found",
            "sInfo": "Showing _START_ to _END_ of _TOTAL_ Records",
            "sInfoEmpty": "Showing 0 to 0 of 0 Records",
            "sInfoFiltered": "(filtered from _MAX_ total Records)"
        },
        "fnDrawCallback": function(oSettings) {
            if (oSettings.aiDisplay.length == 0) {
                return;
            }
            var nTrs = $('#gridDataProduct tbody tr');
            var iColspan = nTrs[0].getElementsByTagName('td').length;
            var sLastGroup = "";
            for (var i = 0; i < nTrs.length; i++) {
                var iDisplayIndex = i;
                var sGroup = oSettings.aoData[oSettings.aiDisplay[iDisplayIndex]]._aData[3];
                if (sGroup != sLastGroup) {
                    var nGroup = document.createElement('tr');
                    var nCell = document.createElement('td');
                    nCell.colSpan = iColspan;
                    nCell.className = "group";
                    nCell.innerHTML = sGroup;
                    nGroup.appendChild(nCell);
                    nTrs[i].parentNode.insertBefore(nGroup, nTrs[i]);
                    sLastGroup = sGroup;
                }
            }
            $('td input.datacell', oTable_product.fnGetNodes()).each(function() {
                $(this).click(function() {
                    var nTr = this.parentNode.parentNode;
                    var aData = oTable_product.fnGetData(nTr);
                    var checkcon = $(this).prop('checked');
                    var CountryProductId = aData[0];
                    var ItemNo = aData[4];
                    var ItemGroupId = aData[5];
                    $.ajax({
                        "type": "POST",
                        "url": baseUrl + "country_product_datasource.php",
                        "data": {
                            action: 'insertAllorOneMapping',
                            CountryProductId: CountryProductId,
                            ItemNo: ItemNo,
                            ItemGroupId: ItemGroupId,
                            jUserId: userid,
                            language: lan,
                            SelCountryId: SelCountryId,
                            checkVal: checkcon
                        },
                        "success": function(response) {

                            /*
                             var oSettings = oTable_product.fnSettings();
                             var page = Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength);
                             oTable_product.fnDraw();
                             oTable_product.fnPageChange(page);
                             */
                            $msgType = JSON.parse(response)['msgType'];
                            $msg = JSON.parse(response)['msg']
                            if ($msgType == "success") {
                                oTable_product.fnDraw();
                                onSuccessMsg($msg);
                            } else {
                                onErrorMsg($msg);
                            }
                        }
                    });
                });
            });
        },
        "fnRowCallback": function(nRow, aData, iDisplayIndex) {
            return nRow;
        },
        "fnServerData": function(sSource, aoData, fnCallback) {
            aoData.push({
                "name": "action",
                "value": "getProductList"
            });
            
            aoData.push({
				"name": "lan",
				"value": lan
			});
            aoData.push({
                "name": "SelCountryId",
                "value": SelCountryId
            });
            aoData.push({
                "name": "mode",
                "value": mode
            });
            $.ajax({
                "dataType": 'json',
                "type": "POST",
                "url": sSource,
                "data": aoData,
                "success": function(json) {
                    fnCallback(json);
                }
            });
        },
        "aoColumns": [{
                "bVisible": false
            }, {
                "sClass": "ProductCode",
                "bSortable": false,
                "sWidth": "25%"
            }, {
                "sClass": "ProductName",
                "bSortable": true,
                "sWidth": "70%"
            }, {
                "sClass": "ProductGroup",
                "bSortable": false,
                "bVisible": false
            }]
    });

});