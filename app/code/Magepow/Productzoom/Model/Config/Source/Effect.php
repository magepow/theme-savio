<?php

/**
 * @Author: nguyen
 * @Date:   2020-06-09 20:10:33
 * @Last Modified by:   nguyen
 * @Last Modified time: 2022-06-20 16:34:25
 */

namespace Magepow\Productzoom\Model\Config\Source;

class Effect implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            '' 	                        => 'Don\'t use Effect' ,
            'flyOutWindow'              => 'Fly Out Window',
            'flyOutImageToWindow'       => 'Fly Out Image To Window',
            'flySpinningWindow' 		=> 'Fly Spinning Window',
            'flySpinningImageToWindow'  => 'Fly Spinning Image To Window'
        ];
    }
}
