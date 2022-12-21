<?php
/**
 * Magiccart 
 * @category  Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license   http://www.magiccart.net/license-agreement.html
 * @Author: DOng NGuyen<nguyen@dvn.com>
 * @@Create Date: 2017-02-05 10:40:51
 * @@Modify Date: 2021-05-14 00:05:21
 * @@Function:
 */

namespace Magiccart\Alothemes\Controller\Product;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\ResultFactory;

class View extends \Magento\Catalog\Controller\Product\View

{

    /**
     * @var \Magento\Catalog\Helper\Product\View
     */
    protected $viewHelper;


    /**
     * Catalog session
     *
     * @var \Magento\Catalog\Model\Session
     */
    protected $_catalogSession;


    /**
     * @var \Magento\Framework\Controller\Result\ForwardFactory
     */

    protected $resultForwardFactory;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */

    protected $resultPageFactory;

    /**
     * Catalog product
     *
     * @var \Magento\Catalog\Helper\Product
     */

    protected $_catalogProduct = null;

    /**
     * Constructor
     *
     * @param Context $context
     * @param \Magento\Catalog\Helper\Product\View $viewHelper
     * @param \Magento\Catalog\Model\Session $catalogSession
     * @param \Magento\Catalog\Helper\Product $catalogProduct
     * @param \Magento\Catalog\Model\Design $catalogDesign
     * @param \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory
     * @param PageFactory $resultPageFactory
     */

    public function __construct(
        Context $context,
        \Magento\Catalog\Helper\Product\View $viewHelper,
        \Magento\Catalog\Model\Session $catalogSession,
        \Magento\Catalog\Helper\Product $catalogProduct,
        \Magento\Catalog\Model\Design $catalogDesign,
        \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory,
        PageFactory $resultPageFactory

    ) {

        $this->_catalogSession = $catalogSession;
        $this->_catalogProduct = $catalogProduct;
        $this->_catalogDesign = $catalogDesign;
        parent::__construct($context, $viewHelper, $resultForwardFactory, $resultPageFactory);

    }



    public function execute()

