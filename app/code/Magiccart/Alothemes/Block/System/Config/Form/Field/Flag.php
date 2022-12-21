<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magiccart\Alothemes\Block\System\Config\Form\Field;

use Magiccart\Alothemes\Model\Status;
/**
 * Backend system config array field renderer
 */
class Flag extends \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray
{

    /**
     * @var \Magento\Framework\Data\Form\Element\Factory
     */
    protected $_elementFactory;

    /**
     * @var \Magento\Framework\View\Design\Theme\LabelFactory
     */

    protected $_store;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Data\Form\Element\Factory $elementFactory
     * @param \Magento\Framework\View\Design\Theme\LabelFactory $labelFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Data\Form\Element\Factory $elementFactory,
        \Magento\Store\Model\System\Store $store,
        array $data = []
    ) {
        $this->_elementFactory = $elementFactory;
        $this->_store = $store;
        parent::__construct($context, $data);
    }

    /**
     * Initialise form fields
     *
     * @return void
     */
    protected function _construct()
    {

        $this->addColumn('store', [
                'label' => __('Store Name'),
                'style' => 'width:116px',
                'class' => 'store'
            ]
        );  

        $this->addColumn('flag', [
            'label' => __('Flag'),
            'style' => 'width:116px',
            'class' => 'flag'
            ]
        );  

        // $this->addColumn('status', [
        //     'label' => __('Status'),
        //     'style' => 'width:116px',
        //     'class' => 'status'
        //     ]
        // );  

        $this->_addAfter = true;
        $this->_addButtonLabel = __('Add \Flag');

        parent::_construct();
    }

   /**
     * Render array cell for prototypeJS template
     *
     * @param string $columnName
     * @return string
     */
    public function renderCellTemplate($columnName)
    {
        if ($columnName == 'store' && isset($this->_columns[$columnName])) {
            $options = $this->_store->toOptionArray();
            $element = $this->_elementFactory->create('select');
            $element->setForm(
                $this->getForm()
            )->setName(
                $this->_getCellInputElementName($columnName)
            )->setHtmlId(
                $this->_getCellInputElementId('<%- _id %>', $columnName)
            )->setValues(
                $options
            );
            $style = '<style>#'. $element->getHtmlId() .'{min-width:185px}</style>';
            return str_replace("\n", '', $element->getElementHtml()) . $style ;
        }

        if ($columnName == 'flag' && isset($this->_columns[$columnName])) {
            $element = $this->_elementFactory->create('image');
            $element->setForm(
                $this->getForm()
            )->setName(
                $this->_getCellInputElementName($columnName)
            )->setHtmlId(
                $this->_getCellInputElementId('<%- _id %>', $columnName)
            );
            $style = '<style>#'. $element->getHtmlId() .'{min-width:185px}</style>';
            return str_replace("\n", '', $element->getElementHtml()) . $style ;
        }

        if ($columnName == 'status' && isset($this->_columns[$columnName])) {
            $options = Status::getOptionArray();
            $element = $this->_elementFactory->create('select');
            $element->setForm(
                $this->getForm()
            )->setName(
                $this->_getCellInputElementName($columnName)
            )->setHtmlId(
                $this->_getCellInputElementId('<%- _id %>', $columnName)
            )->setValues(
                $options
            )->setValue(
                1
            );
            $style = '<style>#'. $element->getHtmlId() .'{min-width:120px}</style>';
            return str_replace("\n", '', $element->getElementHtml()) . $style ;
        }

        return parent::renderCellTemplate($columnName);
    }
}
