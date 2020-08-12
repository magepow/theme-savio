<?php
/**
 * Magiccart 
 * @category    Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license     http://www.magiccart.net/license-agreement.html
 * @Author: DOng NGuyen<nguyen@dvn.com>
 * @@Create Date: 2016-01-05 10:40:51
 * @@Modify Date: 2016-04-22 16:55:00
 * @@Function:
 */

namespace Magiccart\Testimonial\Controller\Adminhtml\Index;

class MassDelete extends \Magiccart\Testimonial\Controller\Adminhtml\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $testimonialIds = $this->getRequest()->getParam('testimonial');
        if (!is_array($testimonialIds) || empty($testimonialIds)) {
            $this->messageManager->addError(__('Please select testimonial(s).'));
        } else {
            $collection = $this->_testimonialCollectionFactory->create()
                ->addFieldToFilter('testimonial_id', ['in' => $testimonialIds]);
            try {
                foreach ($collection as $item) {
                    $item->delete();
                }
                $this->messageManager->addSuccess(
                    __('A total of %1 record(s) have been deleted.', count($testimonialIds))
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        $resultRedirect = $this->_resultRedirectFactory->create();

        return $resultRedirect->setPath('*/*/');
    }
}
