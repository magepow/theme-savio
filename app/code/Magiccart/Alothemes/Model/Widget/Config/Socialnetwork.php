<?php
/**
 * Magiccart 
 * @category    Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license     http://www.magiccart.net/license-agreement.html
 * @Author: Magiccart<team.magiccart@gmail.com>
 * @@Create Date: 2014-07-15 20:37:16
 * @@Modify Date: 2016-06-02 11:45:16
 * @@Function:
 */

namespace Magiccart\Alothemes\Model\Widget\Config;

class Socialnetwork implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'instagram', 'label' => __('Instagram')], 
            ['value' => 'pinterest', 'label' => __('Pinterest')],
            ['value' => 'flickr', 'label' => __('Flickr')],
            ['value' => 'picasa', 'label' => __('Picasa')],
        ];
    }

}
