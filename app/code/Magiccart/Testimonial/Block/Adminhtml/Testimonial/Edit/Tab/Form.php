<?php
/**
 * Magiccart 
 * @category    Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license     http://www.magiccart.net/license-agreement.html
 * @Author: DOng NGuyen<nguyen@dvn.com>
 * @@Create Date: 2016-01-05 10:40:51
 * @@Modify Date: 2016-03-29 13:54:35
 * @@Function:
 */

namespace Magiccart\Testimonial\Block\Adminhtml\Testimonial\Edit\Tab;

class Form extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\Framework\DataObjectFactory
     */
    protected $_objectFactory;

    /**
     * @var \Magiccart\Testimonial\Model\Testimonial
     */

    protected $_testimonial;

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
    protected $_wysiwygConfig;

    /**
     * Review data
     *
     * @var \Magento\Review\Helper\Data
     */
    protected $_reviewData = null;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Framework\DataObjectFactory $objectFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        \Magento\Review\Helper\Data $reviewData,
        \Magiccart\Testimonial\Model\Testimonial $testimonial,
        array $data = []
    ) {
        $this->_objectFactory = $objectFactory;
        $this->_testimonial = $testimonial;
        $this->_systemStore = $systemStore;
        $this->_wysiwygConfig = $wysiwygConfig;
        $this->_reviewData = $reviewData;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * prepare layout.
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        $this->getLayout()->getBlock('page.title')->setPageTitle($this->getPageTitle());
        return $this;
    }

    /**
     * Prepare form.
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('testimonial');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('magic_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Testimonial Information')]);

        if ($model->getId()) {
            $fieldset->addField('testimonial_id', 'hidden', ['name' => 'testimonial_id']);
        }

        $fieldset->addField('name', 'text',
            [
                'label' => __('Name'),
                'title' => __('Name'),
                'name'  => 'name',
                'required' => true,
            ]
        );

        $fieldset->addField('image', 'image',
            [
                'label' => __('Avatar'),
                'title' => __('Avatar'),
                'name'  => 'image',
                'required' => true,
            ]
        );

        $fieldset->addField('text', 'editor',
            [
                'label' => __('Text'),
                'title' => __('Text'),
                'name'  => 'text',
                'required' => true,
            ]
        );

        $fieldset->addField('company', 'text',
            [
                'label' => __('Company'),
                'title' => __('Company'),
                'name'  => 'company',
                'required' => true,
            ]
        );

        $fieldset->addField('job', 'text',
            [
                'label' => __('Job'),
                'title' => __('Job'),
                'name' => 'job',
                'required' => true,
            ]
        );

        $fieldset->addField('email', 'text',
            [
                'label' => __('Email'),
                'title' => __('Email'),
                'name'  => 'email',
                'required' => true,
            ]
        );

        $fieldset->addField('website', 'text',
            [
                'label' => __('Website'),
                'title' => __('Website'),
                'name'  => 'website',
                'required' => true,
            ]
        );

        $summary = $this->getLayout()->createBlock('Magiccart\Testimonial\Block\Adminhtml\Helper\Renderer\Form\Summary');
        $fieldset->addField('summary_rating', 'note', array(
            'label'     => __('Summary Rating'),
            'text'      => $summary->ratingHtml(),
        ));

        $fieldset->addField('detailed_rating', 'note', array(
            'label'     => __('Detailed Rating'),
            'text'      => $summary->detailedHtml(),
        ));

        /* Check is single store mode */
        if (!$this->_storeManager->isSingleStoreMode()) {
            $field = $fieldset->addField(
                'stores',
                'multiselect',
                [
                    'name' => 'stores[]',
                    'label' => __('Store View'),
                    'title' => __('Store View'),
                    'required' => true,
                    'values' => $this->_systemStore->getStoreValuesForForm(false, true)
                ]
            );
            $renderer = $this->getLayout()->createBlock(
                'Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element'
            );
            $field->setRenderer($renderer);
        } else {
            $fieldset->addField(
                'stores',
                'hidden',
                ['name' => 'stores[]', 'value' => $this->_storeManager->getStore(true)->getId()]
            );
            $model->setStoreId($this->_storeManager->getStore(true)->getId());
        }

        $fieldset->addField('order', 'text',
            [
                'label' => __('Order'),
                'title' => __('Order'),
                'name'  => 'order',
            ]
        );

        $fieldset->addField('status', 'select',
            [
                'label' => __('Status'),
                'title' => __('Status'),
                'name' => 'status',
                'options' => $this->_reviewData->getReviewStatuses(),
            ]
        );

        $form->addValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * @return mixed
     */
    public function getTestimonial()
    {
        return $this->_coreRegistry->registry('testimonial');
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getPageTitle()
    {
        return $this->getTestimonial()->getId()
            ? __("Edit Testimonial '%1'", $this->escapeHtml($this->getTestimonial()->getName())) : __('New Testimonial');
    }

    /**
     * Prepare label for tab.
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('General Information');
    }

    /**
     * Prepare title for tab.
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->getTabLabel();
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }
}
