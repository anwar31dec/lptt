var tblProcessTracking;
var tblProcessTracking8;
var tblProcessTracking18;
var tblProcessTracking19;

var ItemNo = '';
var userId = '';
var engbId = '';
var ProductSubGroupId = " ";
var waitingProcessList;

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
    resetForm("frmProcessTracking");
    ItemNo = '';
    $("#bKeyItem").prop("checked", true);
    bKey();
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

function bKey() {
    var checkboxstate = document.getElementById('bKeyItem').checked;
    $('#bKeyItem').val(checkboxstate);
}

function bCommon() {
    var checkboxstate = document.getElementById('bCommonBasket').checked;
    $('#bCommonBasket').val(checkboxstate);
}

var checkout = '';
function onConfirmWhenAddEdit() {

    /* if(checkout == 'END'){
     checkout = '';
     alert('This tracking number already scanned.');
     return;
     }
     */
    $.ajax({
        "type": "POST",
        "url": baseUrl + "t_process_tracking_analytical_server.php",
        "data": $('#frmProcessTracking').serialize() + '&jUserId=' + userId + '&language=' + engbId,
        "success": function (response) {
            $msgType = JSON.parse(response)['msgType'];
            $msg = JSON.parse(response)['msg'];
            if ($msgType == "success") {

                /* if(checkout == ''){
                 checkout = 'START';
                 }
                 else if(checkout == 'START'){
                 checkout = 'END';
                 }
                 */
                tblProcessTracking.fnDraw();
                tblProcessTracking8.fnDraw();
                tblProcessTracking18.fnDraw();
                tblProcessTracking19.fnDraw();
                onSuccessMsg($msg);
                onListPanel();
                $("#regno-group-id").hide();
                $('input[type=checkbox]').attr('checked', false);
            } else {
                onErrorMsg($msg);
            }
            $("#TrackingNo").val("");
            $("#RegNo").val("");
            $("#RegNoWet").val("");
            $("#RegNoMec").val("");
            $("#RegNoPil").val("");
            $
            $("#RegNoPhy").val("");
            $("#RegNoCol").val("");
            $("#RegNoFib").val("");
            $("#RegNoCom").val("");
            $("#RegNoSub").val("");
            $("#RegNoRec").val("");
            $("#RegNoDel").val("");
            $("#TrackingNoPt").val('');
            $("#RetRegNo").val('');
        }
    });
}

