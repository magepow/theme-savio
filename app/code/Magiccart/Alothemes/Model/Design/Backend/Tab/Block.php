<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magiccart\Alothemes\Model\Design\Backend\Tab;

use Magento\Config\Model\Config\Backend\Serialized\ArraySerialized;

class Block extends ArraySerialized
{
 
    /**
     * Initialize dependencies
     *
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $config
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
    }

    /**
     * Validate value
     *
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     * if there is no field value, search value is empty or regular expression is not valid
     */
    public function beforeSave()
    {
        // For value validations
        $exceptions = $this->getValue();
        if(is_array($exceptions)){
            foreach ($exceptions as $rowKey => $row) {
                if ($rowKey === '__empty') {
                    continue;
                }

                // Validate that all values have come
                foreach (['title', 'block'] as $fieldName) {
                    if (!isset($row[$fieldName])) {
                        throw new \Magento\Framework\Exception\LocalizedException(
                            __('Exception does not contain field \'%1\'', $fieldName)
                        );
                    }
                }

                // Empty string (match all) is not supported, because it means setting a default theme. Remove such entries.
                if (!strlen($row['block'])) {
                    unset($exceptions[$rowKey]);
                    continue;
                }

            }            
        }

        $this->setValue($exceptions);

        return parent::beforeSave();
    }

    protected function _afterLoad()
    {
        $value = $this->getValue();
        if (is_string($value)) {
        	if($this->isJson($value)) {
        		$this->setValue(empty($value) ? false : json_decode($value, true));
        	}else {
	        	$this->setValue(empty($value) ? false : unserialize($value)); 		
        	}
        }
    }

    protected function isJson($string) {
		json_decode($string);
		return (json_last_error() == JSON_ERROR_NONE);
	}

}
