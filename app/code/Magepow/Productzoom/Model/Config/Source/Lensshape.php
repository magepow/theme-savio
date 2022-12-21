<?php

/**
 * @Author: nguyen
 * @Date:   2020-06-09 20:10:33
 * @Last Modified by:   nguyen
 * @Last Modified time: 2020-07-09 22:34:47
 */

namespace Magepow\Productzoom\Model\Config\Source;

class Lensshape implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            'square' 	=> 'Square',
            'round' 	=> 'Round'
        ];
    }
}
