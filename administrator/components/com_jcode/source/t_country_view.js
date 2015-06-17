var CountryFormTable;
var RecordId = '';
var userId = '';
var engbId = '';
var ProductSubGroupId = " ";

var $ = jQuery.noConflict();

$(function() {

	CountryFormTable = $('#CountryFormTable').dataTable({
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
				"value" : 'getCountryData'
			});
            aoData.push({
                "name": "lan",
                "value": lan
            });
            aoData.push({
                "name": "baseUrl",
                "value": baseUrl
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
			"sWidth" : "8%",
			"bSortable": false
		},{
			"sClass" : "CountryCode",
			"sWidth" : "10%",
            "bSortable": true
		},{
			"sClass" : "CountryName",
			"sWidth" : "15%",
            "bSortable": true
		},{
			"sClass" : "CountryNameFrench",
			"sWidth" : "15%",
            "bSortable": true
		},{
			"sClass" : "Level",
			"sWidth" : "10%",
            "bSortable": false
		},{
			"sClass" : "Start",
			"sWidth" : "10%",
            "bVisible" : false
		},{
			"sClass" : "Location",
			"sWidth" : "15%",
            "bSortable": false
		},{
			"sClass" : "ZoomLevel",
			"sWidth" : "10%",
            "bSortable": false
		}]
	});

}); 