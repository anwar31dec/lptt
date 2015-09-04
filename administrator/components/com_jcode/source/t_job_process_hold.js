var tblProcessTracking;
var ItemNo = '';
var userId = '';
var engbId = '';
var ProductSubGroupId = " ";

var $ = jQuery.noConflict();

function resetForm(id) {
    $('#' + id).each(function () {
        this.reset();
    });
}

/* When you click on "Back to List" button*/
function onListPanel() {
    $('#list-panel, .btn-form').show();
    $('#form-panel, .btn-list').hide();
    $('#PrintBTN, #PrintBTN1').show();
    $('#filter_panel').show();
}

/* When you click on "Add Record" button*/
function onFormPanel() {
    resetForm("process_hold_form");
    ItemNo = '';
    $('#list-panel, .btn-form').hide();
    $('#form-panel, .btn-list').show();
    $('#PrintBTN, #PrintBTN1').hide();
    $('#filter_panel').hide();
}

function onEditPanel() {
    $('#list-panel, .btn-form').hide();
    $('#form-panel, .btn-list').show();
    $('#PrintBTN, #PrintBTN1').hide();
    $('#filter_panel').hide();
}

function bHold() {
    var checkboxstate = document.getElementById('bHold').checked;
    $('#bHold').val(checkboxstate);
}

function onConfirmWhenAddEdit() {
    $.ajax({
        "type": "POST",
        "url": baseUrl + "t_job_process_hold_server.php",
        "data": $('#process_hold_form').serialize() + '&jUserId=' + userId + '&language=' + engbId,
        "success": function (response) {
            $msgType = JSON.parse(response)['msgType'];
            $msg = JSON.parse(response)['msg'];
            if ($msgType == "success") {
                tblProcessTracking.fnDraw();
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
        "data": 'action=deleteItemList&ItemNo=' + ItemNo + '&jUserId=' + userId + '&language=' + engbId,
        "success": function (response) {
            $msgType = JSON.parse(response)['msgType'];
            $msg = JSON.parse(response)['msg'];
            if ($msgType == "success") {
                tblProcessTracking.fnDraw();
                onSuccessMsg($msg);
            } else {
                onErrorMsg($msg);
            }
        }
    });
}


jQuery('#process_hold_form').parsley({
    listeners: {
        onFieldValidate: function (elem) {
            if (!$(elem).is(':visible')) {
                return true;
            }
            return false;
        },
        onFormSubmit: function (isFormValid, event) {
            if (isFormValid) {
                onConfirmWhenAddEdit();
                return false;
            }
        }
    }
});


function validate() {

    // check if input is bigger than 3
    var value = document.getElementById('HoldComments').value;
//  alert(value);
    if (value.length < 10) {
        return false; // keep form from submitting
    }

    // else form is good let it submit, of course you will 
    // probably want to alert the user WHAT went wrong.

    return true;
}


$(function () {

    //$('#example').dataTable();

    userId = $('#userId').val();
    engbId = $('#en-GBId').val();


    onListPanel();
    resetForm("process_hold_form");

    $('#submitItemList').click(function () {
        var value = $('#HoldComments').val();
        if (value.length < 10) {
            alert('You must input minimum 10 characters.')
            return false;
        }

        $("#process_hold_form").submit();
    });

    tblProcessTracking = $('#tblProcessTracking').dataTable({
        "bFilter": true,
        "bSort": true,
        "bInfo": true,
        "bPaginate": true,
        "bSortClasses": false,
        "bProcessing": true,
        "bServerSide": true,
        "aaSorting": [[0, 'DESC']],
        "aLengthMenu": [[25, 50, 100], [25, 50, 100]],
        "iDisplayLength": 25,
        "sPaginationType": "full_numbers",
        //"sScrollX": "100%",
        "sAjaxSource": baseUrl + "t_job_process_hold_server.php",
        "fnDrawCallback": function (oSettings) {

            if (oSettings.aiDisplay.length == 0) {
                return;
            }
            /* var nTrs = $('#tblProcessTracking tbody tr');
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
             } */

            $('a.itmEdit', tblProcessTracking.fnGetNodes()).each(function () {
                $(this).click(function () {
                    var nTr = this.parentNode.parentNode;
                    var aData = tblProcessTracking.fnGetData(nTr);
                    $('#TrackingNo').val(aData[2]);
                    $('#hTrackingNo').val(aData[2]);
                    if (aData[8] === 1) {
                        $("#bHold").prop("checked", true);
                    } else {
                        $("#bHold").prop("checked", false);
                    }
                    $('#Hold').val(aData[9]);
                    $('#HoldComments').val(aData[10]);
                    $('#ProcessId').val(aData[12]);


                    msg = "Do you really want to edit this record?";
                    onCustomModal(msg, "onEditPanel");
                });
            });
            $('a.itmDrop', tblProcessTracking.fnGetNodes()).each(function () {
                $(this).click(function () {
                    var nTr = this.parentNode.parentNode;
                    var aData = tblProcessTracking.fnGetData(nTr);
                    ItemNo = aData[0];
                    msg = "Do you really want to delete this record?";
                    //alert(ItemNo);
                    onCustomModal(msg, "onConfirmWhenDelete");
                });
            });
            $('a.itmMore', tblProcessTracking.fnGetNodes()).each(function () {
                $(this).click(function () {
                    var nTr = this.parentNode.parentNode;
                    if ($(this).children('span').attr('class') == 'label label-info faminus') {
                        $(this).children('span').attr('class', 'label label-info');
                        var nRemove = $(nTr).next()[0];
                        nRemove.parentNode.removeChild(nRemove);
                    } else {
                        $(this).children('span').attr('class', 'label label-info faminus');
                        tblProcessTracking.fnOpen(nTr, fnFormatDetails(nTr), 'details');
                    }
                });
            });
        },
        "fnServerData": function (sSource, aoData, fnCallback) {
            aoData.push({
                "name": "action",
                "value": 'getProcessTrackingData'
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
                "name": "ProcessId",
                "value": $("#ProcessId").val()
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
                "bVisible": false,
                "bSortable": true
            }, {
                "sClass": "center-aln",
                // SL#
                "sWidth": "5%",
                "bSortable": false,
                "bVisible": false
            }, {
                "sClass": "left-aln",
                // Product Code
                "sWidth": "10%",
                "bSortable": true
            }, {
                "sClass": "left-aln",
                // Product Name
                "sWidth": "10%",
                "bSortable": true,
                "bVisible": false
            }, {
                "sClass": "left-aln",
                // Short Name
                "sWidth": "10%",
                "bSortable": true
            }, {
                "sClass": "left-aln",
                // Key Product
                "sWidth": "10%",
                "bSortable": true,
                "bVisible": false
            }, {
                "sClass": "left-aln",
                // Key Product
                "sWidth": "10%",
                "bSortable": true
            }, {
                "sClass": "left-aln",
                // Key Product
                "sWidth": "10%",
                "bSortable": true
            }, {
                "sClass": "left-aln",
                // Key Product
                "sWidth": "10%",
                "bSortable": true,
                "bVisible": false
            }, {
                "sClass": "center-aln",
                // Key Product
                "sWidth": "10%",
                "bSortable": true
            }, {
                "sClass": "left-aln",
                // Key Product
                "sWidth": "15%",
                "bSortable": true
            }, {
                "sClass": "center-aln",
                // Key Product
                "sWidth": "5%",
                "bSortable": true
            }, {
                "sClass": "center-aln",
                // Key Product
                "sWidth": "10%",
                "bSortable": true,
                "bVisible": false
            }]
    });


}); 