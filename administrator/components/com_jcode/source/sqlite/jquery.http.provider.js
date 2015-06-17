//( function($) {

function HttpProvider(config) {
	this.settings = $.extend({
		// defaults
		delay : 750, // buffer changes for 750 ms
		dirty : false,
		started : false,
		autoStart : true,
		autoRead : true,
		user : 'user',
		id : 1,
		session : 'session',
		logFailure : false,
		logSuccess : false,
		queue : [],
		url : '.',
		readUrl : undefined,
		saveUrl : undefined,
		method : 'post',
		saveBaseParams : {},
		readBaseParams : {
		},
		paramNames : {
			id : 'id',
			name : 'name',
			value : 'value',
			user : 'user',
			session : 'session',
			data : 'data'
		}
	}, config);

	this.submitState = function(nv) {
		// if(!this.dirty) {
		// this.dt.delay(this.delay);
		// return;
		// }
		// this.dt.cancel();

		var o = {
			url : this.settings.saveUrl || this.settings.url,
			method : this.settings.method,
			scope : this,
			queue : 0,
			params : {}
		};

		var params = $.extend({}, this.settings.saveBaseParams);
		params[this.settings.paramNames.id] = this.settings.id;
		params[this.settings.paramNames.user] = this.settings.user;
		params[this.settings.paramNames.session] = this.settings.session;
		params[this.settings.paramNames.data] = nv;
		//Ext.encode(o.queue);

		$.extend(o.params, params);
		
		
		// be optimistic
		//this.dirty = false;
		//console.log(o);
		//Ext.Ajax.request(o);
		this.ajaxRequest(o);
	};
	// eo function submitState

	this.ajaxRequest = function(o) {	
		//console.log(o.params.data);	
		$.ajax({
			type : o.method,
			dataType : "json",
			url : o.url,
			data : {
				"cmd" : "saveState",
				"id" : o.params.id,
				"user" : o.params.user,
				"session" : o.params.id.session,
				"data": o.params.data
			},
			success : function(response) {
				//alert('ajaxRequest');
			}
		});
	};
}

//( function($) {

//}(jQuery));

// HttpProvider.prototype.sayHello = function() {
// alert("Hello, I'm " + this.settings.method);
// };

// $.fn.greenify = function(options) {
// alert('test');
// // This is the easiest way to have default options.
// var settings = $.extend({
// // These are the defaults.
// color : "#556b2f",
// backgroundColor : "#888888"
// }, options);
// // Greenify the collection based on the settings variable.
// return this.css({
// color : settings.color,
// backgroundColor : settings.backgroundColor
// });
// };
//
// var settings;

// HttpProvider = function(config) {
// var settings = $.extend({
// // defaults
// delay : 750, // buffer changes for 750 ms
// dirty : false,
// started : false,
// autoStart : true,
// autoRead : true,
// user : 'user',
// id : 1,
// session : 'session',
// logFailure : false,
// logSuccess : false,
// queue : [],
// url : '.',
// readUrl : undefined,
// saveUrl : undefined,
// method : 'post',
// saveBaseParams : {},
// readBaseParams : {
// },
// paramNames : {
// id : 'id',
// name : 'name',
// value : 'value',
// user : 'user',
// session : 'session',
// data : 'data'
// }
// }, config);
//
// return settings.delay;
// };
//}(jQuery));
