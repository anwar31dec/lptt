urls = {
		bd : baseUrl + "/dashboard/js/africa_countries_iso_topo.json",								
		voteInfo : baseUrl + "/dashboard/js/map_bangla_source.json",
		winnerParty : baseUrl + "/dashboard/js/winner_party_source.json",
		top2party : baseUrl + "/dashboard/js/top2party_source.json"
};	
//world.json
var active, centered, consStyle;
var country;

var x2 = 51.68311627606212;
var x1 = -17.01071856913639;

var y2 = -46.63388413770829;
var y1 = 37.589750373436786;

var centX = x1 + (x2 - x1) / 2;
var centY = y1 + (y2 - y1) / 2;

m_width = $("#map").width();
var width = parseInt(d3.select('#map').style('width'))
	, mapRatio = 0.47, height = (width * mapRatio);
	
var scrollY	= height+20;
//d3.select("#cparams-panel").style('height', height);
$("#cparams-panel").css("height", height+115);

var projection = d3.geo.mercator().scale(200).translate([width/2, height/2]).center([centX, centY]); 
var path = window.path = d3.geo.path().projection(projection);

var svg = d3.select("#map")
			.append("svg")
			.attr("preserveAspectRatio", "xMidYMid")
			.attr("viewBox", "0 0 " + width + " " + height)
			.attr("width", m_width)
			.attr("height", m_width * height / width);

svg.append("rect").attr("width", width).attr("height", height).on("click", country_clicked);

var g = svg.append("g");

var zoom;

queue().defer(d3.json, urls.bd).defer(d3.json, urls.voteInfo).defer(d3.json, urls.winnerParty).defer(d3.json, urls.top2party).await(render);
					
var template = _.template(d3.select('#tooltip-template').html());
	
function render(err, bd, voteInfo, winnerParty, top2party) {	
	window.bd = bd;
	window.voteInfo = voteInfo;

	voteInfo = window.voteInfo = _(voteInfo).chain().map(function(d) {
			return [d.id, d];
		}).object().value();
	//console.log(voteInfo);	

	var vindex = '';
	var states = g.append("g")
    .attr("id", "countries")
	.selectAll("path").data(topojson.object(bd, bd.objects.layer1).geometries)
	.enter()
	.append("path")
	.attr("d", path)
	.attr("class", "feature")
	.style('fill', function(d) { 
		//console.log(d.properties["ID"]);
		if ((d.properties["ID"] != null) && (d.properties["ID"] != undefined)) {
			vindex = d.properties["ID"];
			if (voteInfo[vindex] != undefined) {
				//console.log(vindex);
				//console.log(voteInfo[vindex].bgcolor);
				return voteInfo[vindex].bgcolor;//voteInfo[d.properties["ID"]];
			}
			else return '#CCCCCC';
		}
		else return '#CCCCCC';

		})
	.on("click", country_clicked);
	//.on("click", constituencyDetails)
	//.call(zoom)
	//.call(clickZoomMap);		
	
			g.append("g").selectAll("path")
			.data(topojson.object(bd, bd.objects.layer1).geometries)
			.enter().append("text")
			  .attr("transform", function(d) { return "translate(" + path.centroid(d) + ")"; })
			  .attr("id", function(d) { return 'label-'+d.properties["ID"]; })
			  .attr("dy", ".35em")
			  .attr("class", "country-label")
			  .text(function(d) { return d.properties["FIRST_ADM0"]; });
	  

	states.on('mouseover', tooltipShow)
	.on('mouseout', tooltipHide);
	//console.log(country);
		
	var xyz = [width / 2.3, height / 3.25, 3.5];
    country = null;
    zoom(xyz);	
};

var iSo3 = '';

function constituencyDetails(d){
	iSo3 = d.ISO3;
			
	$("#cparams-header strong").text(d.properties.name);
	
	oTableCProfileParams.fnDraw();
}

function clickZoomMap() {"use strict"
	var x, y, k
    
    var constituencies = topojson.object(bd, bd.objects.layer1).geometries;
    var constituency = constituencies.filter(function(d1) { return d1.id === "BFA"; })[0];
	
	if (constituency && centered !== constituency) {
		var centroid = path.centroid(constituency)
		x = centroid[0]
		y = centroid[1]
		k = 3
		centered = constituency
	} else {
		// zoom out, this happens when user clicks on the same country
		x = width / 2
		y = height / 2
		k = 1
		centered = null
	}

	g.selectAll("path").classed("active", centered &&
	function(d) {
		return d === centered
	})

	g.transition()
	.duration(1000)
	.delay(100)
	.attr("transform", "translate(" + width / 2 + "," + height / 2 + ")scale(" + k + ")translate(" + -x + "," + -y + ")")
	.style("stroke-width", 1.5 / k + "px")
}

function tooltipShow(d, i) {
	
	consStyle = d3.select(this).style("fill");
	
	 d3.select(this)
	.style("fill", "orange");
	
	datum = voteInfo[d.properties["ID"]];
			    
    if (!datum) return;
	
	if(datum.bSiaps == 0) return;
    
    $(this).tooltip({
        title: template(datum),
        html: true,
        container: svg.node().parentNode,
        placement: 'auto'
    }).tooltip('show');					    
}

function tooltipHide(d, i) {
	 d3.select(this)
	.style("fill", consStyle);
	
    $(this).tooltip('hide');
}

