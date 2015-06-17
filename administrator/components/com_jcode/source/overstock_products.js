var oTblOverstockProducts;
$(function() {
	oTblOverstockProducts = $('#tbl-overstock-products').dataTable({
		"bFilter" : true,
		"bJQueryUI" : true,
		"bSort" : true,
		"bInfo" : true,
		"bPaginate" : true,
		"bSortClasses" : false,
		"bProcessing" : true,
		"bServerSide" : true,
		"aaSorting" : [[0, 'asc']],
		"sPaginationType" : "full_numbers",
		"aLengthMenu" : [[25, 50, 100], [25, 50, 100]],
		"iDisplayLength" : 25,
		"sAjaxSource" : baseUrl + "overstock_products_server.php",
		"fnServerData" : function(sSource, aoData, fnCallback) {
			aoData.push({
				"name" : "operation",
				"value" : "getOverStockProducts"
			});
			// aoData.push({
			// "name" : "ISO3",
			// "value" : iSo3
			// });
			$.ajax({
				"dataType" : 'json',
				"type" : "GET",
				"url" : sSource,
				"data" : aoData,
				"success" : function(json) {
					fnCallback(json);
				}
			});
		},
		"aoColumns" : [{
			"bVisible" : true,
			"bSortable" : false,
			"sWidth" : "5%"
		}, {
			"sWidth" : "25%"
		}, {
			"sWidth" : "14%",
			"sClass" : "right-aln",
		}, {
			"sClass" : "right-aln",
			"sWidth" : "14%",
			"bSortable" : true,
		}, {
			"sClass" : "right-aln",
			"sWidth" : "14%",
			"bSortable" : true,
		}, {
			"sClass" : "right-aln",
			"sWidth" : "14%",
			"bSortable" : true,
		}]
	});
});
