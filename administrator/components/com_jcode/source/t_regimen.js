var RegimenTable;
var RegimenItemListTable;
var RegimenId = '';
var OptionId;
var OptionNext;
var RegimenItemList = new Array();
var Mode = '';
var RegimenItemId;
var ComTable;
var userId = '';
var engbId = '';
var regimenMasterListId='';

/****************************************************Regimen Form**************************************************/

var $ = jQuery.noConflict();

function resetForm(id) {
    $('#' + id).each(function() {
        this.reset();
    });
}

/* When you click on "Back to List" button*/
function onListPanel() {
    $('#form-panel, .btn-list').hide();    
    $('#list-panel, .btn-form, #PrintBTN, #PrintBTN1, #regimen-filter').show();
}

/* When you click on "Add Record" button*/
function onFormPanel() {
    resetForm("Regimen_form");
    RegimenId = '';
    $('#form-panel, .btn-list').show();
    $('#list-panel, .btn-form, #PrintBTN, #PrintBTN1, #regimen-filter').hide();
}


function onEditPanel() {
    $('#form-panel, .btn-list').show();
    $('#list-panel, .btn-form, #PrintBTN, #PrintBTN1, #regimen-filter').hide();
    $('#GenderTypeId').val($('#GenderTypeId').val());
}

function onComboItemFormulationName() {
    $.getJSON(baseUrl + "t_combo.php", {
        action: 'getFormulationByGroup',
        ItemGroupId: $('#ItemGroupId').val()
    }, function(response) {
        for (var i = 0; i < response.length; i++) {
            $('#FormulationId').append($('<option></option>').val(response[i].FormulationId).html(response[i].FormulationName));
        }
    });
}

