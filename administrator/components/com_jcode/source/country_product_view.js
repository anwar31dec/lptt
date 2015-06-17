var oTable_country;
var oTable_product;
var SelCountryId = "";
var mode = 'display';
var checkStatus;
var SelCountryName = '';

function showSelected(){
    checkStatus = $('#ssel').prop('checked');  
    if(checkStatus == true){
        mode = 'display';
        oTable_product.fnDraw(); 
    }else{
        mode = 'edit';
        oTable_product.fnDraw();
    }   
}

$(function() {
    
    $('#sselSec').hide();
    
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
		"sAjaxSource": baseUrl + "country_product_datasource.php",
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
			//alart'lan'
            aoData.push({
				"name": "userName",
				"value": userName
			});
			aoData.push({
				"name": "lan",
				"value": lan
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
            "sWidth": "15%"
		},{
			"sClass": "Countries",
			"bSortable": true,
            "sWidth": "80%"
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
                oTable_product.fnDraw(); 
            }else{
                mode = 'edit';
                $('#sselSec').show();
                oTable_product.fnDraw();
            }
 	   }
    });
    
    oTable_product = $("#gridDataProduct").dataTable({
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
 	    "aaSorting": [[3, 'asc'], [2, 'asc']],
        "aLengthMenu": [[25, 50, 100], [25, 50, 100]],
		"iDisplayLength": 25,
		"sAjaxSource": baseUrl + "country_product_datasource.php",
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
			var nTrs = $('#gridDataProduct tbody tr');
			var iColspan = nTrs[0].getElementsByTagName('td').length;
			var sLastGroup = "";
			for (var i = 0; i < nTrs.length; i++) {
				var iDisplayIndex = i;	
				var sGroup = oSettings.aoData[oSettings.aiDisplay[iDisplayIndex]]._aData[3];
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
            $('td input.datacell', oTable_product.fnGetNodes()).each(function() {
                $(this).prop("disabled", true);    
            });
		},
		"fnRowCallback": function(nRow, aData, iDisplayIndex) {
			return nRow;
		},
		"fnServerData": function(sSource, aoData, fnCallback) {
			aoData.push({
				"name": "action",
				"value": "getProductList"
				
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
			"sClass": "ProductCode",
			"bSortable": false,
            "sWidth": "15%"
		},{
			"sClass": "ProductName",
			"bSortable": true,
            "sWidth": "80%"
		},{
			"sClass": "ProductGroup",
			"bSortable": false,
            "bVisible": false
		}]
	});

});