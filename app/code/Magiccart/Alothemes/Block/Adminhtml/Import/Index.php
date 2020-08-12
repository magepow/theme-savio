<?php
/**
 * Magiccart 
 * @category    Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license     http://www.magiccart.net/license-agreement.html
 * @Author: DOng NGuyen<nguyen@dvn.com>
 * @@Create Date: 2016-01-05 10:40:51
 * @@Modify Date: 2016-04-19 22:02:44
 * @@Function:
 */

namespace Magiccart\Alothemes\Block\Adminhtml\Import;

class Index extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Initialize cms page edit block
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_blockGroup = 'Magiccart_Alothemes';
        $this->_controller = 'adminhtml_import';
        $this->updateButton('save', 'label', __('Submit'));
        // $this->updateButton('save', 'onclick', 'deleteConfirm(\'' . __(
        //             'Are you sure you want to do this?'
        //         ) . '\')');

        $this->removeButton('reset');
        $this->removeButton('back');
        
        // $this->addButton(
        //     'system',
        //     [
        //         'label' => __('Export Configuration'),
        //         'onclick' => 'setLocation(\'' . $this->getUrl('*/export/system') . '\')',
        //         'class' => 'system primary'
        //     ],
        //     -1
        // );

    }

    /**
     * Retrieve text for header element depending on loaded page
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        return __('System Export');
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }


    /**
     * Prepare layout
     *
     * @return \Magento\Framework\View\Element\AbstractBlock
     */
    protected function _prepareLayout()
    {
        return parent::_prepareLayout();
    }
}

