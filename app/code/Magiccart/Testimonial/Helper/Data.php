<?php

/**
 * @Author: nguyen
 * @Date:   2021-06-17 09:43:45
 * @Last Modified by:   nguyen
 * @Last Modified time: 2021-06-17 09:47:16
 */

namespace Magiccart\Testimonial\Helper;


class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;

    private $urlInterface;

    /**
     * @var array
     */
    protected $configModule;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Customer\Model\Session $customerSession
    )
    {
        parent::__construct($context);
        $this->customerSession = $customerSession;
        $this->urlInterface    = $context->getUrlBuilder();
        $module = strtolower(str_replace('Magiccart_', '', $this->_getModuleName()));
        $this->configModule = $this->getConfig($module);
    }

    public function getConfig($cfg='')
    {
        if($cfg) return $this->scopeConfig->getValue( $cfg, \Magento\Store\Model\ScopeInterface::SCOPE_STORE );
        return $this->scopeConfig;
    }

    public function getConfigModule($cfg='', $value=null)
    {
        $values = $this->configModule;
        if( !$cfg ) return $values;
        $config  = explode('/', $cfg);
        $end     = count($config) - 1;
        foreach ($config as $key => $vl) {
            if( isset($values[$vl]) ){
                if( $key == $end ) {
                    $value = $values[$vl];
                }else {
                    $values = $values[$vl];
                }
            } 

        }
        return $value;
    }

    public function isAllowedToAddTestimonial()
    {
        $allowCustomersGroup = explode(",", $this->getAllowedCustomerGroups());
        if (in_array($this->getCustomerGroup(), $allowCustomersGroup)) {
            return true;
        }
        return false;
    }

    public function redirectIfNotLoggedIn()
    {
        if (!$this->customerSession->isLoggedIn()) {
            $this->customerSession->setAfterAuthUrl($this->urlInterface->getCurrentUrl());
            $this->customerSession->authenticate();
        }
    }

}
