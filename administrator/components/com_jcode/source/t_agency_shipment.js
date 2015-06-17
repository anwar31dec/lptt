var shipmentEntryTable;
var RecordId = '';
var FundingSourceId = 0;
var userId = '';
var engbId = '';

var $ = jQuery.noConflict();

function resetForm(id) {
    $('#' + id).each(function() {
        this.reset();
    });
}

function onListPanel() {
    $('#filter-panel').show();
    $('#list-panel, .btn-form').show();
    $('#form-panel, .btn-list').hide();
}

function onFormPanel() {
    resetForm("form");
    RecordId = '';

    var myDate = new Date();
    var eMonth = myDate.getMonth();
    if (eMonth.toString().length == 1) {
        startMonth = "0" + (eMonth + 1);
    } else {
        startMonth = (eMonth + 1);
    }
    if (myDate.getDate().toString().length == 1) {
        startDate = "0" + myDate.getDate();
    } else {
        startDate = myDate.getDate();
    }
    var prettyDate = startDate + '/' + startMonth + '/' + myDate.getFullYear();
    $("#ShipmentDate").val(prettyDate);
    $("#CountryId").val($("#ACountryId").val());
    $("#OwnerTypeId").val($("#OwnerType").val());
    $("#ItemGroup").val($("#item-group").val());
    //$("#ItemGroup").val($("#item-group").val());
    $("#FundingSourceId").val($("#AFundingSourceId").val());
    $("#ShipmentStatusId").val($("#ASStatusId").val());
	onComboProductList();
    onComboFundingSource();
			
    $('#list-panel, .btn-form').hide();
    $('#form-panel, .btn-list').show();
    $('#filter-panel').hide();
}

function onEditPanel() {
    $('#list-panel, .btn-form').hide();
    $('#form-panel, .btn-list').show();
    $('#filter-panel').hide();
    //$("#ShipmentDate").val(prettyDate);
    $("#CountryId").val($("#ACountryId").val());
	//$("#OwnerTypeId").val($("#OwnerType").val());
    $("#ItemGroupId").val($("#ItemGroup").val());
}

function addCommas(nStr) {
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}

function removeCommas(nStr) {
    var temp = nStr.split(',');
    return temp.join('');
}

/***************************************************Combo*************************************************/

function onComboAgencyName() {
    $.getJSON(baseUrl + "t_combo.php", {
        action: 'getFundingSource',
        ItemGroup: $("#item-group").val()
    }, function(response) {
	 str = '<option value="">All Funding Source</option>';
        for (var i = 0; i < response.length; i++) {
			str += '<option value="' + response[i].FundingSourceId + '">' + response[i].FundingSourceName + '</option>';
            //$('#AFundingSourceId').append($('<option></option>').val(response[i].FundingSourceId).html(response[i].FundingSourceName));
        }
		$('#AFundingSourceId').html(str);
    });
}

function onComboShipmentStatus() {
    $.getJSON(baseUrl + "t_combo.php", {
        action: 'getShipmentStatus'
    }, function(response) {
        for (var i = 0; i < response.length; i++) {
		
            $('#ASStatusId').append($('<option></option>').val(response[i].ShipmentStatusId).html(response[i].ShipmentStatusDesc));
        }
    });
}



function onComboFundingSource() {
    $.getJSON(baseUrl + "t_combo.php", {
        action: 'getFundingSource',
        ItemGroup: $("#ItemGroup").val()
    }, function(response) {
        str = '<option value="">Funding Source</option>';
        for (var i = 0; i < response.length; i++) {
            str += '<option value="' + response[i].FundingSourceId + '">' + response[i].FundingSourceName + '</option>';
        }
        $('#FundingSourceId').html(str);
        //$('#FundingSourceId').val(FundingSourceId);
    });
}

function onComboShipmentStatusEntry() {
    $.getJSON(baseUrl + "t_combo.php", {
        action: 'getShipmentStatus'
    }, function(response) {
        for (var i = 0; i < response.length; i++) {
            $('#ShipmentStatusId').append($('<option></option>').val(response[i].ShipmentStatusId).html(response[i].ShipmentStatusDesc));
        }
    });
}

function onComboItemNoEntry() {
    $.getJSON(baseUrl + "t_combo.php", {
        action: 'getItemList'
    }, function(response) {
        for (var i = 0; i < response.length; i++) {
            $('#ItemNo').append($('<option></option>').val(response[i].ItemNo).html(response[i].ItemName));
        }
    });
}

$('#ACountryId').change(function() {
    shipmentEntryTable.fnDraw();
});

