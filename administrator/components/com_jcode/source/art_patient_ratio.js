var yearList;
var oTableFacility;
var oTableMonthlyStatus;
var gFacilityCode;
var gMonthId = 0;
var gYearId = 0;
var svg;
var endDate = new Date();

var patientTrendTimeSeries;

var pending_Installation_Reason;

pending_Installation_Reason = ( function(iobj) {

		var margin = {
			top : 40,
			right : 0,
			bottom : 0,
			left : 0
		}, width = 898, height = 350 - margin.top - margin.bottom, formatNumber = d3.format(",d"), transitioning;
		
		var width = parseInt(d3.select('#art-patient-ratio').style('width'))
			//, width = width - margin.left - margin.right
			, ratio = .4, height = (width * ratio);

		//alert(width);

		var x = d3.scale.linear().domain([0, width]).range([0, width]);

		var y = d3.scale.linear().domain([0, height]).range([0, height]);

		var treemap = d3.layout.treemap().children(function(d, depth) {
			return depth ? null : d.children;
		}).sort(function(a, b) {
			return a.value - b.value;
		}).ratio(height / width * 0.5 * (1 + Math.sqrt(5))).round(false);

		var color = d3.scale.category20c();

		iobj.init = function() {
		}
		pendingInstallationReason = function(root) {
			if(svg != undefined){
			 	d3.select("svg").remove();
			 }
			 
			 svg = d3.select("#art-patient-ratio")
			.append("svg").attr("width", width + margin.left + margin.right)
			.attr("height", height + margin.bottom + margin.top)
			.style("margin-left", -margin.left + "px")
			.style("margin.right", -margin.right + "px")
			.append("g").attr("transform", "translate(" + margin.left + "," + margin.top + ")").style("shape-rendering", "crispEdges");

			var grandparent = svg.append("g");//.attr("class", "grandparent");

			grandparent.append("rect").attr("y", -margin.top).attr("width", width).attr("height", margin.top);

			grandparent.append("text").attr("x", 6).attr("y", 6 - margin.top).attr("dy", ".75em").style("font-size", "24px");

			initialize(root);
			accumulate(root);
			layout(root);
			display(root);

			function initialize(root) {
				root.x = root.y = 0;
				root.dx = width;
				root.dy = height;
				root.depth = 0;
			}

			function accumulate(d) {
				return d.children ? d.value = d.children.reduce(function(p, v) {
					return parseFloat(p) + parseFloat(accumulate(v));
				}, 0) : d.value;
			}

			function layout(d) {
				if (d.children) {
					treemap.nodes({
						children : d.children
					});
					d.children.forEach(function(c) {
						c.x = d.x + c.x * d.dx;
						c.y = d.y + c.y * d.dy;
						c.dx *= d.dx;
						c.dy *= d.dy;
						c.parent = d;
						layout(c);
					});
				}
			}

			function display(d) {
				grandparent.datum(d.parent).on("click", transition).select("text").text(name(d));

				var g1 = svg.insert("g", ".grandparent").datum(d).attr("class", "depth");

				var g = g1.selectAll("g").data(d.children).enter().append("g");

				g.filter(function(d) {
					return d.children;
				}).classed("children", true).on("click", transition);

				g.selectAll(".child").data(function(d) {
					return d.children || [d];
				}).enter().append("rect").attr("class", "child").call(rect);

				g.append("rect").attr("class", "parent").call(rect).append("title").text(function(d) {
					//return formatNumber(d.value);
					var numb = (d.value * 100) / (d.parent.totalval);
					return numb.toFixed(1) + '%'
				});
				// alert(d.parent.totalval); formatNumber((formatNumber(d.value) * 100)/formatNumber(d.totalval)) ;

				g.append("text").attr("dy", ".75em").text(function(d) {

					var numb = (d.value * 100) / (d.parent.totalval);

					numb = numb.toFixed(1) + '%'
					return d.name + ": " + numb;
				}).style("font-size", "17px").call(text);

				function transition(d) {

					if (transitioning || !d)
						return;
					transitioning = true;

					var g2 = display(d), t1 = g1.transition().duration(750), t2 = g2.transition().duration(750);

					// Update the domain only after entering new elements.
					x.domain([d.x, d.x + d.dx]);
					y.domain([d.y, d.y + d.dy]);

					// Enable anti-aliasing during the transition.
					svg.style("shape-rendering", null);

					// Draw child nodes on top of parent nodes.
					svg.selectAll(".depth").sort(function(a, b) {
						return a.depth - b.depth;
					});

					// Fade-in entering text.
					g2.selectAll("text").style("fill-opacity", 0);

					// Transition to the new view.
					t1.selectAll("text").call(text).style("fill-opacity", 0);
					t2.selectAll("text").call(text).style("fill-opacity", 1);
					t1.selectAll("rect").call(rect);
					//t1.selectAll("rect").call(rect).style("fill", function(d) { return color((d.children ? d : d.parent).name); });

					t2.selectAll("rect").call(rect);
					//t2.selectAll("rect").call(rect).style("fill", function(d) { return color((d.children ? d : d.parent).name); });

					// Remove the old node when the transition is finished.
					t1.remove().each("end", function() {
						svg.style("shape-rendering", "crispEdges");
						transitioning = false;
					});

				}

				return g;
			}

			function text(text) {
				text.attr("x", function(d) {
					//return x(d.x) + 6;
					//console.log(d.name + " " + d.x + " " + (d.dx / 2) + " " + (this.getBBox().width/2));
					return x(d.x + (d.dx / 2));	// - ((this.getBBox().width)/2)
				}).attr("y", function(d) {
					//return y(d.y) + 6;
					return y(d.y + d.dy / 2);
				}).attr("text-anchor", "middle").style("fill","white");
			}

			function rect(rect) {
				rect.style("fill", function(d,i) {
					//return color((d.children ? d : d.parent).name);
					//return color(i);
					return "hsl(" + Math.random() * 360 + ",100%,50%)";

				})
				rect.attr("x", function(d) {
					return x(d.x);
				}).attr("y", function(d) {
					return y(d.y);
				}).attr("width", function(d) {
					return x(d.x + d.dx) - x(d.x);
				}).attr("height", function(d) {
					return y(d.y + d.dy) - y(d.y);
				});
			}

			function name(d) {

				return d.parent ? "  <<  Back " : d.name;
				//name(d.parent) + "  <  Back " + d.name  //
			}

		};

		return iobj;
	}(pending_Installation_Reason || {})); 
	
	

