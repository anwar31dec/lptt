var fundingReqTable;
var RecordId = '';

function resetForm(id) {
	$('#' + id).each(function() {
		this.reset();
	});
}

function onListPanel(){
	$('#list-panel, .btn-form').show();
	$('#form-panel, .btn-list').hide();
    $('#filter-panel').show();
}

function onFormPanel(){
    resetForm("funding_form");
    RecordId = '';
	$('#list-panel, .btn-form').hide();
	$('#form-panel, .btn-list').show();
    $('#filter-panel').hide();
    //$('#ItemGroupId').val($('#item-group').val());
    
}

function onEditPanel(){
    $('#list-panel, .btn-form').hide();
	$('#form-panel, .btn-list').show();
    $('#filter-panel').hide();
   // $('#ItemGroupId').val($('#item-group').val());
}

function onComboItemGroupName() {
	$.getJSON(baseUrl + "t_combo.php", {
		action: 'getItemGroup'
	}, function(response) {
		for (var i = 0; i < response.length; i++) {
			$('#ItemGroupId').append($('<option></option>').val(response[i].ItemGroupId).html(response[i].GroupName));
		}
	});
}

function onComboServiceName() {
	$.getJSON(baseUrl + "t_combo.php", {
		action: 'getServiceType'
	}, function(response) {
		for (var i = 0; i < response.length; i++) {
		  //$('#service-type').append($('<option></option>').val(response[i].ServiceTypeId).html(response[i].ServiceTypeName));
            $('#ServiceTypeId').append($('<option></option>').val(response[i].ServiceTypeId).html(response[i].ServiceTypeName));
            
		}
	});
}

function onConfirmWhenAddEdit() {     
    $.ajax({
		"type" : "POST",
		"url" : baseUrl + "stage_two_datasource.php",
		"data" : $('#funding_form').serialize(),
		"success" : function(response) {
			if (response == 1) {
				fundingReqTable.fnDraw();
                if(RecordId==''){
               	    msg = "A Funding Requirement Type added successfully.";
                }else{
               	    msg = "Funding Requirement Type updated successfully.";
                }
				onSuccessMsg(msg);
                onListPanel();
			} else {
				msg = "Server processing Error.";
				onErrorMsg(msg);
			}
		}
	});
}

function onConfirmWhenDelete() {
	$.ajax({
		"type" : "POST",
		"url" : baseUrl + "stage_two_datasource.php",
		"data" : 'action=deleteFundingReqData&RecordId=' + RecordId,
		"success" : function(response) {
			if (response == 1) {
				fundingReqTable.fnDraw();
				msg = "Record has been deleted successfully.";
				onSuccessMsg(msg);
			} else {
				msg = "Server processing Error.";
				onErrorMsg(msg);
			}
		}
	});
}
  
$('#funding_form').parsley( { listeners: {
	onFieldValidate: function ( elem ) {
		if ( !$( elem ).is( ':visible' ) ) {
			return true;
		}
		return false;
	},
    onFormSubmit: function ( isFormValid, event ) {
        if(isFormValid)	{
            onConfirmWhenAddEdit();				
			return false;
		}
    }
}});  

$(function() {
    onComboItemGroupName();
    onComboServiceName();
    
    $.each(gItemGroupList, function(i, obj) {
        $('#item-group').append($('<option></option>').val(obj.ItemGroupId).html(obj.GroupName));			
	});
	
    $('#item-group').val(gUserItemGroupId);   
    
    $.each(gServiceTypeList, function(i, obj) {
        $('#ServiceTypeId1').append($('<option></option>').val(obj.ServiceTypeId).html(obj.ServiceTypeName));			
	}); 
    gServiceTypeId = $('#ServiceTypeId1').val();

    onListPanel();
    resetForm("funding_form");
    
    $('.btn-form-success').click(function(){
        $( "#funding_form" ).submit();
	});   
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
		"fnDrawCallback" : function(oSettings) {
 	      
			$('a.itmEdit', fundingReqTable.fnGetNodes()).each(function() {
				$(this).click(function() {
					var nTr = this.parentNode.parentNode;
					var aData = fundingReqTable.fnGetData(nTr);
                    RecordId = aData[0];
                    $('#RecordId').val(aData[0]);                   
                    $('#FundingReqSourceName').val(aData[2]); 
                    $('#FundingReqSourceNameFrench').val(aData[3]);
                    $('#ItemGroupId').val(aData[5]);   
                    $('#ServiceTypeId').val(aData[6]);                  	
                    msg = "Do you really want to edit this record?";
                    onCustomModal(msg, "onEditPanel");
                          			
				});
			});
            
			$('a.itmDrop', fundingReqTable.fnGetNodes()).each(function() {
				$(this).click(function() {
					var nTr = this.parentNode.parentNode;
					var aData = fundingReqTable.fnGetData(nTr);
					RecordId = aData[0];
					msg = "Do you really want to Delete this record?";
					onCustomModal(msg, "onConfirmWhenDelete");
				});
			});
		},
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
			"sWidth" : "35%",
            "bSortable": true,
            "bSearchable" : true
		},{
			"sClass" : "FundingReqSourceNameFrench",
			"sWidth" : "40%",
            "bSortable": true,
            "bSearchable" : true
		},{
			"sClass" : "Action",
			"sWidth" : "13%",
            "bSortable": false
		}]
	});
});