$('#AFundingSourceId').change(function() {
    shipmentEntryTable.fnDraw();
});

$('#ASStatusId').change(function() {
    shipmentEntryTable.fnDraw();
});

$('#item-group').change(function() {
	onComboAgencyName();
    shipmentEntryTable.fnDraw();
});

$('#OwnerType').change(function() {
    shipmentEntryTable.fnDraw();
});
/************************Save/Delete******************************/

function onConfirmWhenAddEdit() {
    $.ajax({
        "type": "POST",
        "url": baseUrl + "stage_two_datasource.php",
        "data": $('#form').serialize()+ '&jUserId=' + userId + '&language=' + engbId,
        "success": function(response) {
            $msgType = JSON.parse(response)['msgType'];
            $msg = JSON.parse(response)['msg'];
            if ($msgType == "success") {
                shipmentEntryTable.fnDraw();
                onSuccessMsg($msg);
                onListPanel();
            } else {
                onErrorMsg($msg);
            }
        }
    });
}

function onConfirmWhenDelete() {
    $.ajax({
        "type": "POST",
        "url": baseUrl + "stage_two_datasource.php",
        "data": 'action=deleteAgencyShipment&RecordId=' + RecordId+ '&jUserId=' + userId + '&language=' + engbId,
        "success": function(response) {
            $msgType = JSON.parse(response)['msgType'];
            $msg = JSON.parse(response)['msg'];
            if ($msgType == "success") {
                shipmentEntryTable.fnDraw();
                onSuccessMsg($msg);
            } else {
                onErrorMsg($msg);
            }
        }
    });
}

$('#form').parsley({listeners: {
        onFieldValidate: function(elem) {
            if (!$(elem).is(':visible')) {
                return true;
            }
            return false;
        },
        onFormSubmit: function(isFormValid, event) {
            if (isFormValid) {
                onConfirmWhenAddEdit();
                return false;
            }
        }
    }});

function onComboProductList() {
    $.getJSON(baseUrl + "t_combo.php", {
        action: 'getItemListByGroup',
        ItemGroupId: $('#ItemGroup').val()

    }, function(response) {
        str = '<option value="">Select Product</option>';
        for (var i = 0; i < response.length; i++) {
            str += '<option value="' + response[i].ItemNo + '">' + response[i].ItemName + '</option>';
        }
        $('#ItemNo').html(str);
    });
}

