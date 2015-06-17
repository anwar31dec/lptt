Ext.state.Provider = Ext.extend(Ext.util.Observable, {
	constructor : function() {
		this.addEvents("statechange");
		this.state = {};
		Ext.state.Provider.superclass.constructor.call(this);
	},
	get : function(b, a) {
		return typeof this.state[b] == "undefined" ? a : this.state[b];
	},
	clear : function(a) {
		delete this.state[a];
		this.fireEvent("statechange", this, a, null);
	},
	set : function(a, b) {
		this.state[a] = b;
		this.fireEvent("statechange", this, a, b);
	},
	decodeValue : function(b) {
		var e = /^(a|n|d|b|s|o|e)\:(.*)$/, h = e.exec(unescape(b)), d, c, a, g;
		if (!h || !h[1]) {
			return
		}
		c = h[1];
		a = h[2];
		switch(c) {
		case"e":
			return null;
		case"n":
			return parseFloat(a);
		case"d":
			return new Date(Date.parse(a));
		case"b":
			return (a == "1");
		case"a":
			d = [];
			if (a != "") {
				Ext.each(a.split("^"), function(i) {
					d.push(this.decodeValue(i));
				}, this);
			}
			return d;
		case"o":
			d = {};
			if (a != "") {
				Ext.each(a.split("^"), function(i) {
					g = i.split("=");
					d[g[0]] = this.decodeValue(g[1]);
				}, this);
			}
			return d;
		default:
			return a
		}
	},
	encodeValue : function(c) {
		var b, g = "", e = 0, a, d;
		if (c == null) {
			return "e:1";
		} else {
			if ( typeof c == "number") {
				b = "n:" + c;
			} else {
				if ( typeof c == "boolean") {
					b = "b:" + ( c ? "1" : "0");
				} else {
					if (Ext.isDate(c)) {
						b = "d:" + c.toGMTString();
					} else {
						if (Ext.isArray(c)) {
							for ( a = c.length; e < a; e++) {
								g += this.encodeValue(c[e]);
								if (e != a - 1) {
									g += "^";
								}
							}
							b = "a:" + g;
						} else {
							if ( typeof c == "object") {
								for (d in c) {
									if ( typeof c[d] != "function" && c[d] !== undefined) {
										g += d + "=" + this.encodeValue(c[d]) + "^";
									}
								}
								b = "o:" + g.substring(0, g.length - 1);
							} else {
								b = "s:" + c;
							}
						}
					}
				}
			}
		}
		return escape(b);
	}
});
Ext.state.Manager = function() {
	var a = new Ext.state.Provider();
	return {
		setProvider : function(b) {
			a = b;
		},
		get : function(c, b) {
			return a.get(c, b);
		},
		set : function(b, c) {
			a.set(b, c);
		},
		clear : function(b) {
			a.clear(b);
		},
		getProvider : function() {
			return a;
		}
	};
}();
Ext.state.CookieProvider = Ext.extend(Ext.state.Provider, {
	constructor : function(a) {
		Ext.state.CookieProvider.superclass.constructor.call(this);
		this.path = "/";
		this.expires = new Date(new Date().getTime() + (1000 * 60 * 60 * 24 * 7));
		this.domain = null;
		this.secure = false;
		Ext.apply(this, a);
		this.state = this.readCookies();
	},
	set : function(a, b) {
		if ( typeof b == "undefined" || b === null) {
			this.clear(a);
			return;
		}
		this.setCookie(a, b);
		Ext.state.CookieProvider.superclass.set.call(this, a, b);
	},
	clear : function(a) {
		this.clearCookie(a);
		Ext.state.CookieProvider.superclass.clear.call(this, a);
	},
	readCookies : function() {
		var d = {}, h = document.cookie + ";", b = /\s?(.*?)=(.*?);/g, g, a, e;
		while (( g = b.exec(h)) != null) {
			a = g[1];
			e = g[2];
			if (a && a.substring(0, 3) == "ys-") {
				d[a.substr(3)] = this.decodeValue(e);
			}
		}
		return d
	},
	setCookie : function(a, b) {
		document.cookie = "ys-" + a + "=" + this.encodeValue(b) + ((this.expires == null) ? "" : ("; expires=" + this.expires.toGMTString())) + ((this.path == null) ? "" : ("; path=" + this.path)) + ((this.domain == null) ? "" : ("; domain=" + this.domain)) + ((this.secure == true) ? "; secure" : "");
	},
	clearCookie : function(a) {
		document.cookie = "ys-" + a + "=null; expires=Thu, 01-Jan-70 00:00:01 GMT" + ((this.path == null) ? "" : ("; path=" + this.path)) + ((this.domain == null) ? "" : ("; domain=" + this.domain)) + ((this.secure == true) ? "; secure" : "");
	}
});