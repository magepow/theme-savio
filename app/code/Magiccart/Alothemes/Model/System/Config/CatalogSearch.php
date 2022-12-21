<?php
/**
 * Magiccart 
 * @category    Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license     http://www.magiccart.net/license-agreement.html
 * @Author: DOng NGuyen<nguyen@dvn.com>
 * @@Create Date: 2016-01-11 23:15:05
 * @@Modify Date: 2017-08-04 11:13:02
 * @@Function:
 */

namespace Magiccart\Alothemes\Model\System\Config;

class CatalogSearch implements \Magento\Framework\Option\ArrayInterface
{

	const PREFIX_ROOT = ''; 	
    const REPEATER = '*';
    const PREFIX_END = '';

    /**
     * @var \Magento\Catalog\Model\CategoryFactory
     */
    protected $_categoryFactory;

    /**
     * @var \Magento\Catalog\Model\Config\Source\Category
     */
    protected $_category;  

    protected $_request;

    protected $_storeManager;

    protected  $_options = array();

    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Catalog\Model\Config\Source\Category $category
    )
    {
        $this->_request = $request;
        $this->_storeManager = $storeManager;
        $this->_categoryFactory = $categoryFactory;
        $this->_category = $category;
    }
 
    public function toOptionArray($depth=0, $levelSymbol=self::REPEATER)
    {
		if(!$this->_options){
            $rootCategoryId = $this->_storeManager->getStore()->getRootCategoryId();
            $_categories = $this->_categoryFactory->create()->getCategories($rootCategoryId);
            if($_categories){
                // $rootOption = array('label' => $category['label']);
                foreach ($_categories as $_category) {
                    $this->_options[] = array(
                        'label' => self::PREFIX_ROOT .$_category->getName(),
                        'value' => $_category->getEntityId(),
                        'class' => 'item top'
                    );
                    if ($_category->hasChildren()) $this->_getChildOptions($_category->getChildren(), $depth, $levelSymbol);
                }
 
            }
	    }
        return $this->_options;
    }
 
    protected function _getChildOptions($categories, $depth=0, $levelSymbol=self::REPEATER)
    {
        if (is_array($categories) || is_object($categories)){
            foreach ($categories as $category) {
                $level = $category->getLevel();
                if( $depth  && $level > $depth) return;
                $prefix = str_repeat($levelSymbol, ($level -1)) . self::PREFIX_END;
                $this->_options[] = array(
                    'label' => $prefix . $category->getName(),
                    'value' => $category->getEntityId(),
                    'class' => 'item'
                );
                if ($category->hasChildren()) $this->_getChildOptions($category->getChildren(), $depth, $levelSymbol);
            }
        }
    }

}
