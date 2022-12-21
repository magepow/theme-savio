<?php
/**
 * @Author: nguyen
 * @Date:   2020-02-12 14:01:01
 * @Last Modified by:   nguyen
 * @Last Modified time: 2020-09-15 21:04:07
 */

namespace Magiccart\Alothemes\Helper;

class Tab extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var array
     */
    protected $configModule;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context
    )
    {
        parent::__construct($context);
    }

    public function getConfig($cfg='')
    {
        if($cfg) return $this->scopeConfig->getValue( $cfg, \Magento\Store\Model\ScopeInterface::SCOPE_STORE );
        return $this->scopeConfig;
    }

    public function getTab($cfg='', $value=null)
    {

    }

    public function getOptions($value)
    {
        $tmp = json_decode($value, true);
        if(json_last_error() == JSON_ERROR_NONE){
            $value = $tmp;
        } else {
            $value = @unserialize($value);
        }
        return $value;
    }

    public function getBlockTab()
    {
        $value = $this->getConfig('alothemes/product_page/tab_block');
        return $this->getOptions($value);
    }

    public function getAttributeTab()
    {
        $value = $this->getConfig('alothemes/product_page/tab_attribute');
        return $this->getOptions($value);
    }

}