    {
        if ($this->getRequest()->isAjax()) {

            // Get initial data from request
            $categoryId = (int) $this->getRequest()->getParam('category', false);
            $productId = (int) $this->getRequest()->getParam('id');
            $specifyOptions = $this->getRequest()->getParam('options');

            // Prepare helper and params

            $params = new \Magento\Framework\DataObject();
            $params->setCategoryId($categoryId);
            $params->setSpecifyOptions($specifyOptions);

            try {
                $product = $this->_initProduct();
                // $page = $this->resultPageFactory->create(false, ['isIsolated' => true]);
                $page = $this->resultPageFactory->create();
                // $page->addHandle('catalog_product_view');
                $page->addHandle('catalog_product_view_type_' . $product->getTypeId());
                
                $pageMainTitle = $page->getLayout()->getBlock('page.main.title');
                if ($pageMainTitle) {
                    $pageMainTitle->setPageTitle($product->getName());
                }
                
                $settings = $this->_catalogDesign->getDesignSettings($product);
                $pageConfig = $page->getConfig();
                if ($settings->getCustomDesign()) {
                    $this->_catalogDesign->applyCustomDesign($settings->getCustomDesign());
                }

                // Apply custom page layout
                if ($settings->getPageLayout()) {
                    $pageConfig->setPageLayout($settings->getPageLayout());
                }

                $urlSafeSku = rawurlencode($product->getSku());

                // Load default page handles and page configurations
                if ($params && $params->getBeforeHandles()) {
                    foreach ($params->getBeforeHandles() as $handle) {
                        echo $handle;die;
                        $page->addPageLayoutHandles(
                            ['id' => $product->getId(), 'sku' => $urlSafeSku, 'type' => $product->getTypeId()],
                            $handle
                        );
                    }
                }


                // $page->addHandle('catalog_product_view');
                // $page->addHandle('catalog_product_view_type_' . $product->getTypeId());

                $page->addPageLayoutHandles(
                    ['id' => $product->getId(), 'sku' => $urlSafeSku, 'type' => $product->getTypeId()]
                );

                if ($params && $params->getAfterHandles()) {
                    foreach ($params->getAfterHandles() as $handle) {
                        $page->addPageLayoutHandles(
                            ['id' => $product->getId(), 'sku' => $urlSafeSku, 'type' => $product->getTypeId()],
                            $handle
                        );
                    }
                }


                // Apply custom layout update once layout is loaded
                $update = $page->getLayout()->getUpdate();
                $layoutUpdates = $settings->getLayoutUpdates();
                if ($layoutUpdates) {
                    if (is_array($layoutUpdates)) {
                        foreach ($layoutUpdates as $layoutUpdate) {
                            $update->addUpdate($layoutUpdate);
                        }
                    }
                }

                $controllerClass = $this->_request->getFullActionName();
                if ($controllerClass != 'catalog-product-view') {
                    $pageConfig->addBodyClass('catalog-product-view');
                }
                $pageConfig->addBodyClass('product-' . $product->getUrlKey());

                // $this->viewHelper->prepareAndRender($page, $productId, $this, $params);

                // $page->getLayout()->removeOutputElement('root');

                $product = $page->getLayout()->getOutput();

                $product = str_replace("swatch-options","swatch-options-".$productId, $product);
                $product = str_replace('data-gallery-role="gallery-placeholder"','data-gallery-role="gallery-placeholder-'.$productId.'"',$product);
                $product = str_replace('data-gallery-role=gallery-placeholder','data-gallery-role=gallery-placeholder-'.$productId,$product);
                // $product = str_replace('gallery-placeholder','gallery-placeholder-'.$productId, $product);
                $this->getResponse()->setBody( $product );


            } catch (\Exception $e) {

                return false;

            }

        } else {

            // return parent::execute();

            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $resultRedirect->setUrl($this->_redirect->getRefererUrl());
            return $resultRedirect;

        }

    }

    /**
     * Product view action
     *
     * @return \Magento\Framework\Controller\Result\Forward|\Magento\Framework\Controller\Result\Redirect
     */
    public function execute2()
    {
        // Get initial data from request
        $categoryId = (int) $this->getRequest()->getParam('category', false);
        $productId = (int) $this->getRequest()->getParam('id');
        $specifyOptions = $this->getRequest()->getParam('options');

        if ($this->getRequest()->isPost() && $this->getRequest()->getParam(self::PARAM_NAME_URL_ENCODED)) {
            $product = $this->_initProduct();
            
            if (!$product) {
                return $this->noProductRedirect();
            }
            
            if ($specifyOptions) {
                $notice = $product->getTypeInstance()->getSpecifyOptionMessage();
                $this->messageManager->addNoticeMessage($notice);
            }
            
            if ($this->getRequest()->isAjax()) {
                $this->getResponse()->representJson(
                    $this->_objectManager->get(\Magento\Framework\Json\Helper\Data::class)->jsonEncode([
                        'backUrl' => $this->_redirect->getRedirectUrl()
                    ])
                );
                return;
            }
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setRefererOrBaseUrl();
            return $resultRedirect;
        }

        // Prepare helper and params
        $params = new \Magento\Framework\DataObject();
        $params->setCategoryId($categoryId);
        $params->setSpecifyOptions($specifyOptions);

        // Render page
        try {
            $page = $this->resultPageFactory->create();
            $this->viewHelper->prepareAndRender($page, $productId, $this, $params);
            $product = $page->getLayout()->getOutput();

            $this->getResponse()->setBody( $product );
            // return $page;
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            return $this->noProductRedirect();
        } catch (\Exception $e) {
            $this->_objectManager->get(\Psr\Log\LoggerInterface::class)->critical($e);
            $resultForward = $this->resultForwardFactory->create();
            $resultForward->forward('noroute');
            return $resultForward;
        }
    }

}

