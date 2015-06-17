var errorLogTable;
var RecordId = '';
var userId = '';
var engbId = '';

var $ = jQuery.noConflict();

$(function() {

	errorLogTable = $('#errorLogTable').dataTable({
		"bFilter" : true,		
		"bSort" : true,
		"bInfo" : true,
		"bPaginate" : true,
		"bSortClasses" : false,
		"bProcessing" : true,
		"bServerSide" : true,
		"aaSorting" : [[4, 'asc']],
		"aLengthMenu" : [[25, 50, 100], [25, 50, 100]],
		"iDisplayLength" : 25,
		"sPaginationType" : "full_numbers",
		//"sScrollX": "100%",
		"sAjaxSource" : baseUrl + "t_errorlog_view_server.php",
		"fnDrawCallback" : function(oSettings) {
			if (oSettings.aiDisplay.length == 0) {
				return;
			}
		},
		"fnServerData" : function(sSource, aoData, fnCallback) {
			aoData.push({
				"name" : "action",
				"value" : 'getErrorLog'
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
			"bVisible" : false
		},{
            "sClass" : "SL",		  
			"sWidth" : "5%",
			"bSortable": false
		},{
			"sWidth" : "12%"
		},{
			"sWidth" : "8%"
		},{
			"sWidth" : "8%",
			"bSearchable" : true
		},{
			"sWidth" : "5%"
		},{
			"sWidth" : "20%",
			"bSortable": false
		},{
			"sWidth" : "8%"
		},{
			"sWidth" : "30%",
			"bSortable": false
		}]
	});

}); 