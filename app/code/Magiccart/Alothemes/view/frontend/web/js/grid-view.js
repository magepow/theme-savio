/*
* @Author: Alex Dong
* @Date:   2021-06-20 13:21:07
* @Last Modified by:   nguyen
* @Last Modified time: 2021-07-14 16:16:33
*/

define([
    'jquery',
    'jquery/ui'
    ], function ($) {
		"use strict";
        $.widget('magiccart.gridView', {
            options: {
                selector: '',
            },

            _create: function () {
            	var options = this.options;
            	this._initGrid();
            },

			_uniqid: function (a = "", b = false) {
			    const c = Date.now()/1000;
			    let d = c.toString(16).split(".").join("");
			    while(d.length < 14) d += "0";
			    let e = "";
			    if(b){
			        e = ".";
			        e += Math.round(Math.random()*100000000);
			    }
			    return a + d + e;
			},

            _initGrid: function () {
                var options = this.options;
                var self = this;
                var elements = options.selector ? self.element.find(options.selector) : self.element;
                elements.each(function() {
                    var element = $(this);
					self.gridActive(element);
					$(window).on('resize', function(){
						self.gridActive(element);
					});
                	self.gridRender(element);		           	
                });
            },

            gridActive: function (element) {
		        var responsive 	= element.data('responsive');
				if(responsive == undefined) return;
				var length = Object.keys(responsive).length;
				var grid = 0;
				var starWith = 1;
				var screenWidth = window.innerWidth;
				$.each( responsive, function( key, value ) {
					$.each( responsive[key], function( maxWith, num) { 
						if( starWith < screenWidth && screenWidth <= maxWith ){ starWith = maxWith; grid = num; }
					});
				});
				element.find('.grid-' + grid).addClass('active').siblings().removeClass('active');
            },

            gridRender: function (element) {
            	var self 	= this;
            	var $body   = $('body');
                element.addClass('grid-view-' + self._uniqid());
				var options = element.data();
				var selector= '.category-products';
				var classes	= '.product-items .product-item';
		        var padding = options.padding;
				var responsive 	= options.responsive;
				if(responsive  == undefined) return;
		        var float  	= $('body').hasClass('rtl') ? 'right' : 'left';
		        var length  = Object.keys(responsive).length;
				element.on('click', '.grid-mode', function(event) {
					if($(this).hasClass('active')) return;
					var screenWidth = window.innerWidth;
					var $this = $(this);
		        	var style = padding ? classes + '{float: ' + float + '; padding: 0 '+padding+'px; box-sizing: border-box} ' + selector + '{margin: 0 -'+padding+'px}' : '';
					var starWith = 1;
					var gridViewStyle = $('.grid-view-style');
					gridViewStyle.not(':first').remove();
					$.each( responsive, function( key, value ) { // data-responsive="[{"1":"1"},{"361":"1"},{"480":"2"},{"640":"3"},{"768":"3"},{"992":"4"},{"1200":"4"}]"
						var grid = 0;
						var maxWith = 0;
						var minWith = 0;
						$.each( value , function(size, num) { minWith = parseInt(size) + 1; grid = num;});
						if(key+2<length){
							$.each( responsive[key+1], function( size, num) { maxWith = size; grid = num;});
							// padding = options.padding*(maxWith/1200); // padding responsive
							style += ' @media (min-width: '+minWith+'px) and (max-width: '+maxWith+'px)';
						} else { 
							if(key+2 == length) return; // don't use key = length - 1;
							$.each( responsive[key], function( size, num) { maxWith = size; grid = num;});
							style += ' @media (min-width: '+maxWith+'px)';
						}
                        if(starWith < screenWidth && screenWidth <= maxWith){
                            starWith = maxWith;
                            grid = $this.data('grid');
                            responsive[key] = {[maxWith]: grid}; 
                            $this.addClass('active');
                            $this.siblings().each(function() {
                                $(this).removeClass('active');
                                $body.removeClass('grid-mode-' + $(this).data('grid'));
                            });
                            $body.addClass('grid-mode-' + grid);

                        }
						style += ' {'+selector + ' .content-products' + '{margin-left: -'+padding+'px; margin-right: -'+padding+'px}'+classes+'{padding-left: '+padding+'px; padding-right:'+padding+'px; width: '+(Math.floor((10/grid) * 100000000000) / 10000000000)+'%} '+classes+':nth-child('+grid+'n+1){clear: ' + float + ';}}';
					});
	
			       	gridViewStyle.html(style);
			       	$(window).trigger('resize');
				});
            }

        });

    return $.magiccart.gridView;
});
