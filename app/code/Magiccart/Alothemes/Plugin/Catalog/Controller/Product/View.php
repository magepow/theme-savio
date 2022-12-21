<?php

/**
 * @Author: nguyen
 * @Date:   2020-10-15 20:22:34
 * @Last Modified by:   nguyen
 * @Last Modified time: 2020-10-16 10:38:12
 */

namespace Magiccart\Alothemes\Plugin\Catalog\Controller\Product;

use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class View
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    private $request;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * Catalog Design
     *
     * @var \Magento\Catalog\Model\Design
     */

    private $_catalogDesign;


    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var \Magiccart\Alothemes\Helper\Data
     */
    protected $helper;

    /**
     * @param \Magento\Framework\App\RequestInterface         $request
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\Catalog\Model\Design      			  $catalogDesign
     * @param \Magento\Store\Model\StoreManagerInterface      $storeManager
     * @param \Magiccart\Alothemes\Helper\Data      		  $helper
     */
    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Catalog\Model\Design $catalogDesign,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magiccart\Alothemes\Helper\Data $helper
    ) {
        $this->request 			 = $request;
        $this->productRepository = $productRepository;
        $this->_catalogDesign 	 = $catalogDesign;
        $this->storeManager 	 = $storeManager;
        $this->helper 	 		 = $helper;
    }

    public function afterExecute(\Magento\Catalog\Controller\Product\View $subject, $resultPage)
    {
        if ($resultPage instanceof ResultInterface)
        {
            $productId = (int) $this->request->getParam('id');
            if ($productId)
            {
                try {
                    $product = $this->productRepository->getById($productId, false, $this->storeManager->getStore()->getId());
	                $settings = $this->_catalogDesign->getDesignSettings($product);
	                if ($settings->getPageLayout()) {
	                	// use private custom page layout
	                    return $resultPage;
	                }

	                $aloConfig = $this->helper->getConfigModule('product_page/page_layout');
	                // Apply alo setting page layout
	                if ($aloConfig) {
	                	$resultPage->getConfig()->setPageLayout($aloConfig);
	                }

                } catch (NoSuchEntityException $e) {
                    // Add you exception message here.
                }
            }
        }
        return $resultPage;
    }
}