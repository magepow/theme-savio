define([
    'jquery',
    ], function ($) {
	"use strict";
	/* notifySlider */
	(function ($) {
	    "use strict";
	    $.fn.notifySlider = function (options) {
	      	var defaults = {
		        autoplay   : true,
		        close_off  : true,
		        firsttime  : 3000,
		        speed      : 9000
	      	};

			var settings    = $.extend(defaults, options);
			var firsttime   = parseInt(settings.firsttime);
			var speed    	= parseInt(settings.speed);
			var autoplay    = settings.autoplay;
			var closeOff 	= settings.close_off;

	      	var methods = {
		        init : function() {
			        return this.each(function() {
			        	methods.suggestLoad($(this));
			        });
		        },
		        
		        suggestLoad: function(suggest){
		        	if (closeOff && sessionStorage.getItem("recently_order_click_close")) return;
		            var el  = suggest.find('.notify-slider-wrapper');
		            suggest.find('.x-close').click(function() {
		                suggest.addClass('close');
		                sessionStorage.setItem("recently_order_click_close", closeOff);
		            });
		            var slideCount    = suggest.find('.slider >.item').length;
		            var slideWidth    = suggest.find('.slider >.item').width();
		            var slideHeight   = suggest.find('.slider >.item').height();
		            var sliderUlWidth = slideCount * slideWidth;
		            /*suggest.find('.notify-slider').css({ width: slideWidth, height: slideHeight });*/
		            suggest.find('.notify-slider .slider').css({ width: sliderUlWidth});
		            suggest.find('.notify-slider .slider >.item:last-child').prependTo('.notify-slider .slider');
		            setTimeout(function(){
		            	methods.notifySliderUp(el);
			            if(!autoplay) return;
			            setInterval(function () {
			            	methods.moveRight(suggest, slideWidth);
			            	methods.notifySliderUp(el);
			            	setTimeout(function(){ 
				            	methods.notifySliderDown(el);
			            	}, speed/2);
			            }, speed);
		            	setTimeout(function(){ 
			            	methods.notifySliderDown(el);
		            	}, speed/2);
		            }, firsttime);
		        },

		        notifySliderDown: function(el){
	            	el.removeClass('notifySliderUp'); 
	            	el.addClass('notifySliderDown');
		        },

		        notifySliderUp: function(el){
	            	el.removeClass('notifySliderDown');
	            	el.addClass('notifySliderUp');
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
	    
  	})($);

	/* End notifySlider */
});