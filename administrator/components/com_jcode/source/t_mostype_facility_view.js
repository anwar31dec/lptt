
var MOStypeFacilityTable;
var MOStypeFacilityDetailsTable;
var RecordId = '';
var RecordId1 = '';
var Country = '';
var Facility = '';
var CountryId = '';
var gMosTypeId = 0;
var gCountryId = 0;
var gFLevelId = 0;
var userId = '';
var engbId = '';

var $ = jQuery.noConflict();

function resetForm(id) {
	$('#' + id).each(function() {
		this.reset();
	});
}

function onFormPanel() {
	resetForm("t_MOSType_form");
	RecordId = '';
	$('#list-panel, .btn-form').hide();
	$('#form-panel, .btn-list').show();
	$('#PrintBTN, #PrintBTN1').hide();
	$('#filter_panel').hide();
}

/* When you click on "Back to List" button*/
function onListPanel() {
	$('#list-panel, .btn-form').show();
	$('#form-panel, .btn-list').hide();
	$('#PrintBTN, #PrintBTN1').show();
	$('#filter_panel').show();
}

function onListPanel1() {
	$('#list-panel1, .btn-form1').show();
	$('#form-panel1, .btn-list1').hide();
	//$('#PrintBTN, #PrintBTN1').show();
	//$('#filter_panel').show();
}

/* When you click on "Add Record" button*/
function onFormPanel1() {
    if (gMosTypeId == 0) {
        alert('Please select master MOS type.');
    }else {
	resetForm("t_MOSType_details_form");
	RecordId = '';
	$('#list-panel1, .btn-form1').hide();
	$('#form-panel1, .btn-list1').show();
	//$('#PrintBTN, #PrintBTN1').hide();
	//$('#filter_panel').hide();
	}
}

function onEditPanel() {
	$('#list-panel, .btn-form').hide();
	$('#form-panel, .btn-list').show();
	$('#PrintBTN, #PrintBTN1').hide();
	$('#filter_panel').hide();
}

function onEditPanel1() {
	$('#list-panel1, .btn-form1').hide();
	$('#form-panel1, .btn-list1').show();
	//$('#PrintBTN, #PrintBTN1').hide();
	//$('#filter_panel').hide();
}

function onConfirmWhenAddEdit() {
    $.ajax({
        "type": "POST",
        "url": baseUrl + "stage_one_datasourse.php",
        "data": $('#t_MOSType_form').serialize() + '&jUserId=' + userId + '&language=' + engbId,
        "success": function(response) {
            $msgType = JSON.parse(response)['msgType'];
            $msg = JSON.parse(response)['msg']
            if ($msgType == "success") {
                calserver();
                //MOStypeFacilityTable.fnDraw();
                onSuccessMsg($msg);
                onListPanel();
            } else {
                onErrorMsg($msg);
            }
        }
    });
}

function onConfirmWhenDelete() {
    $.ajax({
        "type": "POST",
        "url": baseUrl + "stage_one_datasourse.php",
        "data": 'action=deleteMOSTypeFacilityData&RecordId=' + RecordId + '&jUserId=' + userId + '&language=' + engbId,
        "success": function(response) {
            $msgType = JSON.parse(response)['msgType'];
            $msg = JSON.parse(response)['msg']
            if ($msgType == "success") {
                calserver();
                //MOStypeFacilityTable.fnDraw();
                onSuccessMsg($msg);
            } else {
                onErrorMsg($msg);
            }
        }
    });
}

$('#t_MOSType_form').parsley({
    listeners: {
        onFieldValidate: function(elem) {
            if (!$(elem).is(':visible')) {
                return true;
            }
            return false;
        },
        onFormSubmit: function(isFormValid, event) {
            if (isFormValid) {
                onConfirmWhenAddEdit();
                return false;
            }
        }
    }
});