function onConfirmWhenDelete() {
    $.ajax({
        "type": "POST",
        "url": baseUrl + "t_process_tracking_analytical_server.php",
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


jQuery('#frmProcessTracking').parsley({
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

$(function () {

    //$('#example').dataTable();

    userId = $('#userId').val();
    engbId = $('#en-GBId').val();

    $('#submitProcessTracking').click(function () {
        $("#frmProcessTracking").submit();
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
        "sAjaxSource": baseUrl + "t_process_tracking_analytical_server.php",
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
                    ItemNo = aData[0];
                    $('#ItemNo').val(aData[0]);
                    $('#ItemCode').val(aData[2]);
                    $('#ItemName').val(aData[3]);
                    $('#ShortName').val(aData[4]);
                    if (aData[10] == 1) {
                        document.getElementById("bKeyItem").checked = true;
                    } else {
                        document.getElementById("bKeyItem").checked = false;
                    }
                    $('#ItemGroupId').val(aData[11]);
                    //$('#ProductSubGroupId').val(aData[12]);
                    ProductSubGroupId = aData[12];

                    if (aData[13] == 1) {
                        document.getElementById("bCommonBasket").checked = true;
                    } else {
                        document.getElementById("bCommonBasket").checked = false;
                    }

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
            }]
    });

    tblProcessTracking8 = $('#tblProcessTracking8').dataTable({
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
        "sAjaxSource": baseUrl + "t_process_tracking_analytical_server.php",
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
                    ItemNo = aData[0];
                    $('#ItemNo').val(aData[0]);
                    $('#ItemCode').val(aData[2]);
                    $('#ItemName').val(aData[3]);
                    $('#ShortName').val(aData[4]);
                    if (aData[10] == 1) {
                        document.getElementById("bKeyItem").checked = true;
                    } else {
                        document.getElementById("bKeyItem").checked = false;
                    }
                    $('#ItemGroupId').val(aData[11]);
                    //$('#ProductSubGroupId').val(aData[12]);
                    ProductSubGroupId = aData[12];

                    if (aData[13] == 1) {
                        document.getElementById("bCommonBasket").checked = true;
                    } else {
                        document.getElementById("bCommonBasket").checked = false;
                    }

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
                "value": 'getProcessTrackingData8'
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
                "sClass": "center-aln",
                "sWidth": "10%",
                "bVisible": true,
            }, {
                "sClass": "center-aln",
                "sWidth": "10%",
                "bVisible": true,
            }, {
                "sClass": "center-aln",
                "sWidth": "10%",
                "bVisible": true,
            }]
    });

    tblProcessTracking18 = $('#tblProcessTracking18').dataTable({
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
        "sAjaxSource": baseUrl + "t_process_tracking_analytical_server.php",
        "fnDrawCallback": function (oSettings) {
            if (oSettings.aiDisplay.length == 0) {
                return;
            }
        },
        "fnServerData": function (sSource, aoData, fnCallback) {
            aoData.push({
                "name": "action",
                "value": 'getProcessTrackingData18'
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
                "sClass": "center-aln",
                "sWidth": "10%",
                "bVisible": true,
            }, {
                "sClass": "center-aln",
                "sWidth": "10%",
                "bVisible": true,
            }, {
                "sClass": "center-aln",
                "sWidth": "10%",
                "bVisible": true,
            }]
    });


    tblProcessTracking19 = $('#tblProcessTracking19').dataTable({
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
        "sAjaxSource": baseUrl + "t_process_tracking_analytical_server.php",
        "fnDrawCallback": function (oSettings) {
            if (oSettings.aiDisplay.length == 0) {
                return;
            }
        },
        "fnServerData": function (sSource, aoData, fnCallback) {
            aoData.push({
                "name": "action",
                "value": 'getProcessTrackingData19'
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
                "sClass": "center-aln",
                "sWidth": "10%",
                "bVisible": true,
            }, {
                "sClass": "center-aln",
                "sWidth": "10%",
                "bVisible": true,
            }]
    });

    waitingProcessList = $('#WaitingProcessList').dataTable({
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
        "sAjaxSource": baseUrl + "t_process_tracking_analytical_server.php",
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
                    ItemNo = aData[0];
                    $('#ItemNo').val(aData[0]);
                    $('#ItemCode').val(aData[2]);
                    $('#ItemName').val(aData[3]);
                    $('#ShortName').val(aData[4]);
                    if (aData[10] == 1) {
                        document.getElementById("bKeyItem").checked = true;
                    } else {
                        document.getElementById("bKeyItem").checked = false;
                    }
                    $('#ItemGroupId').val(aData[11]);
                    //$('#ProductSubGroupId').val(aData[12]);
                    ProductSubGroupId = aData[12];

                    if (aData[13] == 1) {
                        document.getElementById("bCommonBasket").checked = true;
                    } else {
                        document.getElementById("bCommonBasket").checked = false;
                    }

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
                "value": 'getWaitingProcessList'
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
            aoData.push({
                "name": "ProcessOrder",
                "value": $("#ProcessOrder").val()
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
                "bVisible": false,
            }, {
                "bVisible": false,
            }]
    });



    //var timestamp = new Date().getTime();


    // var txtScanValue = '';
    // $("#TrackingNo").keyup(function(event)
    // {
    // console.log($("#TrackingNo").val());

    // var currentTimestamp = new Date().getTime();

    // if(currentTimestamp - timestamp > 100)
    // {
    ////console.log($("#TrackingNo").val());
    // $("#TrackingNo").val('MMMMM');
    //onConfirmWhenAddEdit();8901177100505


    // }
    // timestamp = currentTimestamp;
    ////alert(timestamp);
    ////console.log(timestamp);
    // });       

    var barcode = "";
    $("#TrackingNo").keydown(function (e) {

        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13)// Enter key hit
        {
            //alert($("#TrackingNo").val().length);
            if (parseInt($("#TrackingNo").val().length) > 15) {
                alert('Your input length is greater than the actual.');
                $("#TrackingNo").val('');
                $("#TrackingNo").focus();
                return;
            }


            if ($("#TrackingNo").val() == $("#RegNo").val()) {
                alert('Inward no and Registration can not be same.');
                $("#TrackingNo").val('');
                $("#RegNo").val('');
                return;
            }

            if ($("#eNewNoPosition").val() == 'REGISTRATION') {
                if ($("#RegNo").val() == '') {
                    $("#RegNo").focus();
                    //alert('Please fill up the tracking number.')
                    return;
                }
            }

            //console.log(code);
            //console.log($("#TrackingNo").val());
            //$("#TrackingNo").val('');
            //$("#TrackingNo").prop('disabled', true);
            onConfirmWhenAddEdit();
        }
        else if (code == 9)// Tab key hit
        {
            alert(code);
        }
        else
        {
            // barcode=barcode+String.fromCharCode(code);
        }
    });
    $("#RegNo").keydown(function (e) {

        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13)// Enter key hit
        {
            //alert($("#RegNo").val().length);
            if (parseInt($("#RegNo").val().length) > 20) {
                alert('Your input length is greater than the actual.');
                $("#TrackingNo").val('');
                $("#RegNo").val('');
                //$("#TrackingNo").focus();
                return;
            }

            if ($("#TrackingNo").val() == $("#RegNo").val()) {
                alert('Inward no and Registration can not be same.');
                $("#TrackingNo").val('');
                $("#RegNo").val('');
                $("#TrackingNo").focus();
                return;
            }

            if ($("#eNewNoPosition").val() == 'REGISTRATION') {
                if ($("#TrackingNo").val() == '') {
                    $("#TrackingNo").focus();
                    //alert('Please fill up the tracking number.')
                    return;
                }
            }

            //console.log(code);
            //console.log($("#TrackingNo").val());
            //$("#TrackingNo").val('');
            onConfirmWhenAddEdit();
        }
        else if (code == 9)// Tab key hit
        {
            alert(code);
        }
        else
        {
            // barcode=barcode+String.fromCharCode(code);
        }
    });

    $("#RegNoWet").keydown(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13)// Enter key hit
        {
            //alert($("#RegNo").val().length);
            if (parseInt($("#RegNoWet").val().length) > 20) {
                alert('Your input length is greater than the actual.');
                $("#RegNoWet").val('');
                return;
            }
            onConfirmWhenAddEdit();
        }
        else if (code == 9)// Tab key hit
        {
            alert(code);
        }
        else
        {
            // barcode=barcode+String.fromCharCode(code);
        }
    });

    $("#RegNoMec").keydown(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13)// Enter key hit
        {
            //alert($("#RegNo").val().length);
            if (parseInt($("#RegNoMec").val().length) > 20) {
                alert('Your input length is greater than the actual.');
                $("#RegNoMec").val('');
                return;
            }
            onConfirmWhenAddEdit();
        }
        else if (code == 9)// Tab key hit
        {
            alert(code);
        }
        else
        {
            // barcode=barcode+String.fromCharCode(code);
        }
    });

    $("#RegNoPil").keydown(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13)// Enter key hit
        {
            //alert($("#RegNo").val().length);
            if (parseInt($("#RegNoPil").val().length) > 20) {
                alert('Your input length is greater than the actual.');
                $("#RegNoPil").val('');
                return;
            }
            onConfirmWhenAddEdit();
        }
        else if (code == 9)// Tab key hit
        {
            alert(code);
        }
        else
        {
            // barcode=barcode+String.fromCharCode(code);
        }
    });

    $("#RegNoPhy").keydown(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13)// Enter key hit
        {
            if (parseInt($("#RegNoPhy").val().length) > 20) {
                alert('Your input length is greater than the actual.');
                $("#RegNoPhy").val('');
                return;
            }
            onConfirmWhenAddEdit();
        }
        else if (code == 9)// Tab key hit
        {
            alert(code);
        }
        else
        {
            // barcode=barcode+String.fromCharCode(code);
        }
    });

    $("#RegNoCol").keydown(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13)// Enter key hit
        {
            if (parseInt($("#RegNoCol").val().length) > 20) {
                alert('Your input length is greater than the actual.');
                $("#RegNoCol").val('');
                return;
            }
            onConfirmWhenAddEdit();
        }
        else if (code == 9)// Tab key hit
        {
            alert(code);
        }
        else
        {
            // barcode=barcode+String.fromCharCode(code);
        }
    });

    $("#RegNoFib").keydown(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13)// Enter key hit
        {
            if (parseInt($("#RegNoFib").val().length) > 20) {
                alert('Your input length is greater than the actual.');
                $("#RegNoFib").val('');
                return;
            }
            onConfirmWhenAddEdit();
        }
        else if (code == 9)// Tab key hit
        {
            alert(code);
        }
        else
        {
            // barcode=barcode+String.fromCharCode(code);
        }
    });

    $("#RegNoCom").keydown(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13)// Enter key hit
        {
            if (parseInt($("#RegNoCom").val().length) > 20) {
                alert('Your input length is greater than the actual.');
                $("#RegNoCom").val('');
                return;
            }
            onConfirmWhenAddEdit();
        }
        else if (code == 9)// Tab key hit
        {
            alert(code);
        }
        else
        {
            // barcode=barcode+String.fromCharCode(code);
        }
    });

    $("#RegNoSub").keydown(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13)// Enter key hit
        {
            if (parseInt($("#RegNoSub").val().length) > 20) {
                alert('Your input length is greater than the actual.');
                $("#RegNoSub").val('');
                return;
            }
            onConfirmWhenAddEdit();
        }
        else if (code == 9)// Tab key hit
        {
            alert(code);
        }
        else
        {
            // barcode=barcode+String.fromCharCode(code);
        }
    });

    $("#RegNoRec").keydown(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13)// Enter key hit
        {
            if (parseInt($("#RegNoRec").val().length) > 20) {
                alert('Your input length is greater than the actual.');
                $("#RegNoRec").val('');
                return;
            }
            onConfirmWhenAddEdit();
        }
        else if (code == 9)// Tab key hit
        {
            alert(code);
        }
        else
        {
            // barcode=barcode+String.fromCharCode(code);
        }
    });

    $("#RegNoDel").keydown(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13)// Enter key hit
        {
            if (parseInt($("#RegNoDel").val().length) > 20) {
                alert('Your input length is greater than the actual.');
                $("#RegNoDel").val('');
                return;
            }
            onConfirmWhenAddEdit();
        }
        else if (code == 9)// Tab key hit
        {
            alert(code);
        }
        else
        {
            // barcode=barcode+String.fromCharCode(code);
        }
    });


    if ($("#bUseRegNo").val() == 1) {
        $("#RegNo").focus();
    } else {
        $("#TrackingNo").focus();
    }

    $('#processTickDone').click(function () {
        if ($('#chk-wet-lab').attr('checked')) {
            $("#regno-group-id").show();
        }
        else if ($('#chk-mechanical-lab').attr('checked')) {
            $("#regno-group-id").show();
        }
        else if ($('#chk-pilling-abrasion-lab').attr('checked')) {
            $("#regno-group-id").show();
        } else {
            alert('You must select at least one option.');
            return;
        }
        $("#RegNo").focus();
    });

    var bShowReturn = false;
    $('#btnRetTrackingNo').click(function () {
        if (!bShowReturn) {
            $("#TrackingNo-group-id").hide();
            $("#RetTrackingNo-group-id").show();
            $("#RetTrackingNo").focus();
            bShowReturn = true;
            $('#btnRetTrackingNo').text('Hide Return');
        } else {
            $("#TrackingNo-group-id").show();
            $("#RetTrackingNo-group-id").hide();
            $("#TrackingNo").focus();
            bShowReturn = false;
            $('#btnRetTrackingNo').text('Show Return');
        }
    });

    $('#btnRetRegNo').click(function () {
        if (!bShowReturn) {
            $("#RegNo-group-id").hide();
            $("#regno14-group-id").hide();
            $("#RetRegNo-group-id").show();
            $("#RetRegNo").focus();
            bShowReturn = true;
            $('#btnRetRegNo').text('Hide Return');
        } else {
            $("#RegNo-group-id").show();
            $("#regno14-group-id").show();
            $("#RetRegNo-group-id").hide();
            $("#RegNo").focus();
            bShowReturn = false;
            $('#btnRetRegNo').text('Show Return');
        }
    });

    $("#TrackingNoPt").keydown(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13)// Enter key hit
        {
            if (parseInt($("#TrackingNoPt").val().length) > 20) {
                alert('Your input length is greater than the actual.');
                $("#TrackingNoPt").val('');
                return;
            }
            onConfirmWhenAddEdit();
        }
        else if (code == 9)// Tab key hit
        {
            alert(code);
        }
        else
        {
            // barcode=barcode+String.fromCharCode(code);
        }
    });

    $("#RetRegNo").keydown(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13)// Enter key hit
        {
            if (parseInt($("#RetRegNo").val().length) > 20) {
                alert('Your input length is greater than the actual.');
                $("#RetRegNo").val('');
                return;
            }
            onConfirmWhenAddEdit();
        }
        else if (code == 9)// Tab key hit
        {
            alert(code);
        }
        else
        {
            // barcode=barcode+String.fromCharCode(code);
        }
    });

}); 