var ItemTable;
var RecordId = '';
var userId = '';
var engbId = '';

var $ = jQuery.noConflict();

$(function() {
	
	//$('#example').dataTable();
	
	userId = $('#userId').val();
	engbId = $('#en-GBId').val();

	ItemTable = $('#ItemTable').dataTable({
		"bFilter" : true,		
		"bSort" : true,
		"bInfo" : true,
		"bPaginate" : true,
		"bSortClasses" : false,
		"bProcessing" : true,
		"bServerSide" : true,
		"aaSorting" : [[2, 'asc']],
		"aLengthMenu" : [[25, 50, 100], [25, 50, 100]],
		"iDisplayLength" : 25,
		"sPaginationType" : "full_numbers",
		//"sScrollX": "100%",
		"sAjaxSource" : baseUrl + "stage_one_datasourse.php",
		"fnDrawCallback" : function(oSettings) {
			if (oSettings.aiDisplay.length == 0) {
				return;
			}
		},
		"fnServerData" : function(sSource, aoData, fnCallback) {
			aoData.push({
				"name" : "action",
				"value" : 'getItemData'
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
			"bVisible": false
		}, {
			"sClass": "center-aln",
			//SL
			"sWidth": "10%",
			"bSortable": false
		}, {
			"sClass": "left-aln",
			//ItemName
			"sWidth": "40%"
		}, {
			"sClass": "left-aln",
			//ItemNameFrench
			"sWidth": "40%",
			"bSortable": true
		}, {
			"sClass": "left-aln",
			"sWidth": "10%",
			"bSortable": false
		}]
	});

}); 