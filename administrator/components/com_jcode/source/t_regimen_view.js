var RegimenTable;
var RegimenId = '';
var OptionId;
var OptionNext;
var RegimenItemList = new Array();
var Mode = '';
var RegimenItemId;
var ComTable;
var userId = '';
var engbId = '';
var regimenMasterListId='';

var $ = jQuery.noConflict();

$(function() {

	$.each(gItemGroupList, function(i, obj) {
		$('#ItemGroupId').append($('<option></option>').val(obj.ItemGroupId).html(obj.GroupName));
	});
	
	$('#ItemGroupId').val(gUserItemGroupId);

	$('#ItemGroupId').change(function() {
		RegimenTable.fnDraw();
	});
 
	RegimenTable = $('#RegimenTable').dataTable({
		"bFilter": true,           
		"bSort": true,
		"bInfo": true,
		"bPaginate": true,
		"bSortClasses": false,
		"bProcessing": true,
		"bServerSide": true,
		"aaSorting": [[2, 'asc']],
		"sPaginationType": "full_numbers",
		"sAjaxSource": baseUrl + "t_regimen_server.php",
		"fnDrawCallback": function(oSettings) {
			if (oSettings.aiDisplay.length == 0) {
				return;
			}

			var nTrs = $('#RegimenTable tbody tr');
			var iColspan = nTrs[0].getElementsByTagName('td').length;
			var sLastGroup = "";
			for (var i = 0; i < nTrs.length; i++) {
				var iDisplayIndex = i;
				var sGroup = oSettings.aoData[oSettings.aiDisplay[iDisplayIndex]]._aData[4];
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
		"fnRowCallback": function(nRow, aData, iDisplayIndex) {
			$(nRow).attr('id', aData[0]);
			return nRow;
		},
		"fnServerData": function(sSource, aoData, fnCallback) {
			aoData.push({
				"name": "action",
				"value": 'getRegimenData'
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
				"name": "ItemGroupId",
				"value": $('#ItemGroupId').val()
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
		},{
			"sClass": "SL",
			"sWidth": "8%",
			"bSortable": false
		},{
			"sClass": "left-aln"
			//Regimen
		},{
			"sClass": "Action",
			"sWidth": "15%",
			"bSortable": false,
			"bVisible": false
		},{
			"sClass": "FormulationName",
			"bVisible": false
		}]
	});  
});