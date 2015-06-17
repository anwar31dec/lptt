var FacilityLevelTable;
var RecordId = '';
var userId = '';
var engbId = '';
var ProductSubGroupId = " ";

var $ = jQuery.noConflict();

$(function() {

	userId = $('#userId').val();
	engbId = $('#en-GBId').val();
	
	FacilityLevelTable = $('#FacilityLevelTable').dataTable({
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
				"value" : 'getFacilityLevelData'
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
			//flevelName
			"sWidth" : "40%"
		},{
			"sClass" : "left-aln",
			//FLevelNameFrench
			"sWidth" : "40%"
		},{
			"sClass" : "left-aln",
			//ColorCode
			"sWidth" : "10%",
			"bSortable": false
		}]
	});
}); 