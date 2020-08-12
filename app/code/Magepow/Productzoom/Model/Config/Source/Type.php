<?php

/**
 * @Author: nguyen
 * @Date:   2020-06-09 20:10:33
 * @Last Modified by:   nguyen
 * @Last Modified time: 2020-07-09 22:33:39
 */

namespace Magepow\Productzoom\Model\Config\Source;

class Type implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            'window' 	=> 'Window',
            'inner' 	=> 'Inner',
            'lens' 		=> 'Lens'
        ];
    }
}
