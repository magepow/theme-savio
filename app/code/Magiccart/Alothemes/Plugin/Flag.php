<?php

/**
 * @Author: nguyen
 * @Date:   2020-09-21 23:33:06
 * @Last Modified by:   nguyen
 * @Last Modified time: 2020-09-27 15:03:38
 */

namespace Magiccart\Alothemes\Plugin;

class Flag
{
    /**
     * @var \Magiccart\Alothemes\Helper\Flag
     */
    protected $helper;

    /**
     * ShowFlagFrontend constructor.
     *
     * @param \Magiccart\Alothemes\Helper\Flag $helper
     */
    public function __construct(
        \Magiccart\Alothemes\Helper\Flag $helper
    ) {
        $this->helper = $helper;
    }

    /**
     * Plugin After
     *
     * @param \Magento\Store\Block\Switcher $subject
     * @param \Magento\Store\Block\Switcher $result
     * @return mixed
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function afterGetStores(\Magento\Store\Block\Switcher $subject, $result)
    {
        $data = [];
        $storeIds = array_keys($result);
        foreach ($storeIds as $storeId) {
            if ($this->helper->getUrlImageFlag($storeId)) {
                $data[$storeId] =  $this->helper->getUrlImageFlag($storeId);
            }
        }
        $subject->setData('flags', $data);
        return $result;
    }
}
