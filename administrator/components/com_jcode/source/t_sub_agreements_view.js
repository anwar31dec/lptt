var agreementTable;
var RecordId = '';
var userId = '';
var engbId = '';
var FundingSourceId=0;

var $ = jQuery.noConflict();

$(function() {
	
	//$('#example').dataTable();
	
	userId = $('#userId').val();
	engbId = $('#en-GBId').val();

    $.each(gItemGroupList, function(i, obj) {
        $('#ItemGroup').append($('<option></option>').val(obj.ItemGroupId).html(obj.GroupName));
        $('#item-group').append($('<option></option>').val(obj.ItemGroupId).html(obj.GroupName));
    });

    $('#item-group').change(function() {
        agreementTable.fnDraw();
    });
	
	agreementTable = $('#agreementTable').dataTable({
		"bFilter" : true,		
		"bSort" : true,
		"bInfo" : true,
		"bPaginate" : true,
		"bSortClasses" : false,
		"bProcessing" : true,
		"bServerSide" : true,
		"aaSorting" : [[5, 'asc'],[3, 'asc'],[4, 'asc']],
		"aLengthMenu" : [[25, 50, 100], [25, 50, 100]],
		"iDisplayLength" : 25,
		"sPaginationType" : "full_numbers",
		//"sScrollX": "100%",
		"sAjaxSource" : baseUrl + "stage_one_datasourse.php",
		"fnDrawCallback" : function(oSettings) {

			if (oSettings.aiDisplay.length == 0) {
				return;
			}
			var nTrs = $('#agreementTable tbody tr');
			var iColspan = nTrs[0].getElementsByTagName('td').length;
			var sLastGroup = "";
			for (var i = 0; i < nTrs.length; i++) {
				var iDisplayIndex = i;
				var sGroup = oSettings.aoData[oSettings.aiDisplay[iDisplayIndex]]._aData[2];
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
				"value" : 'getAgreementData'
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
			"bVisible": false
		}, {
			"sClass": "center-aln",
			//SL
			"sWidth": "10%",
			"bSortable": false
		}, {
			"sClass": "left-aln",
			"sWidth": "10%",
		   "bVisible": false
		}, {
			"sClass": "left-aln",
			"sWidth": "30%"
		}, {
			"sClass": "left-aln",
			"sWidth": "60%"
		}, {
			"sClass": "Action",
			"sWidth": "15%",
			"bSortable": false,
			"bVisible": false
		}]
	});

}); 