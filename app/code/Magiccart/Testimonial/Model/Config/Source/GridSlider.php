<?php

/**
 * @Author: Alex Dong
 * @Date:   2020-07-14 19:36:33
 * @Last Modified by:   nguyen
 * @Last Modified time: 2021-06-17 10:32:17
 */

namespace Magiccart\Testimonial\Model\Config\Source;

class GridSlider
{
    public static function getSlideOptions()
    {
        return [
            'autoplay', 
            'arrows', 
            'fade', 
            'center-mode', 
            'adaptive-height',
            'autoplay-speed', 
            'dots', 
            'infinite', 
            'padding', 
            'vertical', 
            'vertical-swiping', 
            'responsive', 
            'rows', 
            'slides-to-show'
        ];
    }

    public static function getBreakpoints()
    {
        return [
        	1921	=>'visible', 
        	1920	=>'widescreen', 
        	1480	=>'desktop', 
        	1200	=>'laptop', 
        	992		=>'notebook', 
        	768		=>'tablet', 
        	576		=>'landscape', 
        	481		=>'portrait', 
        	361		=>'mobile', 
        	1		=>'mobile'
        ];
    }

}
