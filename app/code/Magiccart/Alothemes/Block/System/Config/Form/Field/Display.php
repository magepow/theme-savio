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
class Display extends \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray
{

    /**
     * @var \Magento\Framework\Data\Form\Element\Factory
     */
    protected $_elementFactory;


    /**
     * @var Yesno
     */
    protected $_enabledRenderer;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Data\Form\Element\Factory $elementFactory
     * @param \Magiccart\Alothemes\Model\System\Config\Block $bock
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Data\Form\Element\Factory $elementFactory,
        array $data = []
    ) {
        $this->_elementFactory = $elementFactory;
        parent::__construct($context, $data);
    }

    /**
     * Initialise form fields
     *
     * @return void
     */
    protected function _prepareToRender()
    {

        $this->addColumn('title', array(
            'label' => __('Title'),
            'style' => 'width:180px',
            'class' => 'title',
        ));

        $this->addColumn('selector', array(
            'label' => __('Selector'),
            'style' => 'width:180px',
            'class' => 'selector',
        ));  

        $this->addColumn('enabled', array(
            'label' => __('Display'),
            'style' => 'width:116px',
            'class' => 'enabled',
            'renderer' => $this->_getEnabledRenderer()
        ));

        // $this->addColumn('status', array(
        //     'label' => __('Status'),
        //     'style' => 'width:116px',
        //     'class' => 'status'
        // ));  

        $this->_addAfter = true;
        $this->_addButtonLabel = __('Add \Tab');

    }

    /**
     * Retrieve group column renderer
     *
     * @return Customergroup
     */
    protected function _getEnabledRenderer($defaulValue='1')
    {
        // $column = $this->getColumns();
        // var_dump($column);
        if (!$this->_enabledRenderer) {
            $this->_enabledRenderer = $this->getLayout()->createBlock(
                \Magiccart\Alothemes\Block\System\Config\Form\Field\Yesno::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
            $this->_enabledRenderer->setClass('block_enabled');
            $this->_enabledRenderer->setValue($defaulValue);
        }
        return $this->_enabledRenderer;
    }

    /**
     * Prepare existing row data object
     *
     * @param \Magento\Framework\DataObject $row
     * @return void
     */
    protected function _prepareArrayRow(\Magento\Framework\DataObject $row)
    {
        $optionExtraAttr = [];
        $value = $row->getData('enabled');
        $optionExtraAttr['option_' . $this->_getEnabledRenderer()->calcOptionHash($value)] = $value;
        $row->setData(
            'option_extra_attrs',
            $optionExtraAttr
        );
    }

   /**
     * Render array cell for prototypeJS template
     *
     * @param string $columnName
     * @return string
     */
    public function renderCellTemplate($columnName)
    {
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

        return parent::renderCellTemplate($columnName) . '<style>.action-add{min-width:65px}</style>';
    }
}
