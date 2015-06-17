var regionTable;
var RecordId = '';
var userId = '';
var engbId = '';

var $ = jQuery.noConflict();

$(function() {
	
    $.each(gCountryList, function(i, obj) {
        $('#AllCountryId').append($('<option></option>').val(obj.CountryId).html(obj.CountryName));
    });
    
     $('#AllCountryId').val(gUserCountryId);
    
    $('#AllCountryId').change(function() {  
        regionTable.fnDraw();    
    });
	
	regionTable = $('#regionTable').dataTable({
		"bFilter" : true,		
		"bSort" : true,
		"bInfo" : true,
		"bPaginate" : true,
		"bSortClasses" : false,
		"bProcessing" : true,
		"bServerSide" : true,
		"aaSorting" : [[2, 'asc'], [4, 'asc']],
		"aLengthMenu" : [[25, 50, 100], [25, 50, 100]],
		"iDisplayLength" : 25,
		"sPaginationType" : "full_numbers",
		//"sScrollX": "100%",
		"sAjaxSource" : baseUrl + "stage_two_datasource.php",
		"fnDrawCallback" : function(oSettings) {

			if (oSettings.aiDisplay.length == 0) {
				return;
			}
			var nTrs = $('#regionTable tbody tr');
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
		"fnServerData" : function(sSource, aoData, fnCallback) {
			aoData.push({
				"name" : "action",
				"value" : 'getRegionData'
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
				"name" : "CountryId",
				"value" : $('#AllCountryId').val()
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
			"sWidth" : "15%",
			"bSortable": false
		},{
			"sClass" : "left-aln",
			"sWidth" : "85%"
		},{
		    "bVisible" : false
        },{
	        "bVisible" : false
		}]
	});
}); 