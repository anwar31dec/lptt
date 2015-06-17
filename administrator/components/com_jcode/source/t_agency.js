var AgencyTable;
var RecordId = '';

function resetForm(id) {
	$('#' + id).each(function() {
		this.reset();
	});
}

function onListPanel(){
	$('.list-panel, .btn-form').show();
	$('.form-panel, .btn-list').hide();
}

function onFormPanel(){
    resetForm("t_Agency_form");
    RecordId = '';
	$('.list-panel, .btn-form').hide();
	$('.form-panel, .btn-list').show();
}

function onEditPanel(){
    $('.list-panel, .btn-form').hide();
	$('.form-panel, .btn-list').show();
}
    
function onConfirmWhenAddEdit() {     
    $.ajax({
		"type" : "POST",
		"url" : baseUrl + "stage_one_datasourse.php",
		"data" : $('#t_Agency_form').serialize(),
		"success" : function(response) {
			if (response == 1) {
				AgencyTable.fnDraw();
                if(RecordId==''){
               	    msg = "Agency Record added successfully.";
                }else{
               	    msg = "Agency Record updated successfully.";
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
		"url" : baseUrl + "stage_one_datasourse.php",
		"data" : 'action=deleteAgencyData&RecordId=' + RecordId,
		"success" : function(response) {
			if (response == 1) {
				AgencyTable.fnDraw();
				msg = "Record has been deleted successfully.";
				onSuccessMsg(msg);
			} else {
				msg = "Server processing Error.";
				onErrorMsg(msg);
			}
		}
	});
}
  
$('#t_Agency_form').parsley( { listeners: {
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
    
    onListPanel();
    resetForm("t_Agency_form");
    
    $('.btn-form-success').click(function(){
        $( "#t_Agency_form" ).submit();
	});

	AgencyTable = $('#AgencyTable').dataTable({
		"bFilter" : true,
		"bJQueryUI" : true,
		"bSort" : true,
		"bInfo" : true,
		"bPaginate" : true,
		"bSortClasses" : false,
		"bProcessing" : true,
		"bServerSide" : true,
		"aaSorting": [[2, 'asc']],
		"sPaginationType" : "full_numbers",
        "aLengthMenu": [[25, 50, 100], [25, 50, 100]],
		"iDisplayLength": 25,
		 
		"sAjaxSource" : baseUrl + "stage_one_datasourse.php",
		"fnDrawCallback" : function(oSettings) {
		  
			$('a.itmEdit', AgencyTable.fnGetNodes()).each(function() {
				$(this).click(function() {
					var nTr = this.parentNode.parentNode;
					var aData = AgencyTable.fnGetData(nTr);
                    RecordId = aData[0];
                    $('#RecordId').val(aData[0]);                    
                    $('#AgencyName').val(aData[2]);
                    $('#AgencyDesc ').val(aData[3]);
                    msg = "Do you really want to edit this record?";
                    onCustomModal(msg, "onEditPanel");                  			
				});
			});
            
			$('a.itmDrop', AgencyTable.fnGetNodes()).each(function() {
				$(this).click(function() {
					var nTr = this.parentNode.parentNode;
					var aData = AgencyTable.fnGetData(nTr);
					RecordId = aData[0];
					msg = "Do you really want to Delete this record?";
					onCustomModal(msg, "onConfirmWhenDelete");
				});
			});
		},
		"fnServerData" : function(sSource, aoData, fnCallback) {
			aoData.push({
				"name" : "action",
				"value" : 'getAgencyData'
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
			"sWidth" : "10%",
			"bSortable": false
		},{
			"sClass" : "agencyname"
			//"sWidth" : "75%"
		},{
			"sClass" : "agencyfullname"
			//"sWidth" : "75%"
		},{
			"sClass" : "Action",
			"sWidth" : "15%"
		}]
	});
});
