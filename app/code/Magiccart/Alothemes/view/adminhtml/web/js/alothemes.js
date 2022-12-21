define([
	'jquery',
	], function($){
		jQuery(document).ready(function($){
			/*
			  mColorPicker
			  Version: 1.0 r39
			  
			  Copyright (c) 2010 Meta100 LLC.
			  http://www.meta100.com/
			  
			  Licensed under the MIT license 
			  http://www.opensource.org/licenses/mit-license.php 
			*/
			// var baseUrl = require.toUrl(''); // requirejs.s.contexts._.config.baseUrl;
			
			(function($){var b,f,h,l,j=$(document),i=$("<div>"),k=$("<div>"),n=$("<div>"),o=$("<input>"),p=/^rgb[a]?\((\d+),\s*(\d+),\s*(\d+)(,\s*(\d+\.\d+)*)?\)/,q=/([a-f0-9])([a-f0-9])([a-f0-9])/,r=/#[a-f0-9]{3}/,s=/#[a-f0-9]{6}/;$.fn.mColorPicker=function(a){var c=$.fn.mColorPicker.getCookie("swatches");b=$.extend($.fn.mColorPicker.defaults,a);$.fn.mColorPicker.defaults.swatches.concat(b.swatches).slice(-10);f.enhancedSwatches&&c&&(b.swatches=c.split("||").concat(b.swatches).slice(0,10)||b.swatches);$("div#mColorPicker").length||$.fn.mColorPicker.drawPicker();$("#css_disabled_color_picker").length||$("head").prepend('<meta data-remove-me="true"/><style id="css_disabled_color_picker" type="text/css">.mColorPicker[disabled] + span, .mColorPicker[disabled="disabled"] + span, .mColorPicker[disabled="true"] + span {filter:alpha(opacity=50);-moz-opacity:0.5;-webkit-opacity:0.5;-khtml-opacity: 0.5;opacity: 0.5;cursor:default;}</style>');$("meta[data-remove-me=true]").remove();this.each($.fn.mColorPicker.drawPickerTriggers);return this};$.fn.mColorPicker.init={replace:"[type=color]",index:0,enhancedSwatches:!0,allowTransparency:!0,slogan:"Meta100 - Designing Fun",showLogo:!0};$.fn.mColorPicker.defaults={currentId:!1,currentInput:!1,currentColor:!1,changeColor:!1,color:!1,imageFolder:"images/",swatches:"#ffffff,#ffff00,#00ff00,#00ffff,#0000ff,#ff00ff,#ff0000,#4c2b11,#3b3b3b,#000000".split(",")};$.fn.mColorPicker.start=function(){$('input[data-mcolorpicker!="true"]').filter(function(){return"[type=color]"==f.replace?"color"==this.getAttribute("type"):$(this).is(f.replace)}).mColorPicker()};$.fn.mColorPicker.events=function(){$("#mColorPickerBg").on("click",$.fn.mColorPicker.closePicker);$(".mColorPicker").on("keyup",function(){try{$(this).css({"background-color":$(this).val()}).css({color:$.fn.mColorPicker.textColor($(this).css("background-color"))}).trigger("change")}catch(a){}});$(".mColorPickerTrigger").on("click",$.fn.mColorPicker.colorShow);$(".mColor, .mPastColor").on("mousemove",function(a){if(!b.changeColor)return!1;var c=$(this),g=c.offset(),d=b.currentInput,d=d.attr("data-hex")||d.attr("hex");b.color=c.css("background-color");c.hasClass("mPastColor")?b.color=$.fn.mColorPicker.setColor(b.color,d):c.hasClass("mColorTransparent")?b.color="transparent":c.hasClass("mPastColor")||(b.color=$.fn.mColorPicker.whichColor(a.pageX-g.left,a.pageY-g.top,d));b.currentInput.mSetInputColor(b.color)}).on("click",$.fn.mColorPicker.colorPicked);$("#mColorPickerInput").on("keyup",function(a){try{b.color=$(this).val(),b.currentInput.mSetInputColor(b.color),13==a.which&&$.fn.mColorPicker.colorPicked()}catch(c){}}).on("blur",function(){b.currentInput.mSetInputColor(b.color)});$("#mColorPickerWrapper").on("mouseleave",function(){if(!b.changeColor)return!1;var a=b.currentInput;b.currentInput.mSetInputColor($.fn.mColorPicker.setColor(b.currentColor,a.attr("data-hex")||a.attr("hex")))})};$.fn.mColorPicker.drawPickerTriggers=function(){var a=$(this),c=a.attr("id")||"color_"+f.index++,g="hidden"==a.attr("text")||"hidden"==a.attr("data-text")?!0:!1,d=$.fn.mColorPicker.setColor(a.val(),a.attr("data-hex")||a.attr("hex")),e=a.width(),h=a.height(),i=a.css("float"),j=$("<span>"),m=$("<span>"),k="";j.attr({id:"color_work_area","class":"mColorPickerInput"}).appendTo(l);m.attr({id:"mcp_"+c,"class":"mColorPickerTrigger"}).css({display:"inline-block",cursor:"pointer"}).insertAfter(a);$("<img>").attr({src:b.imageFolder+"color.png"}).css({border:0,margin:"0 0 0 3px","vertical-align":"text-bottom"}).appendTo(m);j.append(a);k=j.html().replace(/type=[^a-z ]*color[^a-z //>]*/gi,'type="'+(g?"hidden":"text")+'"');j.html("").remove();a=$(k).attr("id",c).addClass("mColorPicker").val(d).insertBefore(m);g&&m.css({border:"1px solid black","float":i,width:e,height:h}).addClass(a.attr("class")).html("&nbsp;");a.mSetInputColor(d);return a};$.fn.mColorPicker.drawPicker=function(){var a=$("<div>"),c=$("<a>"),g=$("<div>"),d=$("<div>");k.attr({id:"mColorPickerBg"}).css({display:"none",background:"black",opacity:0.01,position:"absolute",top:0,right:0,bottom:0,left:0}).appendTo(l);i.attr({id:"mColorPicker","data-mcolorpicker":!0}).css({position:"absolute",border:"1px solid #ccc",color:"#fff",width:"194px",height:"184px","font-size":"12px","font-family":"times",display:"none"}).appendTo(l);n.attr({id:"mColorPickerTest"}).css({display:"none"}).appendTo(l);d.attr({id:"mColorPickerWrapper"}).css({position:"relative",border:"solid 1px gray"}).appendTo(i);$("<div>").attr({id:"mColorPickerImg","class":"mColor"}).css({height:"136px",width:"192px",border:0,cursor:"crosshair","background-image":"url("+b.imageFolder+"picker.png)"}).appendTo(d);a.attr({id:"mColorPickerSwatches"}).css({"border-right":"1px solid #000"}).appendTo(d);$("<div>").addClass("mClear").css({clear:"both"}).appendTo(a);for(h=9;-1<h;h--)$("<div>").attr({id:"cell"+h,"class":"mPastColor"+(0<h?" mNoLeftBorder":"")}).css({"background-color":b.swatches[h].toLowerCase(),height:"18px",width:"18px",border:"1px solid #000","float":"left"}).html("&nbsp;").prependTo(a);g.attr({id:"mColorPickerFooter"}).css({"background-image":"url("+b.imageFolder+"grid.gif)",position:"relative",height:"26px"}).appendTo(d);o.attr({id:"mColorPickerInput",type:"text"}).css({border:"solid 1px gray","font-size":"10pt",margin:"3px",width:"80px"}).appendTo(g);f.allowTransparency&&$("<span>").attr({id:"mColorPickerTransparent","class":"mColor mColorTransparent"}).css({"font-size":"16px",color:"#000","padding-right":"30px","padding-top":"3px",cursor:"pointer",overflow:"hidden","float":"right"}).text("transparent").appendTo(g);f.showLogo&&c.attr({href:"http://meta100.com/",title:f.slogan,alt:f.slogan,target:"_blank"}).css({"float":"right"}).appendTo(g);$("<img>").attr({src:b.imageFolder+"meta100.png",title:f.slogan,alt:f.slogan}).css({border:0,"border-left":"1px solid #aaa",right:0,position:"absolute"}).appendTo(c);$(".mNoLeftBorder").css({"border-left":0})};$.fn.mColorPicker.closePicker=function(){k.hide();i.fadeOut()};$.fn.mColorPicker.colorShow=function(){var a=$(this),c=a.attr("id").replace("mcp_",""),g=a.offset(),d=$("#"+c),e=g.top+a.outerHeight(),f=g.left;if(d.attr("disabled"))return!1;b.currentColor=d.css("background-color");b.changeColor=!0;b.currentInput=d;b.currentId=c;e+i.height()>j.height()&&(e=g.top-i.height());f+i.width()>j.width()&&(f=g.left-i.width()+a.outerWidth());i.css({top:e+"px",left:f+"px"}).fadeIn("fast");k.show();b.color=$("#"+c).attr("data-text")?a.css("background-color"):d.css("background-color");b.color=$.fn.mColorPicker.setColor(b.color,d.attr("data-hex")||d.attr("hex"));o.val(b.color)};$.fn.mColorPicker.setInputColor=function(a,c){$("#"+a).mSetInputColor(c)};$.fn.mSetInputColor=function(a){var c=$(this),g={"background-color":a,"background-image":"transparent"==a?"url('"+b.imageFolder+"grid.gif')":"",color:$.fn.mColorPicker.textColor(a)};(c.attr("data-text")||c.attr("text"))&&c.next().css(g);c.val(a).css(g).trigger("change");o.val(a)};$.fn.mColorPicker.textColor=function(a){a=$.fn.mColorPicker.RGBtoHex(a);return"undefined"==typeof a||"transparent"==a?"black":400>parseInt(a.substr(1,2),16)+parseInt(a.substr(3,2),16)+parseInt(a.substr(5,2),16)?"white":"black"};$.fn.mColorPicker.setCookie=function(a,c,b){a=a+"="+escape(c);c=new Date;c.setDate(c.getDate()+b);a+="; expires="+c.toGMTString();document.cookie=a};$.fn.mColorPicker.getCookie=function(a){return(a=document.cookie.match("(^|;) ?"+a+"=([^;]*)(;|$)"))?unescape(a[2]):null};$.fn.mColorPicker.colorPicked=function(){b.changeColor=!1;$.fn.mColorPicker.closePicker();$.fn.mColorPicker.addToSwatch();b.currentInput.trigger("colorpicked")};$.fn.mColorPicker.addToSwatch=function(a){if(!f.enhancedSwatches)return!1;var c=[];h=0;"string"==typeof a&&(b.color=a);"transparent"!=b.color&&(c[0]=$.fn.mColorPicker.hexToRGB(b.color));$(".mPastColor").each(function(){var a=$(this);b.color=$.fn.mColorPicker.hexToRGB(a.css("background-color"));if(b.color!=c[0]&&c.length<10)c[c.length]=b.color;a.css("background-color",c[h++])});f.enhancedSwatches&&$.fn.mColorPicker.setCookie("swatches",c.join("||"),365)};$.fn.mColorPicker.whichColor=function(a,c,b){var d=[255,255,255];32>a?(d[1]=8*a,d[2]=0):64>a?(d[0]=256-8*(a-32),d[2]=0):96>a?(d[0]=0,d[2]=8*(a-64)):128>a?(d[0]=0,d[1]=256-8*(a-96)):160>a?(d[0]=8*(a-128),d[1]=0):(d[1]=0,d[2]=256-8*(a-160));for(var e=0;3>e;e++)64>c?d[e]+=(256-d[e])*(64-c)/64:128>=c?d[e]-=d[e]*(c-64)/64:128<c&&(d[e]=256-256*(a/192)),d[e]=Math.round(Math.min(d[e],255)),"true"==b&&(d[e]=$.fn.mColorPicker.decToHex(d[e]));return"true"==b?"#"+d.join(""):"rgb("+d.join(", ")+")"};$.fn.mColorPicker.setColor=function(a,c){return"true"==c?$.fn.mColorPicker.RGBtoHex(a):$.fn.mColorPicker.hexToRGB(a)};$.fn.mColorPicker.colorTest=function(a){n.css("background-color",a);return n.css("background-color")};$.fn.mColorPicker.decToHex=function(a){a=parseInt(a);return""+"0123456789ABCDEF".charAt(Math.floor(a/16))+(""+"0123456789ABCDEF".charAt(a-16*Math.floor(a/16)))};$.fn.mColorPicker.RGBtoHex=function(a){var c="#",b,a=a?a.toLowerCase():!1;if(!a)return"";if(s.test(a))return a.substr(0,7);if(r.test(a))return a.replace(q,"$1$1$2$2$3$3").substr(0,7);if(b=a.match(p)){for(a=1;4>a;a++)c+=$.fn.mColorPicker.decToHex(b[a]);return c}return $.fn.mColorPicker.colorTest(a)};$.fn.mColorPicker.hexToRGB=function(a){a=a?a.toLowerCase():!1;return!a?"":p.test(a)?a:r.test(a)?(s.test(a)||(a=a.replace(q,"$1$1$2$2$3$3")),"rgb("+parseInt(a.substr(1,2),16)+", "+parseInt(a.substr(3,2),16)+", "+parseInt(a.substr(5,2),16)+")"):$.fn.mColorPicker.colorTest(a)};f=$.fn.mColorPicker.init;j.ready(function(){l=$("body");$.fn.mColorPicker.events();f.replace&&("function"==typeof $.fn.mDOMupdate?$("input").mDOMupdate($.fn.mColorPicker.start):"function"==typeof $.fn.onquery?$("input").onquery($.fn.mColorPicker.start):($.fn.mColorPicker.start(),j.on("ajaxSuccess.mColorPicker",$.fn.mColorPicker.start)))})})(jQuery);
			function getDirMcolorpicker () {
					if (typeof require !== 'undefined') {
					    var detected = require.toUrl('') + 'Magiccart_Alothemes/js/';
					} else {
						var detected = detectDirMcolorpicker();
					}
					var dir = detected!==false ? detected : 'alothemes/';
				return dir;
			}

			 function detectDirMcolorpicker() {
				var base = location.href;
				var e = document.getElementsByTagName('base');
				for(var i=0; i<e.length; i+=1) {
					if(e[i].href) { base = e[i].href; }
				}
				var e = document.getElementsByTagName('script');
				for(var i=0; i<e.length; i+=1) {
					if(e[i].src && /(^|\/)alothemes.*\.js([?#].*)?$/i.test(e[i].src)) {
						var src = new URI(e[i].src);
						var srcAbs = src.toAbsolute(base);
						srcAbs.path = srcAbs.path.replace(/[^\/]+$/, ''); // remove filename
						srcAbs.query = null;
						srcAbs.fragment = null;
						return srcAbs.toString();
					}
				}
				return false;
			}

			function URI(uri) { // See RFC3986

				this.scheme = null;
				this.authority = null;
				this.path = '';
				this.query = null;
				this.fragment = null;

				this.parse = function(uri) {
					var m = uri.match(/^(([A-Za-z][0-9A-Za-z+.-]*)(:))?((\/\/)([^\/?#]*))?([^?#]*)((\?)([^#]*))?((#)(.*))?/);
					this.scheme = m[3] ? m[2] : null;
					this.authority = m[5] ? m[6] : null;
					this.path = m[7];
					this.query = m[9] ? m[10] : null;
					this.fragment = m[12] ? m[13] : null;
					return this;
				};

				this.toString = function() {
					var result = '';
					if(this.scheme !== null) { result = result + this.scheme + ':'; }
					if(this.authority !== null) { result = result + '//' + this.authority; }
					if(this.path !== null) { result = result + this.path; }
					if(this.query !== null) { result = result + '?' + this.query; }
					if(this.fragment !== null) { result = result + '#' + this.fragment; }
					return result;
				};

				this.toAbsolute = function(base) {
					var base = new URI(base);
					var r = this;
					var t = new URI;

					if(base.scheme === null) { return false; }

					if(r.scheme !== null && r.scheme.toLowerCase() === base.scheme.toLowerCase()) {
						r.scheme = null;
					}

					if(r.scheme !== null) {
						t.scheme = r.scheme;
						t.authority = r.authority;
						t.path = removeDotSegments(r.path);
						t.query = r.query;
					} else {
						if(r.authority !== null) {
							t.authority = r.authority;
							t.path = removeDotSegments(r.path);
							t.query = r.query;
						} else {
							if(r.path === '') { // TODO: == or === ?
								t.path = base.path;
								if(r.query !== null) {
									t.query = r.query;
								} else {
									t.query = base.query;
								}
							} else {
								if(r.path.substr(0,1) === '/') {
									t.path = removeDotSegments(r.path);
								} else {
									if(base.authority !== null && base.path === '') { // TODO: == or === ?
										t.path = '/'+r.path;
									} else {
										t.path = base.path.replace(/[^\/]+$/,'')+r.path;
									}
									t.path = removeDotSegments(t.path);
								}
								t.query = r.query;
							}
							t.authority = base.authority;
						}
						t.scheme = base.scheme;
					}
					t.fragment = r.fragment;

					return t;
				};

				function removeDotSegments(path) {
					var out = '';
					while(path) {
						if(path.substr(0,3)==='../' || path.substr(0,2)==='./') {
							path = path.replace(/^\.+/,'').substr(1);
						} else if(path.substr(0,3)==='/./' || path==='/.') {
							path = '/'+path.substr(3);
						} else if(path.substr(0,4)==='/../' || path==='/..') {
							path = '/'+path.substr(4);
							out = out.replace(/\/?[^\/]*$/, '');
						} else if(path==='.' || path==='..') {
							path = '';
						} else {
							var rm = path.match(/^\/?[^\/]*/)[0];
							path = path.substr(rm.length);
							out = out + rm;
						}
					}
					return out;
				}

				if(uri) {
					this.parse(uri);
				}

			};
			var style = '.accordion .config tr[id^="row_alodesign"] .with-tooltip {font-size: 1.4rem;}';
			style += '.accordion .config tr[id^="row_alodesign"] .admin__control-table td {padding: 1rem;}';
			$('head').append('<style type="text/css">'+style+'</style>');
			var color = '#row_alodesign_base_color, #row_alodesign_header_color, #row_alodesign_left_color, #row_alodesign_right_color, #row_alodesign_content_color, #row_alodesign_footer_color, #row_alodesign_custom_color';
			var background = '#row_alodesign_base_background, #row_alodesign_header_background, #row_alodesign_left_background, #row_alodesign_right_background, #row_alodesign_content_background, #row_alodesign_footer_background, row_alodesign_custom_background';
			var border = '#row_alodesign_base_border, #row_alodesign_header_border, #row_alodesign_left_border, #row_alodesign_right_border, #row_alodesign_content_border, #row_alodesign_footer_border, row_alodesign_custom_border';
		    var selector = color  + ',' + background + ',' + border;
		    $(selector).on("click", 'button', function(){
		    	var input = $(selector).find(".alo-color");
		    	input.each(function(index, el) {
		    		if(!$(this).parent().children('span').hasClass('mColorPickerTrigger')) $(this).attr("data-hex", true).width("116px").mColorPicker();
		    	});
		    });
		    $(selector).each(function(index, el) {
		    	$(this).find(" > td.label").hide();
		    	var readonly = $(this).find(".alo-color.alo-readonly");
		    	if(readonly.length){
		    		$(this).find(".grid, .design_theme_ua_regexp").width("800px");
		    		$(this).find("button").parent().hide();
			    	var container = readonly.parent().parent();
			    	container.parent().parent().find('thead tr th:last-child').hide();
			    	// container.children(':last-child').hide();
			    	var title = container.children(':first-child');
			    	title.find('input').each(function(index, el) {
			    		$(this).addClass('hidden');
				    	$(this).parent().append('<p style="width: 200px">' + $(this).val() + '</P>');	   		
			    	});
			    	readonly.each(function(index, el) {
				    	if($(this).val() =='') $(this).removeClass('alo-color').addClass('hidden');	   		
			    	});
		    	}else {
		    		$(this).find(".grid, .design_theme_ua_regexp").width("1125px");
		    	}
		    });

			$images = getDirMcolorpicker()+ 'images/';
			// console.log($images);
			$.fn.mColorPicker.init.replace = true ; //false;//".color, .alo-color, .mc-color";
			$.fn.mColorPicker.defaults.imageFolder = $images;
			$.fn.mColorPicker.init.allowTransparency = true;
			$.fn.mColorPicker.init.showLogo = false;

			$(".color, .alo-color, .mc-color").attr("data-hex", true).width("116px").mColorPicker(); //$(".color").mColorPicker();
		  	jQuery(document).on({ 
		  		mouseover: function() {jQuery.fn.mColorPicker.events(); $('#mColorPickerWrapper').hide()} 
		  	});
		  	$('body').on('click', function(event) {$('#mColorPicker').hide();});

			$fonts = $('select.mc-fonts');
			$size  = $('select.font-size', $fonts.parent().parent().parent());
			$size.change(function(){$fonts.trigger("click")});
			$fonts.each(function(index, val) {
				$(this).after('<div class="font_preview" style="padding: 10px">Preview this Font</div>');
			});
			$fonts.on('click', function() {
				var $item 	= $(this);
				var $parent = $item.parent();
				var $font 	= $item.val();
				var $size 	= $('select.font-size').val();
				previewFont($size, $font);
				//$item.bind("change", function() {
					var $size 	= $('select.font-size').val();
					var $font 	= $(this).val();
					previewFont($size, $font);
				//});
				function previewFont(size, font){ 
					var link = jQuery("<link>", {
			  			type: "text/css",
			  			rel: "stylesheet", 
			  			href: "//fonts.googleapis.com/css?family=" + font, 
					}).appendTo("head");
					//console.log(size);
					$('.font_preview', $parent).css({"font-size": size, "font-family": font});
				};
			})
		});
});