$(function() {
    userId = $('#userId').val();
    engbId = $('#en-GBId').val();
    //$("#ShipmentDate").mask({placeholder:"dd/mm/yyyy"});
   
    $.each(gItemGroupList, function(i, obj) {
        $('#item-group').append($('<option></option>').val(obj.ItemGroupId).html(obj.GroupName));
        $('#ItemGroup').append($('<option></option>').val(obj.ItemGroupId).html(obj.GroupName));
    });

	$('#item-group').val(gUserItemGroupId);
    //$('#ItemGroup').val(gUserItemGroupId);

    onComboProductList();

    $.each(gCountryList, function(i, obj) {
        $('#ACountryId').append($('<option></option>').val(obj.CountryId).html(obj.CountryName));
        $('#CountryId').append($('<option></option>').val(obj.CountryId).html(obj.CountryName));
    });

    $("#ItemGroup").change(function() {
        onComboFundingSource();
		onComboProductList();
    });
	
    $('#ACountryId').val(gUserCountryId);
    $('#CountryId').val(gUserCountryId);

    $.each(gOwnerTypeList, function(i, obj) {
        $('#OwnerType').append($('<option></option>').val(obj.OwnerTypeId).html(obj.OwnerTypeName));
    });

    $.each(gOwnerTypeList, function(i, obj) {
        $('#OwnerTypeId').append($('<option></option>').val(obj.OwnerTypeId).html(obj.OwnerTypeName));
    });

    onComboAgencyName();
    onComboShipmentStatus();
    onComboShipmentStatusEntry();
    onComboItemNoEntry();

    onListPanel();
    resetForm("form");

    $('.btn-form-success').click(function() {
        $("#form").submit();
    });

    $(".datepicker").datepicker({
        "dateFormat": "dd/mm/yy"
    });

   

    $('body').animate({opacity: 1}, 500, function() {
        shipmentEntryTable = $('#shipmentEntryTable').dataTable({
            "bFilter": true,
            "bSort": true,
            "bInfo": true,
            "bPaginate": true,
            "bSortClasses": false,
            "bProcessing": true,
            "bServerSide": true,
            "aaSorting": [[9, 'asc'], [5, 'desc']],
            "sPaginationType": "full_numbers",
            "aLengthMenu": [[25, 50, 100], [25, 50, 100]],
            "iDisplayLength": 25,
            "sAjaxSource": baseUrl + "stage_two_datasource.php",
            "fnDrawCallback": function(oSettings) {
                if (oSettings.aiDisplay.length == 0) {
                    return;
                }
                var nTrs = $('#shipmentEntryTable tbody tr');
                var iColspan = nTrs[0].getElementsByTagName('td').length;
                var sLastGroup = "";
                for (var i = 0; i < nTrs.length; i++) {
                    var iDisplayIndex = i;
                    var sGroup = oSettings.aoData[oSettings.aiDisplay[iDisplayIndex]]._aData[9];
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
                $('a.itmEdit', shipmentEntryTable.fnGetNodes()).each(function() {
                    $(this).click(function() {
                        var nTr = this.parentNode.parentNode;
                        var aData = shipmentEntryTable.fnGetData(nTr);
                        RecordId = aData[0];
						onComboProductList();
                        onComboFundingSource();
                        $('#RecordId').val(aData[0]);
                        $('#ShipmentDate').val(aData[5]);                       
                        $('#Qty').val(removeCommas(aData[7]));
                        $('#ShipmentStatusId').val(aData[11]);
                        $('#CountryId').val(aData[12]);
                        $('#ItemGroup').val(aData[14]);
						$('#OwnerTypeId').val(aData[15]);						                   
						FundingSourceId = aData[10];
                        $("body").animate({opacity: 1}, 2000, function() {
                            // initialize
							$('#FundingSourceId').val(aData[10]);							
                            $('#ItemNo').val(aData[13]);
							//console.log(aData[13]);     
                        });

                        msg = "Do you really want to edit this record?";
                        onCustomModal(msg, "onEditPanel");
                    });
                });
                $('a.itmDrop', shipmentEntryTable.fnGetNodes()).each(function() {
                    $(this).click(function() {
                        var nTr = this.parentNode.parentNode;
                        var aData = shipmentEntryTable.fnGetData(nTr);
                        RecordId = aData[0];
                        msg = "Do you really want to delete this record?";
                        onCustomModal(msg, "onConfirmWhenDelete");
                    });
                });
            },
            "fnServerData": function(sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "action",
                    "value": 'getAgencyShipment',
                });
                aoData.push({
                    "name": "lan",
                    "value": lan
                });
                aoData.push({
                    "name": "baseUrl",
                    "value": baseUrl
                });

                aoData.push({
                    "name": "ACountryId",
                    "value": $('#ACountryId').val()
                });
                aoData.push({
                    "name": "AFundingSourceId",
                    "value": $('#AFundingSourceId').val()
                });
                aoData.push({
                    "name": "ASStatusId",
                    "value": $('#ASStatusId').val()
                });

                aoData.push({
                    "name": "ItemGroup",
                    "value": $('#item-group').val()
                });

                aoData.push({
                    "name": "OwnerType",
                    "value": $('#OwnerType').val()
                });

                $.ajax({
                    "dataType": 'json',
                    "type": "POST",
                    "url": sSource,
                    "data": aoData,
                    "success": fnCallback
                });
            },
            "aoColumns": [{
                   "bVisible": false
                }, {
                    "sClass": "SL",
                    "sWidth": "5%",
                    "bSortable": false
                }, {
                    "sClass": "GroupName",
                    "sWidth": "12%",
                    "bVisible": true,
                    "bSortable": false
                }, {
                    "sClass": "ItemName",
                    "sWidth": "25%",
                    "bSearchble": true,
                    "bSortable": true
                }, {
                    "sClass": "Status",
                    "sWidth": "12%",
                    "bSearchble": true,
                    "bSortable": true
                }, {
                    "sClass": "Date",
                    "sWidth": "12%",
                    "bSearchble": true,
                    "bSortable": true
                }, {
                    "sClass": "OwnerTypeName",
                    "sWidth": "10%",
                    "bSearchble": true,
                    "bSortable": true
                }, {
                    "sClass": "Quantity",
                    "sWidth": "12%",
                    "bSearchble": true,
                    "bSortable": true
                }, {
                    "sClass": "Action",
                    "sWidth": "10%",
                    "bSortable": false
                }, {
                    "sClass": "Agency",
                    "bVisible": false
                }, {
                    "sClass": "ItemGroupId",
                    "bVisible": false
                }]
        });
    });
    onComboFundingSource();
   $(".numberinput").forceNumeric();
});