function getPatientRatio() {
	$.ajax({
		url : baseUrl + "art_patient_ratio_server.php",
		data : {
			'operation' : 'getPatientRatio'
		},
		success : function(response) {
			jsonResponse = JSON.parse(response);
			//alert(response);
			pendingInstallationReason(jsonResponse);
		}
	});
}

function getPatientRatio() {
	$.ajax({
		type : 'post',
		dataType : "json",
		url : baseUrl + 'art_patient_ratio_server.php',
		data : {
			"operation" : 'getPatientRatio',			
			"CountryId" : gCountryId,
			"MonthId" : gMonthId,
			"YearId" : gYearId						
		},
		success : function(response) {
			pendingInstallationReason(response);
		}
	});
}


$(function() {

	$.each(gMonthList, function(i, obj) {
		$('#month-list').append($('<option></option>').val(obj.MonthId).html(obj.MonthName));
	});
	
	//alert(gMonthId);
	if ( gMonthId == 0) {
		endDate.setMonth(objInit.svrLastMonth - 1);
		$("#month-list").val(objInit.svrLastMonth);
	} else {
		endDate.setMonth(gMonthId);
		$("#month-list").val(gMonthId);
	}


	gMonthId = $('#month-list').val();

	$.each(gYearList, function(i, obj) {
		$('#year-list').append($('<option></option>').val(obj.YearName).html(obj.YearName));
	});
	
	
	if ( gYearId > 0){
		endDate.setYear(gYearId);
		$("#year-list").val(gYearId);
	}

	$("#year-list").val(endDate.getFullYear());

	gYearId = $('#year-list').val();

	$.each(gCountryList, function(i, obj) {
		$('#country-list').append($('<option></option>').val(obj.CountryId).html(obj.CountryName));
	});
	
	$('#country-list').val(gUserCountryId);

	gCountryId = $('#country-list').val();
	
	$("#left-arrow").click(function() {

		if (endDate.getMonth() == 0 && endDate.getFullYear() == gYearList[0].YearName)
			return;

		endDate.prevMonth();

		$("#month-list").val(endDate.getMonth() + 1);
		$("#year-list").val(endDate.getFullYear());

		gMonthId = $("#month-list").val();
		gYearId = $("#year-list").val();

		getPatientRatio();
	});

	$("#right-arrow").click(function() {

		if (endDate.getMonth() == 11 && endDate.getFullYear() == gYearList[gYearList.length - 1].YearName)
			return;

		endDate.nextMonth();
		$("#month-list").val(endDate.getMonth() + 1);
		$("#year-list").val(endDate.getFullYear());
		gMonthId = $("#month-list").val();
		gYearId = $("#year-list").val();

		getPatientRatio();
	});

	$("#month-list").change(function() {
		endDate.setMonth($("#month-list").val() - 1);

		gMonthId = $("#month-list").val();
		gYearId = $("#year-list").val();
		
		getPatientRatio();
		
	});

	$("#year-list").change(function() {
		endDate.setYear($("#year-list").val());
		endDate.setMonth($("#month-list").val() - 1);

		gMonthId = $("#month-list").val();
		gYearId = $("#year-list").val();

		getPatientRatio();
	});

	gCountryId = $("#country-list").val();

	$("#country-list").change(function() {
		gCountryId = $("#country-list").val();		
		getPatientRatio();
	});
	
	getPatientRatio();

});

