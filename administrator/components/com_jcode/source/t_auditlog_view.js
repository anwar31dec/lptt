
var paramTable;
var RecordId = '';
var auditLogTable;
var auditLogTableList;
var logId;

var $ = jQuery.noConflict();

function resetForm(id) {
    $('#' + id).each(function() {
        this.reset();
    });
}

function onEditPanel() {
    $('#list-panel, .btn-form').hide();
    $('#form-panel, .btn-list').show();
}

$(function() {

	auditLogTable = $('#auditLogTable').dataTable({
		"bFilter" : true,		
		"bSort" : true,
		"bInfo" : true,
		"bPaginate" : true,
		"bSortClasses" : false,
		"bProcessing" : true,
		"bServerSide" : true,
		"aaSorting" : [[1, 'DESC']],
		"aLengthMenu" : [[25, 50, 100], [25, 50, 100]],
		"iDisplayLength" : 25,
		"sPaginationType" : "full_numbers",
		//"sScrollX": "100%",
		"sAjaxSource" : baseUrl + "t_auditlog_view_server.php",
		"fnDrawCallback": function(oSettings) {
            $('a.moreQery', auditLogTable.fnGetNodes()).each(function() {

                $(this).click(function() {
                    $(".btn-success").hide();
                    var nTr = this.parentNode.parentNode;
                    var aData = auditLogTable.fnGetData(nTr);
                    logId = aData[1];
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: baseUrl + "t_auditlog_view_server.php",
                        data: {
                            "action": 'getQueryMore',
                            "logId": logId
                        },
                        success: function(response) {
                            var msg=response.query.SqlText;
                            onCustomModal(msg, "onEditPanel");
                        }
                    });


                });
            });

            if (oSettings.aiDisplay.length == 0) {
                return;
            }
            var nTrs = $('#auditLogTable tbody tr');
            var iColspan = nTrs[0].getElementsByTagName('td').length;
            var sLastGroup = "";
            for (var i = 0; i < nTrs.length; i++) {
                var iDisplayIndex = i;
                var sGroup = oSettings.aoData[oSettings.aiDisplay[iDisplayIndex]]._aData[1];

            }
        },
        "fnRowCallback": function(nRow, aData, iDisplayIndex) {
            $(nRow).attr('id', aData[1]);
            return nRow;
        },
		"fnServerData" : function(sSource, aoData, fnCallback) {
			aoData.push({
				"name" : "action",
				"value" : 'getAuditLog'
			});
			aoData.push({
				"name" : "lan",
				"value" : lan
			});
			aoData.push({
				"name" : "baseUrl",
				"value" : baseUrl
			});
			$.ajax({
				"dataType" : 'json',
				"type" : "POST",
				"url" : sSource,
				"data" : aoData,
				"success" : fnCallback
			});
		},
		"aoColumns" : [{
			"sClass": "SL",
			"sWidth": "3%",
			"bSortable": false,
			"bSearchable": false
		},{
			"sWidth": "3%",
			"bSortable": true,
			"bSearchable": true,
			"bVisible" : false
		},{
			"sWidth": "15%",
			"bSortable": true,
			"bSearchable": true
		},{
			"sWidth": "15%",
			"bSortable": true,
			"bSearchable": true
		},{
			"sWidth": "15%",
			"bSearchable": true,
			"bSortable": true,
			"bSearchable" : true
		},{
			"sWidth": "10%",
			"bSortable": true,
			"bSearchable": true
		},{
			"sWidth": "10%",
			"bSortable": true,
			"bSearchable": true
		},{
			"sWidth": "20%",
			"bSortable": false,
			"bSearchable": true
		},{
			"sWidth": "5%",
			"bSortable": false,
		}]
	});

$('#auditLogTable').click(function(event) {
        var id = $(event.target.parentNode).attr('id');
        var aData;
        $(auditLogTable.fnSettings().aoData).each(function() {
            if ($(this.nTr).attr('id') == id) {
                $(this.nTr).addClass('row_selected');
                aData = auditLogTable.fnGetData(this.nTr);
                logId = id;
            } else
                $(this.nTr).removeClass('row_selected');
        });
        if (aData) {
            auditLogTableList.fnDraw();
        }
    });


    auditLogTableList = $('#auditLogTableList').dataTable({
        "bFilter": false,
        "bJQueryUI": true,
        "bSort": false,
        "bInfo": false,
        "bPaginate": false,
        "bSortClasses": false,
        "bProcessing": false,
        "bServerSide": true,
        "aaSorting": [[1, 'desc']],
        "sPaginationType": "full_numbers",
        "aLengthMenu": [[25, 50, 100, 200], [25, 50, 100, 200]],
        "iDisplayLength": 25,
        "sAjaxSource": baseUrl + "t_auditlog_view_server.php",
        "fnDrawCallback": function(oSettings) {
            if (oSettings.aiDisplay.length == 0) {
                return;
            }
            var nTrs = $('#auditLogTableList tbody tr');
            var iColspan = nTrs[0].getElementsByTagName('td').length;

        },
        "fnServerData": function(sSource, aoData, fnCallback) {

            aoData.push({
                "name": "action",
                "value": 'getAuditLogList',
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
                "name": "logId",
                "value": logId
            })

            $.ajax({
                "dataType": 'json',
                "type": "POST",
                "url": sSource,
                "data": aoData,
                "success": fnCallback
            });
        },
        "aoColumns": [
            {
                "sClass": "SL",
                "sWidth": "5%",
                "bSortable": false,
                "bSearchable": false
            }, {
                "sWidth": "45%",
                "bSortable": false,
                "bSearchable": false
            }, {
                "sWidth": "25%",
                "bSortable": false,
                "bSearchable": false
            }, {
                "sWidth": "25%",
                "bSortable": false,
                "bSearchable": false
            }]


    })	

	
	
}); 