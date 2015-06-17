var formulationTable;
var RecordId = '';
var userId = '';
var engbId = '';
var ProductSubGroupId = " ";

var $ = jQuery.noConflict();

$(function() {

	userId = $('#userId').val();
	engbId = $('#en-GBId').val();
	
    $.each(gItemGroupList, function(i, obj) {
        $('#item-group').append($('<option></option>').val(obj.ItemGroupId).html(obj.GroupName));
    });

    $('#item-group').val(gUserItemGroupId);
    
     $('#item-group').change(function() {
         formulationTable.fnDraw();
    })
	
	formulationTable = $('#formulationTable').dataTable({
		"bFilter" : true,		
		"bSort" : true,
		"bInfo" : true,
		"bPaginate" : true,
		"bSortClasses" : false,
		"bProcessing" : true,
		"bServerSide" : true,
		"aaSorting" : [[4, 'asc'], [2, 'asc']],
		"aLengthMenu" : [[25, 50, 100], [25, 50, 100]],
		"iDisplayLength" : 25,
		"sPaginationType" : "full_numbers",
		//"sScrollX": "100%",
		"sAjaxSource" : baseUrl + "stage_two_datasource.php",
		"fnDrawCallback" : function(oSettings) {

			if (oSettings.aiDisplay.length == 0) {
				return;
			}
			
            var nTrs = $('#formulationTable tbody tr');
            var iColspan = nTrs[0].getElementsByTagName('td').length;
            var sLastGroup = "";
            for (var i = 0; i < nTrs.length; i++) {
                var iDisplayIndex = i;
                var sGroup = oSettings.aoData[oSettings.aiDisplay[iDisplayIndex]]._aData[5];
                if (sGroup != sLastGroup) {
                    var nGroup = document.createElement('tr');
                    var nCell = document.createElement('td');
                    nCell.colSpan = iColspan;
                    nCell.className = "group";
                    nCell.innerHTML = sGroup;
                    nGroup.appendChild(nCell);
                    nTrs[i].parentNode.insertBefore(nGroup, nTrs[i]);
                    sLastGroup = sGroup;
                }
            }
		},
		"fnServerData" : function(sSource, aoData, fnCallback) {
			aoData.push({
				"name" : "action",
				"value" : 'getFormulationData'
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
                "name": "itemGroupId",
                "value": $('#item-group').val()
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
			"sClass" : "center-aln",
			"bVisible" : false
		}, {
			"sClass" : "center-aln",
			// SL#
			"sWidth" : "5%",
			"bSortable" : false
		}, {
			"sClass" : "left-aln",
			// FormulationType
			"sWidth" : "30%",
			"bSortable" : true
		}, {
			"sClass" : "left-aln",
			// FormulationTypeFrench
			"sWidth" : "35%",
			"bSortable" : true
		}, {
			"sClass" : "left-aln",
			// ItemGroup
			"sWidth" : "10%",
			"bSortable" : true
		}, {
			"sClass" : "left-aln",
			// ServiceType
			"bSortable" : true,
			"bVisible": false
		}, {
			"sClass" : "left-aln",
			// ColorCode
			"sWidth" : "10%",
			"bSortable" : false
		}]
	});
}); 