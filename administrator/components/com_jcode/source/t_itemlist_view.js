var itemTable;
var ItemNo = '';
var userId = '';
var engbId = '';
var ProductSubGroupId = " ";

var $ = jQuery.noConflict();

function bKey() {
	var checkboxstate = document.getElementById('bKeyItem').checked;
	$('#bKeyItem').val(checkboxstate);
}
function bCommon() {
	var checkboxstate = document.getElementById('bCommonBasket').checked;
	$('#bCommonBasket').val(checkboxstate);
}
$(function() {
	
	$.each(gItemGroupList, function(i, obj) {
		$('#item-group').append($('<option></option>').val(obj.ItemGroupId).html(obj.GroupName));
	});
	
	$('#item-group').val(gUserItemGroupId);
	
	$.each(gItemGroupList, function(i, obj) {
		$('#ItemGroupId').append($('<option></option>').val(obj.ItemGroupId).html(obj.GroupName));
	});
	
	$('#ItemGroupId').val(gUserItemGroupId);

	$("#item-group").change(function() {
		itemTable.fnDraw();
	});
	
	$("#ProductSubGroupId").change(function() {
		ProductSubGroupId = $("#ProductSubGroupId").val();
		itemTable.fnDraw();
	});

	itemTable = $('#itemTable').dataTable({
		"bFilter" : true,		
		"bSort" : true,
		"bInfo" : true,
		"bPaginate" : true,
		"bSortClasses" : false,
		"bProcessing" : true,
		"bServerSide" : true,
		"aaSorting" : [[9, 'asc'], [2, 'asc']],
		"aLengthMenu" : [[25, 50, 100], [25, 50, 100]],
		"iDisplayLength" : 25,
		"sPaginationType" : "full_numbers",
		//"sScrollX": "100%",
		"sAjaxSource" : baseUrl + "stage_two_datasource.php",
		"fnDrawCallback" : function(oSettings) {

			if (oSettings.aiDisplay.length == 0) {
				return;
			}
			var nTrs = $('#itemTable tbody tr');
			var iColspan = nTrs[0].getElementsByTagName('td').length;
			var sLastGroup = "";
			for (var i = 0; i < nTrs.length; i++) {
				var iDisplayIndex = i;
				var sGroup = oSettings.aoData[oSettings.aiDisplay[iDisplayIndex]]._aData[9];
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
				"value" : 'getItemListData'
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
				"name": "ItemGroupId",
				"value": $("#item-group").val()
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
			"sClass": "center-aln",
			// Product No
			"bVisible": false
		},{
			"sClass": "center-aln",
			// SL#
			"sWidth": "8%",
			"bSortable": false
		},{
			"sClass": "left-aln",
			// Product Code
			"sWidth": "10%",
			"bSortable": true
		},{
			"sClass": "left-aln",
			// Product Name
			"sWidth": "30%",
			"bSortable": true
		},{
			"sClass": "left-aln",
			// Short Name
			"sWidth": "12%",
			"bSortable": true
		},{
			"sClass": "center-aln",
			// Key Product
			"sWidth": "12%",
			"bSortable": true
		},{
			"sClass": "left-aln",
			//Product Subgroup
			"sWidth": "12%",
			"bSortable": true,
			"bSearchable": true
		},{
			"sClass": "center-aln",
			//Common Basket
			"sWidth": "12%",
			"bSortable": true//,
			//"bSearchable": true
		},{
			"sClass": "center-aln",
			// Action
			"bVisible": false,
			"sWidth": "12%",
			"bSortable": false
		},{
			"sClass": "left-aln",
			// Group Name
			"bVisible": false,
			"bSortable": false
		}]
	});
}); 