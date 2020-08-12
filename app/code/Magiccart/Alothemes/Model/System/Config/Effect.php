<?php
/**
 * Magiccart 
 * @category    Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license     http://www.magiccart.net/license-agreement.html
 * @Author: DOng NGuyen<nguyen@dvn.com>
 * @@Create Date: 2017-08-29 18:57:54
 * @@Modify Date: 2017-08-29 19:02:17
 * @@Function:
 */

namespace Magiccart\Alothemes\Model\System\Config;

class Effect implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'swing',           'label'=>__('swing')),
            array('value' => 'easeOutQuad',     'label'=>__('easeOutQuad')),
            array('value' => 'easeOutCirc',     'label'=>__('easeOutCirc')),
            array('value' => 'easeOutElastic',  'label'=>__('easeOutElastic')),
            array('value' => 'easeOutExpo',     'label'=>__('easeOutExpo')),
        );
    }
}
