/*
globals beginning:
	__ = classes
	_  = functions
	$  = vars
*/
$loc=(null!=(window.location.hostname.match(/^.*\.loc$/i)));
//----------[EXTENTIONS]begin----------
    String.format = function () {
        var s = arguments[0];
        for (var i = 0; i < arguments.length - 1; i++) {
            var reg = new RegExp("\\{" + i + "\\}", "gm");
            s = s.replace(reg, arguments[i + 1]);
        }
        return s;
    };
    String.IsNullOrEmpty = function () {
        var text = $.trim(arguments[0]);
        return text == null || text == '';
    };
    String.prototype.trimEnd = function () {
        return this.replace(/\s+$/, "");
    };
    String.prototype.trimStart = function () {
        return this.replace(/^\s+/, "");
    };
    String.prototype.endsWith = function (a) {
        return this.substr(this.length - a.length) === a
    };
    String.prototype.startsWith = function (a) {
        return this.substr(0, a.length) === a
    };
//----------[EXTENTIONS]end----------
//----------[GLOBAL_CLASSES]begin----------
function __mask(id,p_id,mess){
	var maxzind=0;
	$("div").each(function(i) {
		var zind=parseInt(this.style['zIndex']);
		if(maxzind<zind)maxzind=zind;
	});
	var parent=$((typeof(p_id)=='string'?'#'+p_id:'body'));
	var mess=(typeof(mess)=='string'?mess:'Loading..');
	parent.append('<div '+(typeof(id)=='string'?'id="'+id+'"':'')+' class="x-mask" style="width: '+parent.width()+'px; height: '+parent.height()+'px;display:none;z-index:'+(maxzind+1)+';"><div class="x-mask-msg x-mask-msg-default x-layer" style="left:'+((parent.width()/2)-100)+'px;top:'+((parent.height()/2)-20)+'px"><div style="position: relative;"><b>'+mess+'</b></div></div></div>');
	var me=parent.children('div.x-mask').last();
	me._show=me.show;
	me.show=function(){
		this.width(this.parent().width());
		this.height(this.parent().height());
		this.children('div').css('left',((parent.width()/2)-100)+'px');
		this.children('div').css('top',((parent.height()/2)-20)+'px');
		this._show();
	}
	me.id=id;
	me.mess=mess;
	me.parent_id=(typeof(p_id)=='string'?'#'+p_id:'body');
	return me;
}
function __dict () {
/*
var ddd=new __dict;

ddd.set('ff','ss');
ddd.set({'tt':'yyy','uu':77});

ddd.get('ff');
ddd.get();

ddd.getAt(0);

ddd.keys;
ddd.values;

ddd.del('ff');

ddd.len();
*/
    this.keys = [],
	this.values = [],
	this.findKey=function (key,match_case) {
		var mc=(typeof(match_case)=='boolean'?match_case:true);
		return this.keys.filter(
			(
				key.constructor == Function?key:
				function(v,i,a){
					if(!mc)v=v.toLowerCase();
					if(key.constructor == RegExp)
						return (key.exec(v)!=null);
					if(key.constructor == String)
						return (v.substr(0,key.length)==key);
				}
			)
		)
	},
	this.findValue=function (value,match_case) {
		var mc=(typeof(match_case)=='boolean'&& match_case),
		r=[],ks=this.keys;
		this.values.filter(
			(
				value.constructor == Function?value:
				function(v,i,a){
					if(!mc)v=v.toLowerCase();
					var b=false;
					if(value.constructor == RegExp)b=(value.exec(v)!=null);
					if(value.constructor == String)b=(v.substr(0, value.length)==value);
					if(b)r.push(ks[i]);
					return b;
				}
			)
		)
		return r;
	},
	this.get=function (key) {
		var r={};
		if(typeof(key)!='undefined')
			r=this.values[this.keys.indexOf(key)];
		else
			for(var i in this.keys)
				r[this.keys[i]]=this.values[i];
		return r;
	},
	this.getAt=function (ind,asobj) {
		var r={};
		if(ind<0&& this.keys.length-Math.abs(ind)>-1)ind=this.keys.length-Math.abs(ind);
		if(typeof(ind)=='number',typeof(asobj)=='number'){
			for(var i=ind;i<ind+asobj;i++)
				r[this.keys[i]]=this.values[i];
		}else
		if(typeof(asobj)!='undefined')
			r[this.keys[ind]]=this.values[ind];
		else
			r=this.values[ind];            
		return r;
	},
	this.set=function (key, value) {
		if(typeof(key)=='object'&&typeof(value)=='undefined'){
			for(var i in key){
				var ind = this.keys.indexOf(i);
				if (ind === -1) {
					ind = this.keys.length;
				}
				this.keys[ind]=i;
				this.values[ind]=key[i];
			}
		}else{
			var i = this.keys.indexOf(key);
			if (i === -1) {
				i = this.keys.length;
			}
			this.keys[i] = key;
			this.values[i] = value;
		}
		return this.keys.length;
	},
	this.del=function (key) {
		var i = this.keys.indexOf(key);
		this.keys.splice(i, 1);
		this.values.splice(i, 1);
	},
	this.len=function(){
		return this.keys.length;
	};
}
function __cookie(){
	this.keys=[];this.values=[];
	var g=document.cookie.split(';');
	for(var i in g){
		g[i]=g[i].trim();
		g[i]=g[i].split('=');
		if(typeof(g[i][1])!='undefined'){
			this.keys[i]=g[i][0];
			this.values[i]=decodeURIComponent(g[i][1]);
		}else{ 
			this.keys[i]=g[i][0];
			this.values[i]='';
		}
	}
}
__cookie.prototype=new __dict();
__cookie.prototype.set=function (name, value, props) {
	props = props || {}
	var exp = props.expires
	if (typeof exp == "number" && exp) {
		var d = new Date(new Date().toUTCString())
		d.setTime(d.getTime() + exp*1000)
		exp = props.expires = d
	}
	if(exp && exp.toUTCString) { props.expires = exp.toUTCString() }

	value = encodeURIComponent(value)
	var updatedCookie = name + "=" + value
	for(var propName in props){
		updatedCookie += "; " + propName
		var propValue = props[propName]
		if(propValue !== true){ updatedCookie += "=" + propValue }
	}
	document.cookie = updatedCookie
	var i = this.keys.indexOf(name);
	if (i === -1) {
		i = this.keys.length;
	}
	this.keys[i] = name;
	this.values[i] = value;		
	return this.keys.length;
}
__cookie.prototype.del=function (name) {
	this.set(name, null, { expires: -1 });
	var i = this.keys.indexOf(name);
	this.keys.splice(i, 1);
	this.values.splice(i, 1);
}
function __timers () {};
__timers.prototype=new __dict();
__timers.prototype.set=function (a,b,c,d) {
		var i = -1,name=a,id=-1,key,execute,interval,loop;
		//_log(a,b,c,d)
		if(typeof(a)=='function'){
			name=false;
			loop=interval;
			interval=execute;
			execute=key;
		}else
		if(typeof(b)=='function'){
			key=a;execute=b;interval=c;loop=d;
			i = this.keys.indexOf(key);
		}else return console.log('what i have to do? Function is not set');
		if (i === -1) {
			i = this.keys.length;
		}else{
			this.values[i].stop();
		}
		if(typeof(interval)!='number')var interval=100;
		//id=setTimeout(execute,interval);
		this.keys[i] = (name===false?id:key);
		this.values[i] = {
			'id':id,			
			'loop':(typeof(loop)=="boolean"&&loop===true),
			'action':execute,
			'interval':interval,
			'enabled':function(b){
				if(typeof(b)!="undefined"){
					if(b===1||b===0)b=(b===1);
					if(typeof(b)=="boolean"){
						if(b)this.start();
						else this.stop();
						return this.id;
					}
				}else return (this.id!=-1);
			},
			'count':0,
			'max_count':-1,
			'stop':function(){
				if(this.id!=-1){
					clearTimeout(this.id);
					this.id=-1;
					//if(typeof(execute)=='function')execute();
					return this.id;
				}
			},
			'start':function(run){
				var me=this;
				var run=(typeof(run)!='undefined');
				if(me.id!=-1)
					clearTimeout(me.id);
				if(run){
					me.count++;
					me.action();
					if(!me.loop||(me.max_count>-1&&me.count>=me.max_count)){
						this.id=-1;
						return;
					}
				}
				me.id=setTimeout(//setInterval
					me.start.bind(me,run),
					me.interval
				);
				return me.id;
			},
			'clear':function(){
				if(this.id!=-1){
					clearTimeout(this.id);
					this.id=-1;
					this.count=0;
					//if(typeof(execute)=='function')execute();
					return this.id;
				}
			}
		}
		this.values[i].start();
		return this.keys.length;
	
	}
