<?php

/**
 * @Author: nguyen
 * @Date:   2020-09-21 23:26:19
 * @Last Modified by:   nguyen
 * @Last Modified time: 2020-09-27 15:06:44
 */

namespace Magiccart\Alothemes\Helper;

class Flag extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Data constructor.
     * @param StoreManagerInterface $storeManager
     * @param Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager

    ) {
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }



    /**
     * GetUpload
     *
     * @param number $storeId
     * @return string
     */
    public function getUrlImageFlag($storeId = null)
    {
        $imgUrl = $this->scopeConfig
            ->getValue('alothemes/flags/flag', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
        if ($imgUrl != '') {
            return $this->storeManager->getStore($storeId)
                   ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'magiccart/flags/' . $imgUrl;
        }
        return false;
    }

    /**
     * GetStoreId
     *
     * @return int
     */
    public function getStoreId()
    {
        return $this->storeManager->getStore()->getId();
    }
}
