<?php
/**
 * Magiccart 
 * @category    Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license     http://www.magiccart.net/license-agreement.html
 * @Author: DOng NGuyen<nguyen@dvn.com>
 * @@Create Date: 2017-08-29 18:57:54
 * @@Modify Date: 2017-08-29 19:00:20
 * @@Function:
 */

namespace Magiccart\Alothemes\Model\System\Config;

class Position implements \Magento\Framework\Option\ArrayInterface
{

    public function toOptionArray()
    {
        return array(
            array('value' => 'left',   'label'=>__('Left')),
            array('value' => 'right',  'label'=>__('Right')),
        );
    }

}
