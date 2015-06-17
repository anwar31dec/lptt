var oTable_country;
var oTable_regimen;
var SelCountryId = "";
var mode = 'display';
var checkStatus;
var RegimenSel = "";
var SelCountryName = '';

function showSelected(){
    checkStatus = $('#ssel').prop('checked');  
    if(checkStatus == true){
        mode = 'display';
        oTable_regimen.fnDraw(); 
    }else{
        mode = 'edit';
        oTable_regimen.fnDraw();
    }   
}

$(function() {
    
    $('#sselSec').hide();
    
    $.each(gItemGroupList, function(i, obj) {
		$('#ItemGroupId').append($('<option></option>').val(obj.ItemGroupId).html(obj.GroupName));
	});
	
	$('#ItemGroupId').val(gUserItemGroupId);
 
    $('#ItemGroupId').change(function() {
        oTable_regimen.fnDraw();
    });
    
    oTable_country = $("#gridDataCountry").dataTable({
		"bFilter": true,
		"bJQueryUI": true,
        "sPaginationType": "full_numbers",
		"bSort": true,
		"bInfo": true,
		"bPaginate": true,
		"bSortClasses": false,
		"bProcessing": true,
		"bServerSide": true,
		"sScrollX": "100%",
        "aLengthMenu": [[25, 50, 100], [25, 50, 100]],
		"iDisplayLength": 25,
		"sAjaxSource": baseUrl + "country_regimen_datasource.php",
    	"aaSorting": [[2, 'asc']],
		"oLanguage": {
			"sLengthMenu": "Display _MENU_ Records",
			"sZeroRecords": "No Record Found",
			"sInfo": "Showing _START_ to _END_ of _TOTAL_ Records",
			"sInfoEmpty": "Showing 0 to 0 of 0 Records",
			"sInfoFiltered": "(filtered from _MAX_ total Records)"
		},
		"fnDrawCallback": function() {},
		"fnRowCallback": function(nRow, aData, iDisplayIndex) {
		    $(nRow).attr('id', aData[0]);
            $(nRow).attr('cname', aData[2]);
			return nRow;
		},
		"fnServerData": function(sSource, aoData, fnCallback) {
			aoData.push({
				"name": "action",
				"value": "getCountryList"
			});
			
			aoData.push({
				"name": "lan",
				"value": lan
			});
            aoData.push({
                "name": "userName",
                "value": userName
            });
			$.ajax({
				"dataType": 'json',
				"type": "POST",
				"url": sSource,
				"data": aoData,
				"success": function(json) {
					fnCallback(json);
				}
			});            
		},
		"aoColumns": [{
			"bVisible": false
		},{
			"sClass": "SL",
			"bSortable": false,
            "sWidth": "10%"
		},{
			"sClass": "Countries",
			"bSortable": true,
            "sWidth": "85%"
		}]
	});
    
	$('#gridDataCountry').click(function(event) {
		var id = $(event.target.parentNode).attr('id');
		SelCountryId = $(event.target.parentNode).attr('id');
        SelCountryName = $(event.target.parentNode).attr('cname');
		 
		var aData;
		var t;
		$(oTable_country.fnSettings().aoData).each(function() {
			if ($(this.nTr).attr('id') == id) {
				$(this.nTr).addClass('row_selected');
				aData = oTable_country.fnGetData(this.nTr);
			} else
			$(this.nTr).removeClass('row_selected');
		});
 	   if (aData) {
 	        checkStatus = $('#ssel').prop('checked');  
            if(checkStatus == true){
                mode = 'display';
                $('#sselSec').show();
                oTable_regimen.fnDraw(); 
            }else{
                mode = 'edit';
                $('#sselSec').show();
                oTable_regimen.fnDraw();
            }
 	   }
    });
    
    oTable_regimen = $("#gridDataRegimen").dataTable({
		"bFilter": true,
		"bJQueryUI": true,
        "sPaginationType": "full_numbers",
		"bSort": true,
		"bInfo": true,
		"bPaginate": true,
		"bSortClasses": false,
		"bProcessing": true,
		"bServerSide": true,
		"sScrollX": "100%",
        "aLengthMenu": [[25, 50, 100], [25, 50, 100]],
		"iDisplayLength": 25,
		"sAjaxSource": baseUrl + "country_regimen_datasource.php",
		"oLanguage": {
			"sLengthMenu": "Display _MENU_ Records",
			"sZeroRecords": "No Record Found",
			"sInfo": "Showing _START_ to _END_ of _TOTAL_ Records",
			"sInfoEmpty": "Showing 0 to 0 of 0 Records",
			"sInfoFiltered": "(filtered from _MAX_ total Records)"
		},
		"fnDrawCallback" : function(oSettings) {
 	        if (oSettings.aiDisplay.length == 0) {
				return;
			}		
			var nTrs = $('#gridDataRegimen tbody tr');
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
		    $('td input.datacell', oTable_regimen.fnGetNodes()).each(function() {
		      $(this).prop("disabled", true); 
				/*$(this).click(function() {
					var nTr = this.parentNode.parentNode;
					var aData = oTable_regimen.fnGetData(nTr);
					var checkcon = $(this).prop('checked');  
					var CountryRegimenId = aData[0];
                    var RegimenId = aData[3];  
					$.ajax({
						"type": "POST",
						"url": baseUrl + "country_regimen_datasource.php",
						"data": {
						      action: 'insertAllorOneMapping',
                              CountryRegimenId: CountryRegimenId,
                              RegimenId: RegimenId,
                              SelCountryId: SelCountryId,
                              ItemGroupId: $('#ItemGroupId').val(),
                              checkVal: checkcon
                        },
						"success": function(response) {
                            var oSettings = oTable_regimen.fnSettings();
                            var page = Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength)
                            oTable_regimen.fnDraw();
                            oTable_regimen.fnPageChange(page);
						}
					 });
				 });*/
			 });
		},
		"fnRowCallback": function(nRow, aData, iDisplayIndex) {
			return nRow;
		},
		"fnServerData": function(sSource, aoData, fnCallback) {
			aoData.push({
				"name": "action",
				"value": "getRegimenList"
                
			});
			
			aoData.push({
				"name": "lan",
				"value": lan
			});
            aoData.push({
				"name": "SelCountryId",
				"value": SelCountryId
			});
			aoData.push({
				"name": "RegimenSel",
				"value": RegimenSel
			});
            aoData.push({
				"name": "ItemGroupId",
				"value": $('#ItemGroupId').val()
			});
            aoData.push({
				"name": "mode",
				"value": mode
			});
			$.ajax({
				"dataType": 'json',
				"type": "POST",
				"url": sSource,
				"data": aoData,
				"success": function(json) {
					fnCallback(json);
				}
			});
		},
		"aoColumns": [{
			"bVisible": false
		},{
			"sClass": "RegimenName",
			"bSortable": false
		},{
			"sClass": "FormulationName",
			"bSortable": false,
            "bVisible": false
		}]
	});

});