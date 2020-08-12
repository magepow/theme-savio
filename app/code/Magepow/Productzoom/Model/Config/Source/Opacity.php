<?php

/**
 * @Author: nguyen
 * @Date:   2020-06-09 20:10:33
 * @Last Modified by:   nguyen
 * @Last Modified time: 2020-07-10 11:57:27
 */

namespace Magepow\Productzoom\Model\Config\Source;

class Opacity implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            '0' 	=> 0,
            '0.1'   => 0.1,
            '0.2' 	=> 0.2,
            '0.3' 	=> 0.3,
            '0.4' 	=> 0.4,
            '0.5' 	=> 0.5,
            '0.6' 	=> 0.6,
            '0.7' 	=> 0.7,
            '0.8' 	=> 0.8,
            '0.9' 	=> 0.9,
            '1' 	=> 1
        ];
    }
}
