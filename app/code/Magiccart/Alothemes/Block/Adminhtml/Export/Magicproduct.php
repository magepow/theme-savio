<?php

/**
 * Magiccart 
 * @category    Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license     http://www.magiccart.net/license-agreement.html
 * @Author: DOng NGuyen<nguyen@dvn.com>
 * @@Create Date: 2016-01-05 10:40:51
 * @@Modify Date: 2020-04-06 17:16:01
 * @@Function:
 */

namespace Magiccart\Alothemes\Block\Adminhtml\Export;

class Magicproduct extends \Magiccart\Magicproduct\Block\Adminhtml\Product\Grid
{

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setDefaultLimit(100);
    }
    protected function _prepareCollection()
    {
        $collection = $this->_magicproductCollectionFactory->create();
        $this->setCollection($collection);
    }

    protected function _prepareColumns()
    {
        parent::_prepareColumns();
        // $this->removeColumn('status');
        $this->removeColumn('edit');
        $this->_exportTypes = [];
        // $this->addExportType('*/*/menu', __('XML'));
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('magicproduct_id');
        $this->getMassactionBlock()->setFormFieldName('exportIds');

        $theme = \Magento\Framework\App\ObjectManager::getInstance()->create('Magiccart\Alothemes\Model\Export\Theme');
        $themes = $theme->toOptionArray();

        $this->getMassactionBlock()->addItem('export', array(
            'label'    => __('Export'),
            'url'      => $this->getUrl('*/*/magicproduct'),
            'additional' => array(
                'visibility' => array(
                    'name' => 'theme_path',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => __('Theme'),
                    'values' => $themes //$stores
                )
            ),
            'confirm'  => __('Are you sure?')
        ));
        return $this;
    }

    public function toHtml()
    {
        $html = parent::toHtml();
        /*
        $find = $this->escapeHtmlAttr("alothemes/export");
        $replace = $this->escapeHtmlAttr("magicproduct/product");
        $html = str_replace($find, $replace, $html);
        */
        $html =  $this->removeUrl($html);

        return $html;
    }

    public function removeUrl($html)
    {
        $pattern = '/<tr([\s\S]*?)(?:title="(.*?)")([\s\S]*?)?([^>]*)>/';
        return preg_replace_callback(
            $pattern,
            function ($match) {
                return isset($match[2]) ? str_replace($match[2], '#', (string) $match[0]) : $match[0];
            },
            $html
        );
    }
}