__timers.prototype.del=function (key) {
		var i = this.keys.indexOf(key);
		this.values[i].stop();
		this.keys.splice(i, 1);
		this.values.splice(i, 1);
	}
//----------[GLOBAL_CLASSES]end----------
//----------[GLOBAL_FUNCTIONS]begin----------
_count=function(o){//_count('ffkr')||_count({f:'rr'})||_count([7,'rr'])
    var c=0;
    for(var i in o)
        c++;
    return c;
}
var _isset=function(){
	for(var i in arguments)
	  if (typeof(arguments[i])=='undefined')return false;
	return true
}
function _empty () {
  // Checks if the argument variable is empty
  // undefined, null, false, number 0, empty string,
  // string "0", objects without properties and empty arrays
  // are considered empty
  //
  // From: http://phpjs.org/functions
  // +   original by: Philippe Baumann
  // +      input by: Onno Marsman
  // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +      input by: LH
  // +   improved by: Onno Marsman
  // +   improved by: Francesco
  // +   improved by: Marc Jansen
  // +      input by: Stoyan Kyosev (http://www.svest.org/)
  // +   improved by: Rafal Kukawski
  // *     example 1: empty(null);
  // *     returns 1: true
  // *     example 2: empty(undefined);
  // *     returns 2: true
  // *     example 3: empty([]);
  // *     returns 3: true
  // *     example 4: empty({});
  // *     returns 4: true
  // *     example 5: empty({'aFunc' : function () { alert('humpty'); } });
  // *     returns 5: false
	var undef, key, i, len,mixed_var;
	var emptyValues = [undef, null, false, 0, "", "0"];
	for(var k in arguments){
		mixed_var=arguments[k];
		for (i = 0, len = emptyValues.length; i < len; i++) {
			if (mixed_var === emptyValues[i]) {
				return true;
			}
		}

		if (typeof mixed_var === "object") {
			for (key in mixed_var) {
				// TODO: should we check for own properties only?
				//if (mixed_var.hasOwnProperty(key)) {
				return false;
				//}
			}
			return true;
		}

		
	}
	return false;
}

