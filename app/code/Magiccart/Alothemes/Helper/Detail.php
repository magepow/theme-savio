<?php
/**
 * Magiccart 
 * @category    Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license     http://www.magiccart.net/license-agreement.html
 * @Author: DOng NGuyen<nguyen@dvn.com>
 * @@Create Date: 2017-08-29 18:48:40
 * @@Modify Date: 2017-08-31 20:34:17
 * @@Function:
 */

namespace Magiccart\Alothemes\Helper;

class Detail extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $_coreRegistry;   
    protected $productRepository; 
     public function __construct(
            \Magento\Framework\Registry $coreRegistry,
            \Magento\Catalog\Api\ProductRepositoryInterface $productRepository

         )
    {
        $this->_coreRegistry = $coreRegistry;
        $this->productRepository = $productRepository;
    }
  public function getNextProduct()
    {
        $prodId = $this->_coreRegistry->registry('current_product')->getId();
        $catArray = $this->_coreRegistry->registry('current_category');
        if($catArray){
            $catArray = $catArray->getProductsPosition();
            $keys = array_flip(array_keys($catArray));
            $values = array_keys($catArray);
            $productId = $values[$keys[$prodId]+1];
            
            
        }else {
            $productId = $prodId + 1;
        }
        $product = $this->productRepository->getById($productId);
        return $product;
    }
    public function getPreviousProduct()
    {
        $prodId = $this->_coreRegistry->registry('current_product')->getId();
        $catArray = $this->_coreRegistry->registry('current_category');
        if($catArray){
            $catArray = $catArray->getProductsPosition();
            $keys = array_flip(array_keys($catArray));
            $values = array_keys($catArray);
            $productId = $values[$keys[$prodId]-1];
            return $product;
        }else {
            $productId = $prodId - 1;
        }
        $product = $this->productRepository->getById($productId);
        return $product;
    }
}
