var shipmentEntryTable;
var RecordId = '';
var FundingSourceId = 0;
var userId = '';
var engbId = '';

var $ = jQuery.noConflict();

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


    $('#ItemGroup').change(function() {
        onComboProductList();
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
                    "bSortable": false,
					"bVisible": false
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
});