function reset() {
	g.selectAll(".active").classed("active", active = false);
	g.transition().duration(750).attr("transform", "");
}

function zoomed() {
	console.log(zoom.scale());
    g.attr("transform",
        "translate(" + zoom.translate() + ")" +
        "scale(" + zoom.scale() + ")"
    );
}

function interpolateZoom (translate, scale) {
    var self = this;
    return d3.transition().duration(350).tween("zoom", function () {
        var iTranslate = d3.interpolate(zoom.translate(), translate),
            iScale = d3.interpolate(zoom.scale(), scale);
        return function (t) {
            zoom
                .scale(iScale(t))
                .translate(iTranslate(t));
            zoomed();
        };
    });
}

function zoomClick() {	
	
	// if(this.id === 'home-map'){
		 // interpolateZoom([0, 0], 1);
		 // return;
	// }
			
    var clicked = d3.event.target,
        direction = 1,
        factor = 0.2,
        target_zoom = 1,
        center = [width / 2, height / 2],
        extent = zoom.scaleExtent(),
        translate = zoom.translate(),
        translate0 = [],
        l = [],
        view = {x: translate[0], y: translate[1], k: zoom.scale()};

    d3.event.preventDefault();
    direction = (this.id === 'zoom_in') ? 1 : -1;
    
    target_zoom = zoom.scale() * (1 + factor * direction);

    if (target_zoom < extent[0] || target_zoom > extent[1]) { return false; }

    translate0 = [(center[0] - view.x) / view.k, (center[1] - view.y) / view.k];
    view.k = target_zoom;
    l = [translate0[0] * view.k + view.x, translate0[1] * view.k + view.y];

    view.x += center[0] - l[0];
    view.y += center[1] - l[1];

    interpolateZoom([view.x, view.y], view.k);
}

$(window).resize(function() {
  var w = $("#map").width();
  svg.attr("width", w);
  svg.attr("height", w * height / width);
});
//d3.selectAll('.button').on('click', zoomClick);

function zoom(xyz) {
  
  g.transition()
    .duration(1000)
    .attr("transform", "translate(" + projection.translate() + ")scale(" + xyz[2] + ")translate(-" + xyz[0] + ",-" + xyz[1] + ")")
    .selectAll(["#countries", "#states"])
    .style("stroke-width", 1.0 / xyz[2] + "px");
    //.selectAll(["#countries", "#states", "#cities"])
    //.style("stroke-width", 1.0 / xyz[2] + "px")
    //.selectAll(".city")
    //.attr("d", path.pointRadius(20.0 / xyz[2]));
}

function get_xyz(d) {
	//console.log("get_xyz");

  var centroid = path.centroid(d);
  x = centroid[0];
  y = centroid[1];
	
  var bounds = path.bounds(d);
  var w_scale = (bounds[1][0] - bounds[0][0]) / width;
  var h_scale = (bounds[1][1] - bounds[0][1]) / height;
  var z = .6 / Math.max(w_scale, h_scale);
  //var x = (bounds[1][0] + bounds[0][0]) / 2;
  //var y = (bounds[1][1] + bounds[0][1]) / 2 + (height / z / 6);
  return [x, y, z];
  
}

function country_clicked(d) {
  g.selectAll(["#states"]).remove(); //, "#cities"
  state = null;
	
  if (country) {
    g.selectAll("#" + country.properties["ID"]).style('display', null);
  }

  if (d && country !== d) {
    var xyz = get_xyz(d);
    country = d;

	
    // if (d.properties["ID"]  == 'TGO' || d.properties["ID"]  == 'CMR' || d.properties["ID"]  == 'BEN' || 
			// d.properties["ID"]  == 'BFA' || d.properties["ID"]  == 'GIN' || d.properties["ID"]  == 'NER') {
      // d3.json(baseUrl + "/dashboard/js/" + d.properties["ID"] + "_topo.json", function(error, us) {
        // g.append("g")
          // .attr("id", "states")
          // .selectAll("path")
          // .data(topojson.object(us, us.objects.layer1).geometries)
          // .enter()
          // .append("path")
          // .attr("id", function(d) { return d.properties["ADM2_NAME"]; })
          // .attr("class", "states")
          // .attr("d", path)
          // .on("click", state_clicked);
// 
        // zoom(xyz);
        // g.selectAll("#" + d.properties["ADM2_NAME"]).style('display', 'none');
      // });      
    // } else {
      zoom(xyz);
    // }
  } else {
    var xyz = [width / 2, height / 2, 1];
    country = null;
    zoom(xyz);
  }
}

function state_clicked(d) {
  //g.selectAll("#cities").remove();
/*
  if (d && state !== d) {
    var xyz = get_xyz(d);
    state = d;

    country_code = state.id.substring(0, 3).toLowerCase();
    state_name = state.properties.name;

    d3.json("/json/cities_" + country_code + ".topo.json", function(error, us) {
      g.append("g")
        .attr("id", "cities")
        .selectAll("path")
        .data(topojson.feature(us, us.objects.cities).features.filter(function(d) { return state_name == d.properties.state; }))
        .enter()
        .append("path")
        .attr("id", function(d) { return d.properties.name; })
        .attr("class", "city")
        .attr("d", path.pointRadius(20 / xyz[2]));

      zoom(xyz);
    });      
  } else {
    state = null;
    country_clicked(country);
  }
*/

    state = null;
    country_clicked(country);
  
}