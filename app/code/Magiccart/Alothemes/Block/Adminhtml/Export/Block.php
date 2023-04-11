<?php

/**
 * Magiccart 
 * @category    Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license     http://www.magiccart.net/license-agreement.html
 * @Author: DOng NGuyen<nguyen@dvn.com>
 * @@Create Date: 2016-01-05 10:40:51
 * @@Modify Date: 2020-04-06 21:40:25
 * @@Function:
 */

namespace Magiccart\Alothemes\Block\Adminhtml\Export;

use Magento\Theme\Model\Theme\Collection;
use Magento\Framework\App\Area;

use Magiccart\Alothemes\Model\Status;

class Block extends \Magento\Backend\Block\Widget\Grid\Extended
{

    /**
     * @var \Magento\Cms\Model\ResourceModel\Page\CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * @var \Magento\Cms\Model\Page
     */
    protected $_cmsBlock;

    /**
     * @var \Magento\Framework\View\Model\PageLayout\Config\BuilderInterface
     */
    protected $pageLayoutBuilder;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magento\Cms\Model\Page $cmsPage
     * @param \Magento\Cms\Model\ResourceModel\Page\CollectionFactory $collectionFactory
     * @param \Magento\Framework\View\Model\PageLayout\Config\BuilderInterface $pageLayoutBuilder
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Cms\Model\Block $cmsBlock,
        \Magento\Cms\Model\ResourceModel\Block\CollectionFactory $collectionFactory,
        // \Magento\Framework\View\Model\PageLayout\Config\BuilderInterface $pageLayoutBuilder,
        array $data = []
    ) {
        $this->_collectionFactory = $collectionFactory;
        $this->_cmsBlock = $cmsBlock;
        // $this->pageLayoutBuilder = $pageLayoutBuilder;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('cmsBlockGrid');
        $this->setDefaultSort('identifier');
        $this->setDefaultDir('ASC');
        $this->setDefaultLimit(100);
    }

    /**
     * Prepare collection
     *
     * @return \Magento\Backend\Block\Widget\Grid
     */
    protected function _prepareCollection()
    {
        $collection = $this->_collectionFactory->create();
        /* @var $collection \Magento\Cms\Model\ResourceModel\Block\Collection */
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Prepare columns
     *
     * @return \Magento\Backend\Block\Widget\Grid\Extended
     */
    protected function _prepareColumns()
    {

        $this->addColumn('title', ['header' => __('Title'), 'index' => 'title']);

        $this->addColumn('identifier', ['header' => __('URL Key'), 'index' => 'identifier']);


        /**
         * Check is single store mode
         */
        if (!$this->_storeManager->isSingleStoreMode()) {
            $this->addColumn(
                'store_id',
                [
                    'header' => __('Store View'),
                    'index' => 'store_id',
                    'type' => 'store',
                    'store_all' => true,
                    'store_view' => true,
                    'sortable' => false,
                    'filter_condition_callback' => [$this, '_filterStoreCondition']
                ]
            );
        }

        $this->addColumn(
            'is_active',
            [
                'header' => __('Status'),
                'index' => 'is_active',
                'type' => 'options',
                'options' => ['1' => __('Enabled'), '0' => __('Disabled')],
            ]
        );

        $this->addColumn(
            'creation_time',
            [
                'header' => __('Created'),
                'index' => 'creation_time',
                'type' => 'datetime',
                'header_css_class' => 'col-date',
                'column_css_class' => 'col-date'
            ]
        );

        $this->addColumn(
            'update_time',
            [
                'header' => __('Modified'),
                'index' => 'update_time',
                'type' => 'datetime',
                'header_css_class' => 'col-date',
                'column_css_class' => 'col-date'
            ]
        );

        return parent::_prepareColumns();
    }

    /**
     * After load collection
     *
     * @return void
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    /**
     * Filter store condition
     *
     * @param \Magento\Framework\Data\Collection $collection
     * @param \Magento\Framework\DataObject $column
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function _filterStoreCondition($collection, \Magento\Framework\DataObject $column)
    {
        if (!($value = $column->getFilter()->getValue())) {
            return;
        }

        $this->getCollection()->addStoreFilter($value);
    }

    /**
     * Row click url
     *
     * @param \Magento\Framework\DataObject $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', ['block_id' => $row->getId()]);
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('block_id');
        $this->getMassactionBlock()->setFormFieldName('exportIds');

        $theme = \Magento\Framework\App\ObjectManager::getInstance()->create('Magiccart\Alothemes\Model\Export\Theme');
        $themes = $theme->toOptionArray();

        $this->getMassactionBlock()->addItem('export', array(
            'label'    => __('Export'),
            'url'      => $this->getUrl('*/*/block'),
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
        $replace = $this->escapeHtmlAttr("cms/block");
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