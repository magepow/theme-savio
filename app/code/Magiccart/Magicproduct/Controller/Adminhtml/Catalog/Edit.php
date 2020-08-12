<?php
/**
 * Magiccart 
 * @category    Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license     http://www.magiccart.net/license-agreement.html
 * @Author: DOng NGuyen<nguyen@dvn.com>
 * @@Create Date: 2016-01-05 10:40:51
 * @@Modify Date: 2017-07-18 17:40:49
 * @@Function:
 */

namespace Magiccart\Magicproduct\Controller\Adminhtml\Catalog;

class Edit extends \Magiccart\Magicproduct\Controller\Adminhtml\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('magicproduct_id');
        $storeViewId = $this->getRequest()->getParam('store');
        $model = $this->_magicproductFactory->create();

        if ($id) {
            $model->setStoreViewId($storeViewId)->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This Catalog Tabs no longer exists.'));
                $resultRedirect = $this->_resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');
            }else {
                $tmp = @unserialize($model->getConfig());
                if(is_array($tmp)){
                    unset($tmp['form_key']);
                    unset($tmp['magicproduct_id']);
                    $model->addData($tmp);
                }
            }
        }

        $data = $this->_getSession()->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        $model->setData('media_attributes', array());

        $this->_coreRegistry->register('magicproduct', $model);
        $this->_coreRegistry->register('current_product', $model, 1);
        $this->_coreRegistry->register('product', $model, 1);
        $resultPage = $this->_resultPageFactory->create();

        return $resultPage;
    }
}
