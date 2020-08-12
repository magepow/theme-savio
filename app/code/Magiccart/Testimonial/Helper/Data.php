<?php
/**
 * Magiccart 
 * @category    Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license     http://www.magiccart.net/license-agreement.html
 * @Author: DOng NGuyen<nguyen@dvn.com>
 * @@Create Date: 2015-12-14 20:26:27
 * @@Modify Date: 2016-03-21 15:59:53
 * @@Function:
 */

namespace Magiccart\Testimonial\Helper;

// use \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\UrlInterface;
use Magento\Customer\Model\Session;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    private $customerSession;
    private $urlInterface;
    const SECTIONS      = 'testimonial';   // module name
    const GROUPS        = 'general';        // setup general
    const MEDIA_PATH    = 'magiccart/testimonial/';
    public function __construct(
        Context $context,
        Session $customerSession
    )
    {
        parent::__construct($context);
        $this->customerSession = $customerSession;
        $this->urlInterface = $context->getUrlBuilder();
    }

    public function getConfig($cfg=null)
    {
        return $this->scopeConfig->getValue(
            $cfg,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    
    public function getGeneralCfg($cfg=null) 
    {
        $config = $this->scopeConfig->getValue(
            self::SECTIONS.'/'.self::GROUPS,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        if(isset($config[$cfg])) return $config[$cfg];
        return $config;
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
