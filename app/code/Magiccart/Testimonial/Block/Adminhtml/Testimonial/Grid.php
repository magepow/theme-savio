<?php
/**
 * Magiccart 
 * @category    Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license     http://www.magiccart.net/license-agreement.html
 * @Author: DOng NGuyen<nguyen@dvn.com>
 * @@Create Date: 2016-01-05 10:40:51
 * @@Modify Date: 2019-01-25 21:06:07
 * @@Function:
 */

namespace Magiccart\Testimonial\Block\Adminhtml\Testimonial;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{

    /**
     * Review data
     *
     * @var \Magento\Review\Helper\Data
     */
    protected $_reviewData = null;

    /**
     * testimonial collection factory.
     *
     * @var \Magiccart\Testimonial\Model\ResourceModel\Testimonial\CollectionFactory
     */
    protected $_testimonialCollectionFactory;


    /**
     * construct.
     *
     * @param \Magento\Backend\Block\Template\Context                         $context
     * @param \Magento\Backend\Helper\Data                                    $backendHelper
     * @param \Magiccart\Testimonial\Model\ResourceModel\Testimonial\CollectionFactory $testimonialCollectionFactory
     * @param array                                                           $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Review\Helper\Data $reviewData,
        \Magiccart\Testimonial\Model\ResourceModel\Testimonial\CollectionFactory $testimonialCollectionFactory,
    
        array $data = []
    ) {

        $this->_reviewData = $reviewData;
        $this->_testimonialCollectionFactory = $testimonialCollectionFactory;

        parent::__construct($context, $backendHelper, $data);
    }

    protected function _construct()
    {
        parent::_construct();
        $this->setId('testimonialGrid');
        $this->setDefaultSort('testimonial_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection()
    {
        $store = $this->getRequest()->getParam('store');
        $collection = $this->_testimonialCollectionFactory->create();
        if($store) $collection->addFieldToFilter('stores',array( array('finset' => 0), array('finset' => $store)));
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * @return $this
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'testimonial_id',
            [
                'header' => __('Testimonial ID'),
                'type' => 'number',
                'index' => 'testimonial_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id',
            ]
        );

        $this->addColumn(
            'name',
            [
                'header' => __('Name'),
                'type' => 'text',
                'index' => 'name',
                'header_css_class' => 'col-name',
                'column_css_class' => 'col-name',
            ]
        );

        $this->addColumn(
            'image',
            [
                'header' => __('Image'),
                'class' => 'xxx',
                'width' => '50px',
                'filter' => false,
                'renderer' => 'Magiccart\Testimonial\Block\Adminhtml\Helper\Renderer\Grid\Image',
            ]
        );

        $this->addColumn('rating_summary', array(
            'header'    => __('Rating'),
            'align'     => 'left',
            'width'     => '80px',
            'index'     => 'rating_summary',
            'type'      => 'options',
            'renderer'  => 'Magiccart\Testimonial\Block\Adminhtml\Helper\Renderer\Grid\Summary',
            'options'   => array(
              1 => '1 star',
              2 => '2 stars',
              3 => '3 stars',
              4 => '4 stars',
              5 => '5 stars',
            ),
        ));

        // if (!$this->_storeManager->isSingleStoreMode()) {
        //     $this->addColumn(
        //         'stores',
        //         [
        //             'header' => __('Store View'),
        //             'index' => 'stores',
        //             'type' => 'store',
        //             'store_all' => true,
        //             'store_view' => true,
        //             'sortable' => false,
        //             'filter_condition_callback' => [$this, '_filterStoreCondition']
        //         ]
        //     );
        // }

        $this->addColumn(
            'order',
            [
                'header' => __('Order'),
                'type' => 'text',
                'index' => 'order',
                'header_css_class' => 'col-order',
                'column_css_class' => 'col-order',
            ]
        );

        $this->addColumn(
            'status',
            [
                'header' => __('Status'),
                'index' => 'status',
                'type' => 'options',
                'options' => $this->_reviewData->getReviewStatuses(),
            ]
        );

        $this->addColumn(
            'edit',
            [
                'header' => __('Edit'),
                'type' => 'action',
                'getter' => 'getId',
                'actions' => [
                    [
                        'caption' => __('Edit'),
                        'url' => ['base' => '*/*/edit'],
                        'field' => 'testimonial_id',
                    ],
                ],
                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
                'header_css_class' => 'col-action',
                'column_css_class' => 'col-action',
            ]
        );
        $this->addExportType('*/*/exportCsv', __('CSV'));
        $this->addExportType('*/*/exportXml', __('XML'));
        $this->addExportType('*/*/exportExcel', __('Excel'));

        return parent::_prepareColumns();
    }

    /**
     * get testimonial vailable option
     *
     * @return array
     */

    /**
     * @return $this
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('testimonial_id');
        $this->getMassactionBlock()->setFormFieldName('testimonial');

        $this->getMassactionBlock()->addItem(
            'delete',
            [
                'label' => __('Delete'),
                'url' => $this->getUrl('testimonial/*/massDelete'),
                'confirm' => __('Are you sure?'),
            ]
        );

        $statuses = $this->_reviewData->getReviewStatuses();

        array_unshift($statuses, ['label' => '', 'value' => '']);
        $this->getMassactionBlock()->addItem(
            'status',
            [
                'label' => __('Change status'),
                'url' => $this->getUrl('testimonial/*/massStatus', ['_current' => true]),
                'additional' => [
                    'visibility' => [
                        'name' => 'status',
                        'type' => 'select',
                        'class' => 'required-entry',
                        'label' => __('Status'),
                        'values' => $statuses,
                    ],
                ],
            ]
        );

        return $this;
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', ['_current' => true]);
    }

    /**
     * get row url
     * @param  object $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl(
            '*/*/edit',
            ['testimonial_id' => $row->getId()]
        );
    }
}
