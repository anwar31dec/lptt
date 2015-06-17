urls = {
		//bd : "js/world.json",
		//bd : "js/bd.json",		
		bd : baseUrl + "json/togo_topo.json",									
		voteInfo : baseUrl + "dashboard/js/map_bangla_source.json",
		winnerParty : baseUrl + "dashboard/js/winner_party_source.json",
		top2party : baseUrl + "dashboard/js/top2party_source.json"		
};	
var active, centered, consStyle, bPollStarted = true;

// var x2 = 92.68311627606212;
// var x1 = 88.01071856913639;
// 
// var y2 = 26.63388413770829;
// var y1 = 20.589750373436786;
// 
// var x2 = 1.810287;
// var x1 = -0.079361;
// 
// var y2 = 11.115171;
// var y1 = 6.229397;

var x2 = 1.8066899999994348;
var x1 = -0.14732;

var y2 = 11.138980000003357;
var y1 = 6.104420000003302;


var centX = x1 + (x2 - x1) / 2;
var centY = y1 + (y2 - y1) / 2;

var width = parseInt(d3.select('#map').style('width'))
	//, width = width - margin.left - margin.right
	, mapRatio = 1.6, height = (width * mapRatio);
	
var scrollY	= height+20;
//d3.select("#cparams-panel").style('height', height);
//$("#cparams-panel").css("height", height+115);

// var projection = d3.geo.mercator().scale(5200).center([centX-100, centY+10]);
var projection = d3.geo.mercator().scale(7000).center([centX+2.3, centY+.6]);
//var projection = d3.geo.mercator().scale(150);
var path = window.path = d3.geo.path().projection(projection);

var svg = d3.select("#map").append("svg").attr("width", width).attr("height", height);

svg.append("rect").attr("width", width).attr("height", height).on("click", reset);

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
	
	winnerParty = window.winnerParty = _(winnerParty).chain().map(function(d) {
			return [d.ConstId, d];
		}).object().value();
		
	window.top2party = top2party;	
	
	var top2party = d3.nest().key(function(d) {
		return d.ConstId;
	}).entries(top2party);
		
	top2party = window.top2party = _(top2party).chain().map(function(d) {
			return [d.key, d];
		}).object().value();
		
	 zoom = d3.behavior.zoom()
	.translate([0, 0])
	.scale(1)
	.scaleExtent([1, 8])
	.on("zoom", zoomed);

	var color = d3.scale.linear().domain([1, 20]).range(['#669933', '#660033']);
var country = '';
	var states = g.selectAll("path").data(topojson.object(bd, bd.objects.countries).geometries)
	.enter()
	.append("path")
	.attr("d", path)
	.attr("class", "feature")
	.style('fill',function(d) { //country = country+'{"id":"'+ d.id +'", "name":"'+d.properties.name+'", "bgcolor":"#cccccc"'+ ', "num_of_staff":"' + Math.floor((Math.random() * 100)) + '", "num_of_indicator":"' +Math.floor((Math.random() * 100))+ '", "area":"TB, Malaria, Aids' +'"},';
							if(bPollStarted){
							var winColor = voteInfo[d.id]; 
								if(winColor !== undefined) 
								return winColor.bgcolor;
								else return '#CCCCCC';
								} else return '#CCCCCC'})
	//.on("click", constituencyDetails)
	.call(zoom);
	//.call(clickZoomMap);		
	
	//states.on('mouseover', tooltipShow)
	//.on('mouseout', tooltipHide);
	//console.log(country);
	
	// 9.374644, 0.942367 
	
	g.selectAll("circle")
           .data(topojson.object(bd, bd.objects.countries).geometries)
           .enter()
           .append("circle")
           .attr("cx", function(d) {
           	console.log(d);
                   return projection([d.properties.long, d.properties.lat])[0];
           })
           .attr("cy", function(d) {
                   return projection([d.properties.long, d.properties.lat])[1];
           })
           .attr("r", function(d) {
                   return d.properties.radious*2;
           })
           .style("fill", function(d) {
                   return d.properties.color;
           });
		
	
};

var iSo3 = '';

function constituencyDetails(d){
	iSo3 = d.id;
			
	$("#cparams-header strong").text(d.properties.name);
	
	oTableCProfileParams.fnDraw();
}

function clickZoomMap() {"use strict"
	var x, y, k
    
    var constituencies = topojson.object(bd, bd.objects.countries).geometries;
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
	
	datum = voteInfo[d.id];
	//console.log(d);
	 // var datum = {
		// "constCand": top2party[d.properties.CONSTID],
		// "voteInfo": voteInfo[d.properties.CONSTID]
		// };
		
	
			    
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

//d3.selectAll('.button').on('click', zoomClick);