function onConfirmWhenAddEdit() {
    $.ajax({
        "type": "POST",
        "url": baseUrl + "t_regimen_server.php",
        "data": $('#Regimen_form').serialize() + "&RegimenName=" + $('#regimenMaster-list option:selected').text() + '&jUserId=' + userId + '&language=' + engbId,
        "success": function(response) {
            $msgType = JSON.parse(response)['msgType'];
            $msg = JSON.parse(response)['msg'];
            if ($msgType == "success") {
                RegimenTable.fnDraw();
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
        "url": baseUrl + "t_regimen_server.php",
        "data": 'action=deleteRegimenData&RegimenId=' + RegimenId + '&jUserId=' + userId + '&language=' + engbId,
        "success": function(response) {
            $msgType = JSON.parse(response)['msgType'];
            $msg = JSON.parse(response)['msg'];
            if ($msgType == "success") {
                RegimenTable.fnDraw();
                onSuccessMsg($msg);
            } else {
                onErrorMsg($msg);
            }
        }
    });
}

$('#Regimen_form').parsley({
    listeners: {
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
    }
});

/***********************************************Regimen Item Form***********************************************/
function ItemEditPanel() {
    $('#RegimentListModal').modal('show');
    comboDefaultRegimenItemListData(RegimenId);
    comboSelectedRegimenItemListData(RegimenId, OptionId);
    $('#combtn').html(OptionId);
    Mode = "edit";
    RegimenItemList = new Array();
}

function testRegimenSel() {
    if (RegimenId == '') {
        msg = "Select a Regimen first.";
        onErrorMsg(msg);
    } else {
        $("#openModal").attr("data-toggle", "modal");
        $("#openModal").attr("href", "#RegimentListModal");
        getOption(RegimenId);
        Mode = "add";
        RegimenItemList = new Array();
        $('body').animate({opacity: 1}, 100, function() {
            comboDefaultRegimenItemListData(RegimenId);
            //comboSelectedRegimenItemListData(RegimenId, OptionNext); 
            $('#selectedBox2').html("");
        });
    }
}

function testCombinationSel() {
    if (RegimenId == '') {
        msg = "Select a Regimen first.";
        onErrorMsg(msg);
    } else {
        ComTable.fnDraw();
        $('body').animate({opacity: 1}, 200, function() {
            var sum = 0;
            $(".datacell").each(function() {
                var val = parseFloat($(this).val());
                sum += val;
            });
            $('#comTotal').html(sum + '%');

        });
        $("#openComModal").attr("data-toggle", "modal");
        $("#openComModal").attr("href", "#CombinationModal");
    }
}

function getOption(RegimenId) {
    var Id = RegimenId;
    $.ajax({
        "type": "POST",
        "url": baseUrl + 't_regimen_server.php',
        "data": {
            action: 'getOptionNumber',
            RegimenId: Id,
            Country: $('#country-list').val()
        },
        "success": function(response) {
            response = JSON.parse(response);
            if (response.success == true) {
                OptionId = parseInt(response.OptionId);
                if (isNaN(OptionId))
                    OptionId = 0;
                OptionNext = OptionId + 1;
                OptionId = OptionNext;
                $('#combtn').html(OptionNext);
            }
        }
    });
}

function comboDefaultRegimenItemListData(RegimenId) {
    var Id = RegimenId;
    $.getJSON(baseUrl + "t_regimen_server.php", {
        action: 'getDefaultRegimenItemListData',
        RegimenId: Id,
        ItemGroupId: $('#ItemGroupId').val(),
        Country: $('#country-list').val()
    }, function(response) {
        str = '';
        for (var i = 0; i < response.length; i++) {
            str += '<option value="' + response[i].ItemNo + '">' + response[i].ItemName + '</option>';
        }
        $('#selectedBox1').html(str);
    });
}

function comboSelectedRegimenItemListData(RegimenId, OptionId) {
    var Id = RegimenId;
    $.getJSON(baseUrl + "t_regimen_server.php", {
        action: 'getSelectedRegimenItemListData',
        RegimenId: Id,
        OptionId: OptionId,
        Country: $('#country-list').val()
    }, function(response) {
        str = '<option style="display: none;" value=""></option>';
        for (var i = 0; i < response.length; i++) {
            str += '<option value="' + response[i].ItemNo + '">' + response[i].ItemName + '</option>';
        }
        $('#selectedBox2').html(str);
    });
}

$('.closeModal').click(function() {
    RegimenItemListTable.fnDraw();
    $('#selectedBox2').html("");
});

$('#btnSelect').click(function() {
    currentSelected1 = $('#selectedBox1').val();
    lengthSelecetBox1 = currentSelected1.length;
    $('#selectedBox1 option:selected').appendTo('#selectedBox2');

    for (i = 0; i < lengthSelecetBox1; i++) {
        RegimenItem = {};
        RegimenItem['Id'] = 0;
        RegimenItem['ItemNo'] = currentSelected1[i];
        RegimenItemList.push(RegimenItem);
    }
    //console.log(RegimenItemList);
    return false;
});

$('#btnRemove').click(function() {
    currentSelected2 = $('#selectedBox2').val();
    lengthSelecetBox2 = currentSelected2.length;
    $('#selectedBox2 option:selected').appendTo('#selectedBox1');

    for (i = 0; i < lengthSelecetBox2; i++) {
        RegimenItem = {};
        RegimenItem['Id'] = -1;
        RegimenItem['ItemNo'] = currentSelected2[i];
        RegimenItemList.push(RegimenItem);
    }
    console.log(RegimenItemList);
    return false;
});

$('#btnSelectAll').click(function() {
    var lengthSelecetBox3 = 0;
    currentSelected3Array = [];
    $('#selectedBox1 option').each(function() {
        currentSelected3 = {};
        currentSelected3['value'] = $(this).val();
        currentSelected3Array.push(currentSelected3);
        $(this).appendTo('#selectedBox2');
        lengthSelecetBox3++;
    });
    for (i = 0; i < lengthSelecetBox3; i++) {
        RegimenItem = {};
        RegimenItem['Id'] = 0;
        RegimenItem['ItemNo'] = currentSelected3Array[i]['value'];
        RegimenItemList.push(RegimenItem);
    }
    return false;
});

$('#btnRemoveAll').click(function() {
    var lengthSelecetBox4 = 0;
    currentSelected4Array = [];
    $('#selectedBox2 option').each(function() {
        currentSelected4 = {};
        currentSelected4['value'] = $(this).val();
        currentSelected4Array.push(currentSelected4);
        $(this).appendTo('#selectedBox1');
        lengthSelecetBox4++;
    });
    for (i = 0; i < lengthSelecetBox4; i++) {
        RegimenItem = {};
        RegimenItem['Id'] = -1;
        RegimenItem['ItemNo'] = currentSelected4Array[i]['value'];
        RegimenItemList.push(RegimenItem);
    }
    return false;
});

function saveRegimenItem() {
    $.ajax({
        "type": "POST",
        "url": baseUrl + "t_regimen_server.php",
        "data": {
            "action": "saveRegimenItemList",
            'RegimenItemList': JSON.stringify(RegimenItemList),
            'RegimenId': RegimenId,
            'OptionId': OptionId,
            'Country': $('#country-list').val(),
            'Mode': Mode
        },
        success: function(response) {
            if (response = 1) {
                $('#RegimentListModal').modal('hide');
                RegimenItemListTable.fnDraw();
                if (Mode == 'add') {
                    msg = "Item added Successfully.";
                } else {
                    msg = "Item updated Successfully.";
                }
                onSuccessMsg(msg);
            } else {
                msg = "Server processing Error.";
                onErrorMsg(msg);
            }
        }
    });
}


function saveCombinationItem() {
    var sum = 0;
    var ComName = new Array();
    var ComPer = new Array();

    $(".datacell").each(function() {
        var val = parseFloat($(this).val())
        ComPer.push(val);
        sum += val;
    })

    $(".ComName").each(function() {
        var comname = $(this).text().split("-").pop();
        ComName.push(comname);
    })

    if (sum == 100 || sum < 100) {
        $.ajax({
            "type": "POST",
            "url": baseUrl + "t_regimen_server.php",
            "data": {
                "action": "updateCombinationPer",
                'ComOptionId': JSON.stringify(ComName),
                'ComPercentage': JSON.stringify(ComPer),
                'RegimenId': RegimenId,
                'Country': $('#country-list').val()
            },
            success: function(response) {
                if (response = 1) {
                    $('#CombinationModal').modal('hide');
                    RegimenItemListTable.fnDraw();
                    msg = "Combination Updated Successfully.";
                    onSuccessMsg(msg);
                } else {
                    msg = "Server processing Error.";
                    onErrorMsg(msg);
                }
            }
        });
    } else {
        msg = "Percentage sum must be equel to or less then 100.";
        onErrorMsg(msg);
    }
}

function onConfirmWhenCombinationDelete() {
    $.ajax({
        "type": "POST",
        "url": baseUrl + "t_regimen_server.php",
        "data": {
            'action': "deleteRegimenItemList",
            'RegimenItemId': RegimenItemId
        },
        "success": function(response) {
            if (response == 1) {
                RegimenItemListTable.fnDraw();
                msg = "Item has been deleted successfully.";
                onSuccessMsg(msg);
            } else {
                msg = "Server processing Error.";
                onErrorMsg(msg);
            }
        }
    });
}


function onComboCaseTypeName() {
	$.getJSON(baseUrl + "t_combo.php", {
		action : 'getCasseTypeName',
		FormulationId : $("#FormulationId").val()
	}, function(response) {
		str = '<option value="">Case Type Name*</option>';
		for (var i = 0; i < response.length; i++) {
			str += '<option value="' + response[i].RegMasterId + '">' + response[i].RegimenName + '</option>';
		}
		$('#regimenMaster-list').html(str);
		$('#regimenMaster-list').val(regimenMasterListId);
	});
}

/*************************************************ready Function***********************************************/
$(function() {

    userId = $('#userId').val();
    engbId = $('#en-GBId').val();

    onListPanel();
    resetForm("Regimen_form");

    $('.btn-form-success').click(function() {
        $("#Regimen_form").submit();
    });

    $.each(gCountryList, function(i, obj) {
        $('#country-list').append($('<option></option>').val(obj.CountryId).html(obj.CountryName));
    });

    $('#country-list').val(gUserCountryId);
    
      
    $("#FormulationId").change(function() {
		onComboCaseTypeName();
	});

//    $.each(gRegimenMasterList, function(i, obj) {
//        $('#regimenMaster-list').append($('<option></option>').val(obj.RegMasterId).html(obj.RegimenName));
//    });

    $.each(gGenderTypeList, function(i, obj) {
        $('#GenderTypeId').append($('<option></option>').val(obj.GenderTypeId).html(obj.GenderType));

    });

    $('#country-list').change(function() {
        //RegimenItemListTable.fnDraw();
    });
//Added    
    $('#country-list').change(function() {
        onComboItemFormulationName();
        RegimenTable.fnDraw();
    });
    //end   
    $.each(gItemGroupList, function(i, obj) {
        $('#ItemGroupId').append($('<option></option>').val(obj.ItemGroupId).html(obj.GroupName));
    });

    $('#ItemGroupId').val(gUserItemGroupId);

    $('#ItemGroupId').change(function() {
        onComboItemFormulationName();
        RegimenTable.fnDraw();
    });

    $('body').animate({opacity: 1}, 500, function() {

        onComboItemFormulationName();
        
        // "bFilter" : true,		
		// "bSort" : true,
		// "bInfo" : true,
		// "bPaginate" : true,
		// "bSortClasses" : false,
		// "bProcessing" : true,
		// "bServerSide" : true,
		// "aaSorting" : [[9, 'asc'], [2, 'asc']],
		// "aLengthMenu" : [[25, 50, 100], [25, 50, 100]],
		// "iDisplayLength" : 25,
		// "sPaginationType" : "full_numbers",

        RegimenTable = $('#RegimenTable').dataTable({
            "bFilter": true,           
            "bSort": true,
            "bInfo": true,
            "bPaginate": true,
            "bSortClasses": false,
            "bProcessing": true,
            "bServerSide": true,
            "aaSorting": [[2, 'asc']],
            "sPaginationType": "full_numbers",
            "sAjaxSource": baseUrl + "t_regimen_server.php",
            "fnDrawCallback": function(oSettings) {
                if (oSettings.aiDisplay.length == 0) {
                    return;
                }

                var nTrs = $('#RegimenTable tbody tr');
                var iColspan = nTrs[0].getElementsByTagName('td').length;
                var sLastGroup = "";
                for (var i = 0; i < nTrs.length; i++) {
                    var iDisplayIndex = i;
                    var sGroup = oSettings.aoData[oSettings.aiDisplay[iDisplayIndex]]._aData[4];
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
                $('a.itmEdit', RegimenTable.fnGetNodes()).each(function() {
                    $(this).click(function() {
                        var nTr = this.parentNode.parentNode;
                        var aData = RegimenTable.fnGetData(nTr);
                        RegimenId = aData[0];
                        $('#RegimenId').val(aData[0]);
                        $('#RegimenName').val(aData[2]);
                        $('#FormulationId').val(aData[5]);
                        //$('#regimenMaster-list').val(aData[6]);
                        $('#GenderTypeId').val(aData[7]);  
                        onComboCaseTypeName();
                        regimenMasterListId=aData[6];
                        var msg = "Do you really want to edit this record?";
                        onCustomModal(msg, "onEditPanel");
                    });
                });
                $('a.itmDrop', RegimenTable.fnGetNodes()).each(function() {
                    $(this).click(function() {
                        var nTr = this.parentNode.parentNode;
                        var aData = RegimenTable.fnGetData(nTr);
                        RegimenId = aData[0];
                        var msg = "Do you really want to delete this record?";
                        onCustomModal(msg, "onConfirmWhenDelete");
                    });
                });
            },
            "fnRowCallback": function(nRow, aData, iDisplayIndex) {
                $(nRow).attr('id', aData[0]);
                return nRow;
            },
            "fnServerData": function(sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "action",
                    "value": 'getRegimenData'
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
                    "name": "ItemGroupId",
                    "value": $('#ItemGroupId').val()
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
                },
                {
                    "sClass": "SL",
                    "sWidth": "8%",
                    "bSortable": false
                },
                {
                    "sClass": "Regimen"
                },
                {
                    "sClass" : "center-aln",
                    "sWidth": "15%",
                    "bSortable": false
                },
                {
                    "sClass": "FormulationName",
                    "bVisible": false
                }]
        });
    });
    
    onComboCaseTypeName();


    /*	$('#RegimenTable').click(function(event) {
     var id = $(event.target.parentNode).attr('id');
     var aData;
     $(RegimenTable.fnSettings().aoData).each(function() {
     if ($(this.nTr).attr('id') == id) {
     $(this.nTr).addClass('row_selected');
     aData = RegimenTable.fnGetData(this.nTr);
     RegimenId = id; 
     } else
     $(this.nTr).removeClass('row_selected');
     });
     if (aData) {
     //RegimenItemListTable.fnDraw();
     }
     });
     
     RegimenItemListTable = $('#RegimenItemListTable').dataTable({
     "bFilter": true,
     "bJQueryUI": true,
     "bSort": true,
     "bInfo": true,
     "bPaginate": true,
     "bSortClasses": false,
     "bProcessing": true,
     "bServerSide": true,
     "aaSorting": [[4, 'asc']],
     "sPaginationType": "full_numbers",
     "sScrollX": "100%",
     "sAjaxSource": baseUrl + "t_regimen_server.php",
     "fnDrawCallback": function(oSettings) {
     if (oSettings.aiDisplay.length == 0) {
     return;
     }
     var nTrs = $('#RegimenItemListTable tbody tr');
     var iColspan = nTrs[0].getElementsByTagName('td').length;
     var sLastGroup = "";
     for (var i = 0; i < nTrs.length; i++) {
     var iDisplayIndex = i;
     var sGroup = oSettings.aoData[oSettings.aiDisplay[iDisplayIndex]]._aData[6];
     if (sGroup != sLastGroup) {
     var nGroup = document.createElement('tr');
     var nCell = document.createElement('td');
     nCell.colSpan = iColspan;
     nCell.className = "group";
     nCell.innerHTML = 'Combination ' + sGroup;
     nGroup.appendChild(nCell);
     nTrs[i].parentNode.insertBefore(nGroup, nTrs[i]);
     sLastGroup = sGroup;
     }
     }
     $('a.itmEdit', RegimenItemListTable.fnGetNodes()).each(function() {
     $(this).click(function() {
     var nTr = this.parentNode.parentNode;
     var aData = RegimenItemListTable.fnGetData(nTr);
     OptionId = aData[4];
     var msg = "Do you really want to edit this record?";
     onCustomModal(msg, "ItemEditPanel");
     });
     });
     $('a.itmDrop', RegimenItemListTable.fnGetNodes()).each(function() {
     $(this).click(function() {	
     var nTr = this.parentNode.parentNode;
     var aData = RegimenItemListTable.fnGetData(nTr);
     RegimenItemId = aData[0];
     var msg = "Do you really want to delete this combination Item?";
     onCustomModal(msg, "onConfirmWhenCombinationDelete");
     });
     });
     },
     "fnServerData": function(sSource, aoData, fnCallback) {
     aoData.push({
     "name": "action",
     "value": 'getRegimenItemListData',
     "lan":lan                 
     });			
     aoData.push({
     "name" : "lan",
     "value" : lan
     });
     aoData.push({
     "name" : "baseUrl",
     "value" : baseUrl
     });
     aoData.push({
     "name": "Country",
     "value": $('#country-list').val()
     });					
     aoData.push({
     "name": "RegimenId",
     "value": RegimenId
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
     },
     {
     "sClass": "SL",
     "sWidth": "10%",
     "bSortable": false
     },
     {
     "sClass": "ItemName",
     "sWidth": "75%"
     },
     {
     "sClass": "Action",
     "sWidth": "10%",
     "bSortable": false
     },
     {
     "sClass": "Combination",
     "bVisible": false
     }]
     });
     
     ComTable = $('#CombinationTable').dataTable({
     "bFilter": false,
     "bJQueryUI": true,
     "bSort": false,
     "bInfo": false,
     "bPaginate": false,
     "bSortClasses": false,
     "bProcessing": true,
     "bServerSide": true,
     "sScrollX": "100%",
     "sAjaxSource": baseUrl + "t_regimen_server.php",
     "fnDrawCallback": function() {
     $('td input.datacell', ComTable.fnGetNodes()).change(function() {
     var sum = 0;
     $('td input.datacell', ComTable.fnGetNodes()).each(function() { 
     var value = parseFloat($(this).val().trim());
     sum = sum + value;
     })  
     $('#comTotal').html(sum + '%');        
     })  
     },		
     "fnRowCallback": function(nRow, aData, iDisplayIndex) {
     return nRow;
     },
     "fnServerData": function(sSource, aoData, fnCallback) {
     aoData.push({
     "name": "action",
     "value": 'getCombinationData'
     });		
     aoData.push({
     "name" : "lan",
     "value" : lan
     });
     aoData.push({
     "name" : "baseUrl",
     "value" : baseUrl
     });			
     aoData.push({
     "name": "Country",
     "value": $('#country-list').val()
     });					
     aoData.push({
     "name": "RegimenId",
     "value": RegimenId
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
     "sClass": "comname",
     "sWidth": "60%",
     "bSortable": false
     },
     {
     "sClass": "percentage",
     "sWidth": "30%",
     "bSortable": false
     }]
     });*/

});