function onConfirmWhenAddEditDetails() {
    $.ajax({
        "type": "POST",
        "url": baseUrl + "stage_one_datasourse.php",
        "data": $('#t_MOSType_details_form').serialize() + '&MosTypeId=' + gMosTypeId + '&CountryId=' + gCountryId + '&FLevelId=' + gFLevelId + '&jUserId=' + userId + '&language=' + engbId,
        "success": function(response) {
            $msgType = JSON.parse(response)['msgType'];
            $msg = JSON.parse(response)['msg']
            if ($msgType == "success") {
                MOStypeFacilityDetailsTable.fnDraw();
                onListPanel1();
                onSuccessMsg($msg);
            } else {
                onErrorMsg($msg);
            }

        }
    });
}
function onConfirmWhenDeleteDetails() {
    $.ajax({
        "type": "POST",
        "url": baseUrl + "stage_one_datasourse.php",
        "data": 'action=deleteMOSTypeFacilityDetailsData&RecordId=' + RecordId + '&jUserId=' + userId + '&language=' + engbId,
        "success": function(response) {
            $msgType = JSON.parse(response)['msgType'];
            $msg = JSON.parse(response)['msg']
            if ($msgType == "success") {
                MOStypeFacilityDetailsTable.fnDraw();
                onSuccessMsg($msg);
            } else {
                onErrorMsg($msg);
            }
        }
    });
}

$('#t_MOSType_details_form').parsley({
    listeners: {
        onFieldValidate: function(elem) {
            if (!$(elem).is(':visible')) {
                return true;
            }
            return false;
        },
        onFormSubmit: function(isFormValid, event) {
            if (isFormValid) {
                onConfirmWhenAddEditDetails();
                return false;
            }
        }
    }
});

/*jQuery('#t_MOSType_details_form').parsley({
	listeners : {
		onFieldValidate : function(elem) {
			if (!$(elem).is(':visible')) {
				return true;
			}
			return false;
		},
		onFormSubmit : function(isFormValid, event) {
			if (isFormValid) {
				onConfirmWhenAddEdit();
				return false;
			}
		}
	}
});*/

function checkMinMax(minid, maxid) {
    //if($('#MinMos').val() == '' ||  $('#MaxMos').val() == '')
    if ($(minid).val() == '' || $(maxid).val() == '')
        return true;

    var minVal = parseFloat($(minid).val());
    var maxVal = parseFloat($(maxid).val());
    if (minVal < maxVal)
        return true;
    else
        return false;
}
function calserver() {
    gMosTypeId = 0;
    gCountryId = 0;
    gFLevelId = 0;
    MOStypeFacilityTable.fnDraw();
    MOStypeFacilityDetailsTable.fnDraw();
}