var _isdef=function(){
	console.log(arguments);
	console.log(arguments.callee.caller);
	var res=false;
	for(var i in arguments)
	if(typeof(arguments[i])=='string'){// && arguments[i].match(/^\?=([A-z_0-9\$]+\.?)+$/)!==null){
		var keys=arguments[i].split('.'),ostr='';//.substr(2)
		for(var i in keys){
			if(ostr!='')ostr+='.';
			ostr+=keys[i]
			if(eval('typeof('+ostr+')')=='undefined' || eval(ostr)==null)return false;
		}
		res=true
	}
	return res;
}
var _repeat=function(a,b){//what,count
	var o=a;
	if(_isset(a,b)&&b>0)for(var i=0;i<b;i++)o+=a;
	return o;
}
var _log=function(){
	console.log('-- from '+(new Error).stack.split("\n")[1])
	for(var i in arguments){
		var t=typeof(arguments[i]),l='';
		l='('+t+((t!='undefined'&&t!='number')?'['+_count(arguments[i])+']':'')+')';
		l+=_repeat(' ',13-l.length);
		console.log('    '+l+'    ',arguments[i]);
	}
	//var dbug=(new Error).stack.split("\n")[1].split('/')
	//console.log('file&line'+dbug[dbug.length-1]+')');
	//console.log('-- end');
}
var _setGET=function(url){
	$_GET={};
	var s=(typeof(url)=='string'?url:location.search.substr(1));
	if(s.length>0){
		var g=s.split('&');
		for(var i in g){
			g[i]=g[i].split('=');
			if(typeof(g[i][1])!='undefined')
				$_GET[g[i][0]]=g[i][1];
			else 
				$_GET[g[i][0]]='';
		}
		//delete(g);
	}
	//delete(s);
}
var _setHASH=function(url){
	if(typeof(url)=='object'){
		var a=[]
		for(var i in url){
			a.push(i+'='+url[i])
		}
		location.hash=a.join('&');
		_setHASH();
		return true;
	}
	$_HASH={};
	var s=(typeof(url)=='string'?url:location.hash.substr(1));
	if(s.length>0){
		var g=s.split('&');
		for(var i in g){
			g[i]=g[i].split('=');
			if(typeof(g[i][1])!='undefined')
				$_HASH[g[i][0]]=g[i][1];
			else 
				$_HASH[g[i][0]]='';
		}
		//delete(g);
	}
	//delete(s);
}
function _copy(from, to){
    //if (from == null || typeof from != "object") return from;
    if (from.constructor != Object && from.constructor != Array) return from;
    if (from.constructor == Date || from.constructor == RegExp || from.constructor == Function ||
        from.constructor == String || from.constructor == Number || from.constructor == Boolean)
        return new from.constructor(from);
    to = to || new from.constructor();
    for (var name in from)
        to[name] = typeof to[name] == "undefined" ? _copy(from[name], null) : to[name];
    return to;
}
//----------[GLOBAL_FUNCTIONS]end----------
//----------[GLOBAL_VARS]begin----------
var $_GET={},$_HASH={};
_setGET();
_setHASH();
$cookie=new __cookie();
var $base64 = {
	// private property
	_keyStr : "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",

	// public method for encoding
	encode : function (input) {
		var output = "";
		var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
		var i = 0;

		input = $base64._utf8_encode(input);

		while (i < input.length) {

			chr1 = input.charCodeAt(i++);
			chr2 = input.charCodeAt(i++);
			chr3 = input.charCodeAt(i++);

			enc1 = chr1 >> 2;
			enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
			enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
			enc4 = chr3 & 63;

			if (isNaN(chr2)) {
				enc3 = enc4 = 64;
			} else if (isNaN(chr3)) {
				enc4 = 64;
			}

			output = output +
			$base64._keyStr.charAt(enc1) + $base64._keyStr.charAt(enc2) +
			$base64._keyStr.charAt(enc3) + $base64._keyStr.charAt(enc4);

		}

		return output;
	},

	// public method for decoding
	decode : function (input) {
		var output = "";
		var chr1, chr2, chr3;
		var enc1, enc2, enc3, enc4;
		var i = 0;

		input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

		while (i < input.length) {

			enc1 = $base64._keyStr.indexOf(input.charAt(i++));
			enc2 = $base64._keyStr.indexOf(input.charAt(i++));
			enc3 = $base64._keyStr.indexOf(input.charAt(i++));
			enc4 = $base64._keyStr.indexOf(input.charAt(i++));

			chr1 = (enc1 << 2) | (enc2 >> 4);
			chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
			chr3 = ((enc3 & 3) << 6) | enc4;

			output = output + String.fromCharCode(chr1);

			if (enc3 != 64) {
				output = output + String.fromCharCode(chr2);
			}
			if (enc4 != 64) {
				output = output + String.fromCharCode(chr3);
			}

		}

		output = $base64._utf8_decode(output);

		return output;

	},

	// private method for UTF-8 encoding
	_utf8_encode : function (string) {
		string = string.replace(/\r\n/g,"\n");
		var utftext = "";

		for (var n = 0; n < string.length; n++) {

			var c = string.charCodeAt(n);

			if (c < 128) {
				utftext += String.fromCharCode(c);
			}
			else if((c > 127) && (c < 2048)) {
				utftext += String.fromCharCode((c >> 6) | 192);
				utftext += String.fromCharCode((c & 63) | 128);
			}
			else {
				utftext += String.fromCharCode((c >> 12) | 224);
				utftext += String.fromCharCode(((c >> 6) & 63) | 128);
				utftext += String.fromCharCode((c & 63) | 128);
			}

		}

		return utftext;
	},

	// private method for UTF-8 decoding
	_utf8_decode : function (utftext) {
		var string = "";
		var i = 0;
		var c = c1 = c2 = 0;

		while ( i < utftext.length ) {

			c = utftext.charCodeAt(i);

			if (c < 128) {
				string += String.fromCharCode(c);
				i++;
			}
			else if((c > 191) && (c < 224)) {
				c2 = utftext.charCodeAt(i+1);
				string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
				i += 2;
			}
			else {
				c2 = utftext.charCodeAt(i+1);
				c3 = utftext.charCodeAt(i+2);
				string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
				i += 3;
			}

		}
		return string;
	}
}
//----------[GLOBAL_VARS]end----------