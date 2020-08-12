<?php
/**
 * Copyright © 2015-17 Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 *
 * Glory to Ukraine! Glory to the heroes!
 */

namespace Magefan\Blog\Model\Config\Source;

/**
 * Comment statuses
 */
class DisplayMode implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @const string
     */
    const PENDING = 0;

    /**
     * @const int
     */
    const APPROVED = 1;
    
    /**
     * Options int
     *
     * @return array
     */
    public function toOptionArray()
    {
        return  [
            ['value' => self::PENDING, 'label' => __('Recent Blog Posts')],
            ['value' => self::APPROVED, 'label' => __('Featured Blog Posts')],
        ];
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        $array = [];
        foreach ($this->toOptionArray() as $item) {
            $array[$item['value']] = $item['label'];
        }
        return $array;
    }
}
