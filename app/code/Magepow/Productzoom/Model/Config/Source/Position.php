<?php

/**
 * @Author: nguyen
 * @Date:   2020-06-09 20:10:33
 * @Last Modified by:   Alex Dong
 * @Last Modified time: 2020-08-08 16:19:53
 */

namespace Magepow\Productzoom\Model\Config\Source;

class Position implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            1 	=> 1,
            2 	=> 2,
            3 	=> 3,
            4 	=> 4,
            5 	=> 5,
            6 	=> 6,
            7 	=> 7,
            8 	=> 8,
            9 	=> 9,
            10 	=> 10,
            11 	=> 11,
            12  => 12,
            13  => 13,
            14  => 14
        ];
    }
}