$(function() {
	
	//$('#example').dataTable();
	
	userId = $('#userId').val();
	engbId = $('#en-GBId').val();
	
	//console.log(gMonthList);

	onListPanel();
	resetForm("t_MOSType_form");

	$('#submitItemList').click(function() {
        if (!checkMinMax('#MinMos', '#MaxMos')) {
            alert('Maximum MOS cannot be  less than  Minimum MOS');
            return false;
        }
		$("#t_MOSType_form").submit();
	});	
	
	onListPanel1();
	resetForm("t_MOSType_details_form");

	$('#submitItemList1').click(function() {
        if (!checkMinMax('#MinMos1', '#MaxMos1')) {
            alert('Maximum MOS cannot be  less than  Minimum MOS');
            return false;
        }
		$("#t_MOSType_details_form").submit();
	});	
	
    $.each(gCountryList, function(i, obj) {
        $('#country-list').append($('<option></option>').val(obj.CountryId).html(obj.CountryName));
        $('#CountryId').append($('<option></option>').val(obj.CountryId).html(obj.CountryName));

    });

    $('#country-list').val(gUserCountryId);
    $('#CountryId').val(gUserCountryId);

    $('#country-list').change(function() {
        calserver();
    });


    $.each(gFLevelList, function(i, obj) {
        $('#facility-level-list').append($('<option></option>').val(obj.FLevelId).html(obj.FLevelName));
        $('#FacilityId').append($('<option></option>').val(obj.FLevelId).html(obj.FLevelName));
    });

    $('#facility-level-list').change(function() {
        calserver();
    });

    $('body').animate({
        opacity: 1
    }, 1000, function() {
	
	MOStypeFacilityTable = $('#MOStypeFacilityTable').dataTable({
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

			$('a.itmEdit', MOStypeFacilityTable.fnGetNodes()).each(function() {
				$(this).click(function() {
					var nTr = this.parentNode.parentNode;
					var aData = MOStypeFacilityTable.fnGetData(nTr);
                        RecordId = aData[0];
                        $('#RecordId').val(aData[0]);
                        $('#MosTypeName').val(aData[2]);
                        $('#MinMos').val(aData[3]);
                        $('#MaxMos').val(aData[4]);
                        $('#ColorCode').val(aData[11]);
                        $('#IconMos').val(aData[6]);
                        $('#IconMos_Width').val(aData[7]);
                        $('#IconMos_Height').val(aData[8]);
                        $('#MosLabel').val(aData[9]);
                        $('#CountryId').val(aData[12]);
                        $('#FacilityId').val(aData[13]);

					msg = "Do you really want to edit this record?";
					onCustomModal(msg, "onEditPanel");
				});
			});
			$('a.itmDrop', MOStypeFacilityTable.fnGetNodes()).each(function() {
				$(this).click(function() {
					var nTr = this.parentNode.parentNode;
					var aData = MOStypeFacilityTable.fnGetData(nTr);
					RecordId = aData[0];
					msg = "Do you really want to delete this record?";
					//alert(RecordId);
					onCustomModal(msg, "onConfirmWhenDelete");
				});
			});
			$('a.itmMore', MOStypeFacilityTable.fnGetNodes()).each(function() {
				$(this).click(function() {
					var nTr = this.parentNode.parentNode;
					if ($(this).children('span').attr('class') == 'label label-info faminus') {
						$(this).children('span').attr('class', 'label label-info');
						var nRemove = $(nTr).next()[0];
						nRemove.parentNode.removeChild(nRemove);
					} else {
						$(this).children('span').attr('class', 'label label-info faminus');
						MOStypeFacilityTable.fnOpen(nTr, fnFormatDetails(nTr), 'details');
					}
				});
			});
		},
		
		"fnRowCallback": function(nRow, aData, iDisplayIndex) {
			$(nRow).attr('id', aData[0]);
			$(nRow).attr('CountryId', aData[12]);
			$(nRow).attr('FLevelId', aData[13]);
			return nRow;
		},
		
		"fnServerData" : function(sSource, aoData, fnCallback) {
			aoData.push({
				"name" : "action",
				"value" : 'getMOSTypeFacilityData'
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
				"name": "CountryId",
				"value": $('#country-list').val()
			});
			aoData.push({
				"name": "FacilityLevel",
				"value": $('#facility-level-list').val()
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
			},
			{
				"sClass": "SL",
				"sWidth": "8%",
				"bSortable": false
			},
			{
				"sClass": "MosTypeName",
				"sWidth": "12%",
				"bSortable": false
			},
			{
				"sClass": "MinMos",
				"sWidth": "10%",
				"bSortable": false
			},
			{
				"sClass": "MaxMos",
				"sWidth": "10%",
				"bSortable": false
			},
			{
				"sClass": "ColorCode",
				"sWidth": "9%",
				"bSortable": false
			},
			{
				"sClass": "IconMos",
				"sWidth": "10%",
				"bSortable": false
			},
			{
				"sClass": "IconMos_Width",
				"sWidth": "8%",
				"bSortable": false
			},
			{
				"sClass": "IconMos_Height",
				"sWidth": "8%",
				"bSortable": false
			},
			{
				"sClass": "MosLabel",
				"sWidth": "8%",
				"bSortable": false
			},
			{
				"sClass": "Action",
				"sWidth": "8%",
				"bSortable": false,
				"bVisible": false
			},
			{
				"sClass": "CountryId",
				"bSortable": false,
				"bVisible": false
			},
			{
				"sClass": "FLevelId",
				"bSortable": false,
				"bVisible": false
		}]
	});
	//Main end
	$('#MOStypeFacilityTable').click(function(event) {

		var id = $(event.target.parentNode).attr('id');
		gCountryId = $(event.target.parentNode).attr('CountryId');
		gFLevelId = $(event.target.parentNode).attr('FLevelId');
		 $('#MostypeFacilityId').val(id);

		var aData;
		$(MOStypeFacilityTable.fnSettings().aoData).each(function() {
			if ($(this.nTr).attr('id') == id) {
				$(this.nTr).addClass('row_selected');
				aData = MOStypeFacilityTable.fnGetData(this.nTr);
				gMosTypeId = id;
			} else
				$(this.nTr).removeClass('row_selected');
		});
		if (aData) {
			MOStypeFacilityDetailsTable.fnDraw();
		}
	});

	MOStypeFacilityDetailsTable = $('#MOStypeFacilityDetailsTable').dataTable({
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
		"sAjaxSource": baseUrl + "stage_one_datasourse.php",
		"fnDrawCallback": function(oSettings) {

			$('a.itmEdit', MOStypeFacilityDetailsTable.fnGetNodes()).each(function() {
				$(this).click(function() {
					var nTr = this.parentNode.parentNode;
					var aData = MOStypeFacilityDetailsTable.fnGetData(nTr);
					RecordId1 = aData[0];
					$('#RecordId1').val(aData[0]);
					$('#MosTypeName1').val(aData[2]);
					$('#MinMos1').val(aData[3]);
					$('#MaxMos1').val(aData[4]);
					$('#ColorCode1').val(aData[11]);
					$('#IconMos1').val(aData[6]);
					$('#IconMos_Width1').val(aData[7]);
					$('#IconMos_Height1').val(aData[8]);
					$('#MosLabel1').val(aData[9]);
					$('#CountryId1').val(aData[12]);
					$('#FacilityId1').val(aData[13]);

					msg = "Do you really want to edit this record?";
					onCustomModal(msg, "onEditPanel1");
				});
			});

			$('a.itmDrop', MOStypeFacilityDetailsTable.fnGetNodes()).each(function() {
				$(this).click(function() {
					var nTr = this.parentNode.parentNode;
					var aData = MOStypeFacilityDetailsTable.fnGetData(nTr);
					RecordId = aData[0];
					msg = "Do you really want to Delete this record?";
					onCustomModal(msg, "onConfirmWhenDeleteDetails");
				});
			});
		},
		"fnServerData": function(sSource, aoData, fnCallback) {
			aoData.push({
				"name": "action",
				"value": 'getMOSTypeFacilityDetailsData',
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
				"name": "MosTypeId",
				"value": gMosTypeId
			});
			aoData.push({
				"name": "CountryId",
				"value": gCountryId
			});
			aoData.push({
				"name": "FacilityLevel",
				"value": gFLevelId
			});
			$.ajax({
				"dataType": 'json',
				"type": "POST",
				"url": sSource,
				"data": aoData,
				"success": fnCallback
			});
		},
		"aoColumns": [{
				"bVisible": false
			},
			{
				"sClass": "SL",
				"sWidth": "8%",
				"bSortable": false
			},
			{
				"sClass": "MosTypeName",
				"sWidth": "12%",
				"bSortable": false
			},
			{
				"sClass": "MinMos",
				"sWidth": "10%",
				"bSortable": false
			},
			{
				"sClass": "MaxMos",
				"sWidth": "10%",
				"bSortable": false
			},
			{
				"sClass": "ColorCode",
				"sWidth": "9%",
				"bSortable": false
			},
			{
				"sClass": "IconMos",
				"sWidth": "10%",
				"bSortable": false
			},
			{
				"sClass": "IconMos_Width",
				"sWidth": "8%",
				"bSortable": false
			},
			{
				"sClass": "IconMos_Height",
				"sWidth": "8%",
				"bSortable": false
			},
			{
				"sClass": "MosLabel",
				"sWidth": "8%",
				"bSortable": false,
				"bVisible": false
			},
			{
				"sClass": "Action",
				"sWidth": "8%",
				"bSortable": false,
				"bVisible": false
			},
			{
				"sClass": "CountryId",
				"bSortable": false,
				"bVisible": false
			},
			{
				"sClass": "FLevelId",
				"bSortable": false,
				"bVisible": false
			}]
        });
        //Details End		
		
    });
    $(".numberinput").forceNumeric();
    $(".colorpicker3").colorpicker();
}); 