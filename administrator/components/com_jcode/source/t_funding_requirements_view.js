var fundingReqTable;
var RecordId = '';

function resetForm(id) {
	$('#' + id).each(function() {
		this.reset();
	});
}


$(function() {
    
    $.each(gItemGroupList, function(i, obj) {
        $('#item-group').append($('<option></option>').val(obj.ItemGroupId).html(obj.GroupName));			
	});
	
    $('#item-group').val(gUserItemGroupId);   
    
    $.each(gServiceTypeList, function(i, obj) {
        $('#ServiceTypeId1').append($('<option></option>').val(obj.ServiceTypeId).html(obj.ServiceTypeName));			
	}); 
    gServiceTypeId = $('#ServiceTypeId1').val();

   
    $('#item-group').change(function() {
    		fundingReqTable.fnDraw();
    });
     $('#ServiceTypeId1').change(function() {
    		fundingReqTable.fnDraw();
    });

	fundingReqTable = $('#fundingReqTable').dataTable({
		"bFilter" : true,
		"bJQueryUI" : true,
		"bSort" : true,
		"bInfo" : true,
		"bPaginate" : true,
		"bSortClasses" : false,
		"bProcessing" : true,
		"bServerSide" : true,
		"aaSorting": [[0, 'asc']],
		"sPaginationType" : "full_numbers",
        "aLengthMenu": [[25, 50, 100], [25, 50, 100]],
		"iDisplayLength": 25,
		 
		"sAjaxSource" : baseUrl + "stage_two_datasource.php",
	
		"fnServerData" : function(sSource, aoData, fnCallback) {
			aoData.push({
				"name" : "action",
				"value" : 'getFundingReqData'
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
            aoData.push({
    				"name" : "ServiceTypeId",
    				"value" : $('#ServiceTypeId1').val()
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
			"bVisible" : false,
			"bSearchable" : true
		},{
            "sClass" : "SL",		  
			"sWidth" : "10%",
			"bSortable": false
		},{
			"sClass" : "FundingReqSourceName",
			"sWidth" : "45%",
            "bSortable": true,
            "bSearchable" : true
		},{
			"sClass" : "FundingReqSourceNameFrench",
			"sWidth" : "43%",
            "bSortable": true,
            "bSearchable" : true
		}]
	});
});
