/**
 * Magiccart 
 * @category 	Magiccart 
 * @copyright 	Copyright (c) 2014 Magiccart (http://www.magepow.com/) 
 * @license 	https://www.magepow.com/license-agreement.html
 * @Author: DOng NGuyen<nguyen@dvn.com>
 * @@Create Date: 2014-04-25 13:16:48
 * @@Modify Date: 2020-07-10 12:05:30
 * @@Function:
 */

define([
    'jquery',
    'magiccart/slick',
	'magiccart/parallax',
	'jquery/jquery.cookie',
	'Magento_Ui/js/modal/modal'
    ], function ($, slick, parallax, cookie, modal) {
	"use strict";
	window.magicproduct = function(el, iClass) {
		if( !el.data( 'vertical') && $('body').hasClass('rtl') ){
			el.attr('dir', 'rtl');
			el.data( 'rtl', true );
			// el.data( 'vertical-reverse', true );
		}
		var options = el.data();
		if(iClass === undefined){
			el.children().addClass('alo-item');
			iClass = '.alo-item';
		}
		var selector = el.selector;
		if(!selector) {
			selector = 'magicslider-' + Math.floor((Math.random() * 9999999999) + 1);
			el.addClass(selector);
			selector = '.' + selector;
		}
		var classes = selector + ' '+ iClass;
		var padding = options.padding;
		var float  = $('body').hasClass('rtl') ? 'right' : 'left';
		var style = padding ? classes + '{float: ' + float + '; padding-left: '+padding+'px; padding-right:'+padding+'px} ' + selector + '{margin-left: -'+padding+'px; margin-right: -'+padding+'px}' : '';
		if(options.slidesToShow){
			el.on('init', function(event, slick){
			    $('body').trigger('contentUpdated');
			});			
			if(el.hasClass('slick-initialized')) el.slick("refresh");
			else $(el).slick(options);
		} else {
			var responsive 	= options.responsive;
			if(responsive == undefined) return;
			var length = Object.keys(responsive).length;

			$.each( responsive, function( key, value ) { // data-responsive="[{"1":"1"},{"361":"1"},{"480":"2"},{"640":"3"},{"768":"3"},{"992":"4"},{"1200":"4"}]"
				var col = 0;
				var maxWith = 0;
				var minWith = 0;
				$.each( value , function(size, num) { minWith = parseInt(size) + 1; col = num;});
				if(key+2<length){
					$.each( responsive[key+1], function( size, num) { maxWith = size; col = num;});
					// padding = options.padding*(maxWith/1200); // padding responsive
					style += ' @media (min-width: '+minWith+'px) and (max-width: '+maxWith+'px)';
				} else { 
					if(key+2 == length) return; // don't use key = length - 1;
					$.each( responsive[key], function( size, num) { maxWith = size; col = num;});
					style += ' @media (min-width: '+maxWith+'px)';
				}
				style += ' {'+selector + '{margin-left: -'+padding+'px; margin-right: -'+padding+'px}'+classes+'{padding-left: '+padding+'px; padding-right:'+padding+'px; width: '+(Math.floor((10/col) * 100000000000) / 10000000000)+'%} '+classes+':nth-child('+col+'n+1){clear: ' + float + ';}}';
			});
		}

		$('head').append('<style type="text/css">'+style+'</style>'); // return '<style type="text/css">'+style+'</style>';
	};

	/* Timer */
	(function ($) {
		"use strict";
		$.fn.timer = function (options) {
			var defaults = {
				classes  	 : '.countdown',
				layout	 	 : '<span class="day">%%D%%</span><span class class="colon">:</span><span class="hour">%%H%%</span><span class="colon">:</span><span class="min">%%M%%</span><span class="colon">:</span><span class="sec">%%S%%</span>',
				layoutcaption: '<div class="timer-box"><span class="day">%%D%%</span><span class="title">Days</span></div><div class="timer-box"><span class="hour">%%H%%</span><span class="title">Hrs</span></div><div class="timer-box"><span class="min">%%M%%</span><span class="title">Mins</span></div><div class="timer-box"><span class="sec">%%S%%</span><span class="title">Secs</span></div>',
				leadingZero	 : true,
				countStepper : -1, // s: -1 // min: -60 // hour: -3600
				timeout	 	 : '<span class="timeout">Time out!</span>',
			};

			var settings = $.extend(defaults, options);
			var layout			 = settings.layout;
			var layoutcaption	 = settings.layoutcaption;
			var leadingZero 	 = settings.leadingZero;
			var countStepper 	 = settings.countStepper;
			var setTimeOutPeriod = (Math.abs(countStepper)-1)*1000 + 990;
			var timeout 		 = settings.timeout;

			var methods = {
				init : function() {
					return this.each(function() {
						var $countdown 	= $(settings.classes, $(this));
						if( $countdown.length && !$countdown.hasClass('init')){
							$countdown.addClass('init');
							methods.timerLoad($countdown);
						}
					});
				},
				
				timerLoad: function(el){
					var gsecs = el.data('timer');
					if(isNaN(gsecs)){
						var start = Date.parse(new Date());
						var end = Date.parse(gsecs);
						gsecs  = (end - start)/1000;	
					}
					if(gsecs > 0 ){
						methods.CountBack(el, gsecs);
					}
				},

				calcage: function (secs, num1, num2) {
					var s = ((Math.floor(secs/num1)%num2)).toString();
					if (leadingZero && s.length < 2) s = "0" + s;
					return "<b>" + s + "</b>";
				},

				CountBack: function (el, secs) {
					if (secs < 0) {
						el.html(timeout);
						return;
					}
					if(el.hasClass('caption')){
						var timerStr = layoutcaption.replace(/%%D%%/g, methods.calcage(secs,86400,100000));
					}else {
						var timerStr = layout.replace(/%%D%%/g, methods.calcage(secs,86400,100000));					
					}
					timerStr = timerStr.replace(/%%H%%/g, methods.calcage(secs,3600,24));
					timerStr = timerStr.replace(/%%M%%/g, methods.calcage(secs,60,60));
					timerStr = timerStr.replace(/%%S%%/g, methods.calcage(secs,1,60));
					el.html(timerStr);
					setTimeout(function(){ methods.CountBack(el, (secs+countStepper))}, setTimeOutPeriod);
				},

			};

			if (methods[options]) { // $("#element").pluginName('methodName', 'arg1', 'arg2');
				return methods[options].apply(this, Array.prototype.slice.call(arguments, 1));
			} else if (typeof options === 'object' || !options) { // $("#element").pluginName({ option: 1, option:2 });
				return methods.init.apply(this);
			} else {
				$.error('Method "' + method + '" does not exist in timer plugin!');
			}
		}

		$(document).ready(function($) {
			if (typeof alo_timer_layoutcaption != 'undefined'){
				$('.alo-count-down').not('.exception').timer({
					classes			: '.countdown',
					layout			: alo_timer_layout, 
					layoutcaption	: alo_timer_layoutcaption, 
					timeout 		: alo_timer_timeout
				});
			} else {
				$('.alo-count-down').not('.exception').timer({classes : '.countdown'});			
			}
		});
	})($);
	/* End Timer */
	/* notifySlider */
	(function ($) {
	    "use strict";
	    $.fn.notifySlider = function (options) {
	      	var defaults = {
		        autoplay   : true,
		        firsttime  : 3000,
		        speed      : 9000
	      	};

			var settings    = $.extend(defaults, options);
			var firsttime   = parseInt(settings.firsttime);
			var speed    	= parseInt(settings.speed);
			var autoplay    = settings.autoplay;

	      	var methods = {
		        init : function() {
			        return this.each(function() {
			        	methods.suggestLoad($(this));
			        });
		        },
		        
		        suggestLoad: function(suggest){
		            var el  = suggest.find('.notify-slider-wrapper');
		            suggest.find('.x-close').click(function() {
		                suggest.addClass('close')
		            });
		            var slideCount    = suggest.find('.slider >.item').length;
		            var slideWidth    = suggest.find('.slider >.item').width();
		            var slideHeight   = suggest.find('.slider >.item').height();
		            var sliderUlWidth = slideCount * slideWidth;
		            /*suggest.find('.notify-slider').css({ width: slideWidth, height: slideHeight });*/
		            suggest.find('.notify-slider .slider').css({ width: sliderUlWidth});
		            suggest.find('.notify-slider .slider >.item:last-child').prependTo('.notify-slider .slider');
		            setTimeout(function(){
		            	el.slideDown('slow'); 
			            if(!autoplay) return;
			            setInterval(function () {
			                el.slideUp({
			                        duration:'slow', 
			                        easing: 'swing',
			                        complete: function(){
			                            methods.moveRight(suggest, slideWidth);
			                            setTimeout(function(){ el.slideDown('slow'); }, speed/2);
			                        }
			                    });

			            }, speed);
		            }, firsttime);
		        },

		        moveRight: function(suggest, slideWidth){
		            suggest.find('.notify-slider .slider').animate({
		                left: - slideWidth
		            }, 0, function () {
		                var slider = suggest.find('.notify-slider .slider');
		                suggest.find('.notify-slider .slider >.item:first-child').appendTo(slider);
		                slider.css('left', '');
		            })
		        }

	      	};

	      	if (methods[options]) {
	        	return methods[options].apply(this, Array.prototype.slice.call(arguments, 1));
	      	} else if (typeof options === 'object' || !options) {
	        	return methods.init.apply(this);
	      	} else {
	        	$.error('Method "' + method + '" does not exist in timer plugin!');
	      	}
	    }

	    $(document).ready(function($) {
		    $('.suggest-slider').each(function() {
		    	if($(this).hasClass('autoplay')){
		    		var config = $(this).data();
		    		$(this).notifySlider(config);
		    	}
		    });  
	    });
	    
  	})($);

	/* End notifySlider */
	
	$(document).ready(function($) {
		$('body').removeClass('preload');
		var specialOffer = $('#header-offer');
		specialOffer.find('.header-offer-close').click(function() {
			specialOffer.slideUp('slow');
		});

		$('.home-slider, .magicslider').each(function() { // home-slider, magic-slider
			if($(this).hasClass('autoplay')){
	    		magicproduct($(this));
	    	}
		});

		// Realated + Upsell + Crosssell
	    $('.products-related .product-items, .products-upsell .product-items, .products-crosssell .product-items').each(function() {
	    	magicproduct($(this))
	    });
		// End Realated + Upsell + Crosssell

		// add Js
		var $toggleTab  = $('.toggle-tab');
		var $toggleContent  = $('.toggle-content');
		$(document).on("click", '.toggle-tab', function(){
			$(this).parent().toggleClass('toggle-visible').find('.toggle-content').toggleClass('visible');
			var input = $(this).parent().find('input');
			if(input.length) input.first().focus();
		});
		$(window).click(function(event) {
			if (!$toggleContent.is(event.target) && $toggleContent.has(event.target).length === 0) 
			{
				$toggleTab.each(function() {
					if( $(this).has(event.target).length ) return;
					if( $(this).parent().has(event.target).length ) return;
					if( $(this).hasClass('outside-none') ) return;
					$(this).parent().removeClass('toggle-visible').find('.toggle-content').removeClass('visible');
				});
			}
		});
		// add click map

		var $toggleTabMap  = $('.onclick-map');

		$toggleTabMap.click(function(){
			$(this).parent().toggleClass('toggle-visible').find('.toggle-content').toggleClass('visible');
		});
		var $closeMap = $('.onmap .btn-close');
		$closeMap.click(function() {
			$(this).closest('.tool-map').removeClass('toggle-visible').find('.toggle-content-close').removeClass('visible');
		});

	    function _increaseJnit(){
	    	var updateCart;
			$('.main').on("click", '.alo_qty_dec', function(){
			    var input = $(this).closest('.qty').find('input');
		        var value  = parseInt(input.val());
		        if(value) input.val(value-1);
		        clearTimeout(updateCart);
		        updateCart = setTimeout(function(){ $('.action.update').trigger('click'); }, 1000);
			});
		    $('.main').on("click", '.alo_qty_inc', function(){
		        var input = $(this).closest('.qty').find('input');
		        var value  = parseInt(input.val());
		        input.val(value+1);
		        clearTimeout(updateCart);
		        updateCart = setTimeout(function(){ $('.action.update').trigger('click'); }, 1000);
		    });			    	
	    }

	    function _goTopJnit(){
			var $backtotop = $('#backtotop');
			$backtotop.hide();
			var height =  $(document).height();
			$(window).scroll(function () {
				if(!$('body').hasClass('cms-no-route'))
					if ($(this).scrollTop() > height/10) {
						$backtotop.fadeIn();
					} else {
						$backtotop.fadeOut();
					}
			});
			$backtotop.click(function () {
				$('body,html').animate({
					scrollTop: 0
				}, 800);
				return false;
			});
	    }

	    function _sktickyCartJnit(){
	    	var topmenu  	 = $('.magicmenu')
            var menuHeight 	 = topmenu.height()/2;
            var postionTop 	 = topmenu.offset().top + menuHeight;
            var headerHeight = $('header').height();
            var minicart 	 = $('.minicart-wrapper');
            var minicartParent = minicart.parent();
            $(window).scroll(function () {
                var postion = $(this).scrollTop();
                if (postion > postionTop ){
                    $('.magicmenu .nav-desktop').append(minicart);
                } else {
                	('.magicmenu .nav-desktop')
                	minicartParent.prepend($('.magicmenu .nav-desktop').find('.minicart-wrapper'))
                }
            });
	    }

		function _elevatorJnit(){
			/* elevator click*/ 
			var $megashop = $('.megashop');
			var length = $megashop.length;
			$megashop.each(function(index, el) {
				var elevator = $(this).find('.floor-elevator');
				elevator.attr('id', 'elevator-' +index);
				var bntUp 	= elevator.find('.btn-elevator.up');
				var bntDown = elevator.find('.btn-elevator.down');
				bntUp.attr('href', '#elevator-' + (index-1));
				bntDown.attr('href', '#elevator-' +(index+1));
				if(!index) bntUp.addClass('disabled');
				if(index == length-1) bntDown.addClass('disabled');
				elevator.find('.btn-elevator').click(function(e) {
					 e.preventDefault();
					var target = this.hash;
					if($(document).find(target).length <=0){
						return false;
					}
					var $target = $(target);
					$('html, body').stop().animate({
						'scrollTop': $target.offset().top-50
					}, 500);
					return false;
				});
			});
		}

	    // add equalheight category
		(function( $ ) {

			$.fn.equalHeights = function( options ) {
				var defaults = {
					onResize: 	true,
					onLoad: 	true
				};
				var settings = $.extend( {}, defaults, options );
				
				var topPositions = {},
					foundPosition = 0,
					$el = [],
					curHeight = 0,
					topPosition = 0,
					resizeTimer,
					$elements = $(this);
			 
				if ($elements.length < 2) return this;
				
				if ( settings.onResize ) {
					$( window ).resize(function() {
						if ( resizeTimer ) window.clearTimeout(resizeTimer);
						resizeTimer = window.setTimeout(function() {
							$elements.equalHeights( { onResize: false, onLoad: false } );
						}, 100);
					});
				};

				if ( settings.onLoad ) {
					$( window ).load(function() {
						$elements.equalHeights( { onResize: false, onLoad: false } );
					});
				}
			 
				$elements.each(function() {
					$el = $(this);
					$el.height('auto');// restore original height from possible previous equalHeights()
					curHeight = $el.height();
			 
					if ( curHeight > 0 ) {
						topPosition = $el.position().top;
						foundPosition = topPosition in topPositions;
							 
						if(!foundPosition) {
							// First at this position, only store and set height
							topPositions[topPosition] = curHeight;
							$el.height(topPositions[topPosition]);
						} else {
							if(curHeight > topPositions[topPosition]) {
								// Tallest so far for this position, remember tallest and stretch others on this position
								topPositions[topPosition] = curHeight;
								$($elements).filter(function() {
									return $(this).position().top == topPosition;
								}).height(curHeight);
							} else {
								// Same or less high, maximize this one
								$el.height(topPositions[topPosition]);
							}
						}
					}
				});
			 
				return this;
			 
			};

		}( $ ));

	    // add equalheight category
		function _equalHeightJnit(selector){
			if(selector === undefined) selector = '.category-products.products-grid .items';
		    $(selector).each(function(){
		        var $this = $(this),
		            target = $this.find('>.item');
		        $this.find(target).equalHeights();
		    });
		}

		function _qsJnit(){
			var obj = arguments[0];
			var _qsModalContent = '<div class="content-quickview">quickview placeholder</div>';
			if(!$('#modals_quickview').length){
				$(document.body).append('<div id="modals_quickview" style="display:none">' + _qsModalContent + '</div>');
			}
			var _qsModal = $('#modals_quickview .content-quickview');
			var quickajax= function(url){
				if(_qsModal.length) $('#modals_quickview').html(_qsModalContent);
				// _qsModal.trigger('contentUpdated');
                $.ajax({
                    url:url,
                    type:'POST',
                    showLoader: true,
                    cache:false,   
                    success:function(data){
                    	_qsModal.html(data);
			            modal({
			                type: 'popup',
			                modalClass: 'modals-quickview',
			                responsive: true, 
							buttons: false,
			                closed: function(){
			                	$('.modals-quickview').remove();
			                }                       	
						}, _qsModal);
						var body = $('body');
						_qsModal.modal('openModal');
						body.addClass('open-quickview');
						_qsModal.trigger('contentUpdated');
						_qsModal.on('modalclosed', function(){body.removeClass('open-quickview');});
                    }
                });
                _qsModal.on('fotorama:load', function(){
					_qsModal.find(".product-view .product-info-main.product-shop").height(_qsModal.find(".product-img-box").height());
                });
			}
			if(obj.url){
				quickajax(obj.url)
			} else {
				$(document).on('click', obj.itemClass, function(e) {
					e.preventDefault();
	                quickajax($(this).data('url'))
				});
			}
		}

		_increaseJnit()

		_goTopJnit()

		// _sktickyCartJnit()

		_elevatorJnit()

		$(window).resize(function(){
			_equalHeightJnit();
		});
		$(document).on('swatch.initialized', function() {
			_equalHeightJnit();
		})

		_qsJnit({
			url : '',
			itemClass : '.quickview.autoplay',
		});

		$.fn.quickview 	 = _qsJnit;
		$.fn.equalheight = _equalHeightJnit;

		if($('.bg-parallax').length >0){

			$('.bg-parallax').each(function(){

				$(this).parallax("50%",0.1);

			})  

		}

		$('.delivery-return .delivery-return-text').click(function () {
			$('#delivery-return-popup').modal({
				type: 'popup',
				modalClass: 'modals-deliveryguide',
				responsive: true, 
				buttons: false           
			});
			$("#delivery-return-popup").modal("openModal");
		});
	
	});

});
