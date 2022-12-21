<?php

/**
 * @Author: nguyen
 * @Date:   2021-02-09 22:13:11
 * @Last Modified by:   Alex Dong
 * @Last Modified time: 2022-09-14 09:39:04
 */

namespace Magiccart\Alothemes\Model\System\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Header implements \Magento\Framework\Option\ArrayInterface
{

    /**
     * ScopeConfigInterface
     *
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * ScopeConfigInterface
     *
     * @var \Magento\Framework\App\RequestInterface
     */
        
    protected $_request;

    /**
     * theme collection factory.
     *
     * @var \Magento\Theme\Model\ThemeFactory
     */
    protected $_themeFactory;


    public function __construct(
        ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\View\FileSystem $viewFileSystem,
        \Magento\Theme\Model\Theme $themeFactory,
        array $data = []
    ) {
        $this->_scopeConfig     = $scopeConfig;
        $this->_request         = $request;
        $this->_themeFactory    = $themeFactory;
    }

    public function toOptionArray() 
    {
        $store   = $this->_request->getParam('store');
        $themeId = $this->_scopeConfig->getValue( 'design/theme/theme_id', ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $store );
        
        if ($themeId) {
            $theme = $this->getTheme($themeId);
            $parentId = $theme->getParentId();
        }
    
        return [];
    }

    public function getTreeTheme($themeId)
    {
        if ($themeId) {
            $theme = $this->getTheme($themeId);
            $parentId = $theme->getParentId();
            if($parentId) $this->getTreeTheme($themeId);
        }        
    }

    public function getTheme($themeId)
    {
        $model = $this->_themeFactory->create();
        return $model->load($themeId);
    }

    public function getThemeCollection()
    {
        $collection = $this->_themeFactory->getCollection();
        $frontentCollction = $collection->addAreaFilter(\Magento\Framework\App\Area::AREA_FRONTEND);
        return  $frontentCollction;      
    }
 
}
