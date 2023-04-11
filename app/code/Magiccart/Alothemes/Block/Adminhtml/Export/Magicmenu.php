<?php

/**
 * Magiccart 
 * @category    Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license     http://www.magiccart.net/license-agreement.html
 * @Author: DOng NGuyen<nguyen@dvn.com>
 * @@Create Date: 2016-01-05 10:40:51
 * @@Modify Date: 2020-04-06 17:13:48
 * @@Function:
 */

namespace Magiccart\Alothemes\Block\Adminhtml\Export;

class Magicmenu extends \Magiccart\Magicmenu\Block\Adminhtml\Extra\Grid
{

    protected function _prepareColumns()
    {
        parent::_prepareColumns();
        // $this->removeColumn('status');
        $this->removeColumn('edit');
        $this->_exportTypes = [];
        // $this->addExportType('*/*/menu', __('XML'));
    }

    // protected function _prepareMassaction()
    // {
    //     $this->setMassactionIdField('entity_id');
    //     $this->getMassactionBlock()->setFormFieldName('magicmenu');

    //     $this->getMassactionBlock()->addItem(
    //         'delete',
    //         [
    //             'label' => __('Delete'),
    //             'url' => $this->getUrl('magicmenu/*/massDelete'),
    //             'confirm' => __('Are you sure?'),
    //         ]
    //     );

    //     $statuses = Status::getAvailableStatuses();

    //     array_unshift($statuses, ['label' => '', 'value' => '']);
    //     $this->getMassactionBlock()->addItem(
    //         'status',
    //         [
    //             'label' => __('Change status'),
    //             'url' => $this->getUrl('magicmenu/*/massStatus', ['_current' => true]),
    //             'additional' => [
    //                 'visibility' => [
    //                     'name' => 'status',
    //                     'type' => 'select',
    //                     'class' => 'required-entry',
    //                     'label' => __('Status'),
    //                     'values' => $statuses,
    //                 ],
    //             ],
    //         ]
    //     );

    //     return $this;
    // }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('magicmenu_id');
        $this->getMassactionBlock()->setFormFieldName('exportIds');

        $theme = \Magento\Framework\App\ObjectManager::getInstance()->create('Magiccart\Alothemes\Model\Export\Theme');
        $themes = $theme->toOptionArray();

        $this->getMassactionBlock()->addItem('export', array(
            'label'    => __('Export'),
            'url'      => $this->getUrl('*/*/magicmenu'),
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
        $replace = $this->escapeHtmlAttr("magicmenu/extra");
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