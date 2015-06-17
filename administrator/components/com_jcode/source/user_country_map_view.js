var oTable_user;
var oTable_country;
var userName = "";
var mode = 'display';

$(function() {

    oTable_user = $("#gridDataUser").dataTable({
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
		"sAjaxSource": baseUrl + "user_country_map_datasource.php",
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
		  	$(nRow).attr('userN', aData[3]);
			return nRow;
		},
		"fnServerData": function(sSource, aoData, fnCallback) {
			aoData.push({
				"name": "action",
				"value": "getJoomlaLabUsers"
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
            "sWidth": "12%"
		},{
			"sClass": "Users",
			"bSortable": true,
            "sWidth": "86%"
		}]
	});
    
	$('#gridDataUser').click(function(event) {
		var id = $(event.target.parentNode).attr('id');
		userName = $(event.target.parentNode).attr('userN');
		var aData;
		var t;
		$(oTable_user.fnSettings().aoData).each(function() {
			if ($(this.nTr).attr('id') == id) {
				$(this.nTr).addClass('row_selected');
				aData = oTable_user.fnGetData(this.nTr);
			} else
			$(this.nTr).removeClass('row_selected');
		});
 	   if (aData) {
 	      mode = 'edit';
 	      oTable_country.fnDraw();
 	   }
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
		"sAjaxSource": baseUrl + "user_country_map_datasource.php",
		"oLanguage": {
			"sLengthMenu": "Display _MENU_ Records",
			"sZeroRecords": "No Record Found",
			"sInfo": "Showing _START_ to _END_ of _TOTAL_ Records",
			"sInfoEmpty": "Showing 0 to 0 of 0 Records",
			"sInfoFiltered": "(filtered from _MAX_ total Records)"
		},
		"fnDrawCallback": function() {},
		"fnRowCallback": function(nRow, aData, iDisplayIndex) {
			return nRow;
		},
		"fnServerData": function(sSource, aoData, fnCallback) {
			aoData.push({
				"name": "action",
				"value": "getCountryList",
                "lan":lan
			});
            aoData.push({
				"name": "userName",
				"value": userName
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
			"sClass": "CountryName",
			"bSortable": false
		}]
	});
    
    $(".datacell").each(function(){       
        $(this).prop("disabled", true);                                               
    })

});