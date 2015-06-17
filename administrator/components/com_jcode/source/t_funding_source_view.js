var FundingSourceTable;
var RecordId = '';
var userId = '';
var engbId = '';

var $ = jQuery.noConflict();


$(function() {
	
	//$('#example').dataTable();
	
	userId = $('#userId').val();
	engbId = $('#en-GBId').val();

     $.each(gItemGroupList, function(i, obj) {
        $('#item-group').append($('<option></option>').val(obj.ItemGroupId).html(obj.GroupName));			
	});
	
    $('#item-group').val(gUserItemGroupId);
    
    $('#item-group').change(function() {
    		FundingSourceTable.fnDraw();
    });

	FundingSourceTable = $('#FundingSourceTable').dataTable({
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
				"value" : 'getFundingSourceData'
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
				"name" : "itemGroupId",
				"value" : $('#item-group').val()
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
			"sWidth" : "12%",
			"bSortable": false
		},{	
			"sClass" : "left-aln",
			"sWidth" : "10%"
		},{
			"sClass" : "left-aln",
			//FSourcename
			"sWidth" : "30%"
		},{
			"sClass" : "left-aln",
			//FSourceDesc
			"sWidth" : "46%"
		}]
	});

}); 