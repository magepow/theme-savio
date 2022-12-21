<?php
/**
 * Magiccart 
 * @category    Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license     http://www.magiccart.net/license-agreement.html
 * @Author: Magiccart<team.magiccart@gmail.com>
 * @@Create Date: 2016-01-07 22:10:30
 * @@Modify Date: 2016-03-23 19:22:01
 * @@Function:
 */

namespace Magiccart\Alothemes\Model\System\Config;

class Attribute implements \Magento\Framework\Option\ArrayInterface
{

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory
     */
    protected $_collectionFactory;

    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $collectionFactory
    )
    {
        $this->_collectionFactory = $collectionFactory;
    }

    public function toOptionArray()
    {
        $options = array('' => __('Select a attribute'));
        $collection = $this->_collectionFactory->create()
                        // ->addFieldToFilter('frontend_input', 'select')
                         // ->addFieldToFilter('is_visible_on_front', 1)
                        ->addVisibleFilter();
        foreach ($collection as $item) {
            $options[$item->getAttributeCode()] = $item->getFrontendLabel();
        }
        return $options;
    }

}
