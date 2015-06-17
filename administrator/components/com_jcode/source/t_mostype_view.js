var MOStypeTable;
var RecordId = '';
var userId = '';
var engbId = '';
var ProductSubGroupId = " ";

var $ = jQuery.noConflict();

$(function() {

	userId = $('#userId').val();
	engbId = $('#en-GBId').val();

	MOStypeTable = $('#MOStypeTable').dataTable({
		"bFilter" : true,		
		"bSort" : true,
		"bInfo" : true,
		"bPaginate" : true,
		"bSortClasses" : false,
		"bProcessing" : true,
		"bServerSide" : true,
		"aaSorting" : [[1, 'asc']],
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
				"value" : 'getMOSTypeData'
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
            "sClass" : "center-aln",		  
            //SL		  
			"sWidth" : "10%",
			"bSortable": false
		},{
			"sClass" : "left-aln",
			//mosTypeName
			"bSortable": false
		},{
			"sClass" : "left-aln",
			//mosTypeNameFrench
			"bSortable": false
		},{
			"sClass" : "center-aln",
			//minMos
		    "bSortable": false
		},{
			"sClass" : "center-aln",
			//maxMos
			"bSortable": false
		},{
			"sClass" : "left-aln",
			//colorCode
			"bSortable": false
		},{
			"sClass" : "center-aln",
			//MosLabel
			"bSortable": false
		}]
	});
}); 