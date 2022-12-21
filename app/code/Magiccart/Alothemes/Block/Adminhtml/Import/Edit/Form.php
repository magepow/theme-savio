<?php
/**
 * Magiccart 
 * @category    Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license     http://www.magiccart.net/license-agreement.html
 * @Author: DOng NGuyen<nguyen@dvn.com>
 * @@Create Date: 2016-01-05 10:40:51
 * @@Modify Date: 2020-09-15 14:56:16
 * @@Function:
 */

namespace Magiccart\Alothemes\Block\Adminhtml\Import\Edit;

use Magento\Theme\Model\Theme\Collection;
use Magento\Framework\App\Area;

class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @var \Magento\Config\Model\Config\Source\Yesno
     */
    protected $_yesno;

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @var \Magiccart\Alothemes\Model\Import\Theme
     */


    protected $_theme;

    /**
     * @param \Magento\Backend\Block\Template\Context                    $context       
     * @param \Magento\Framework\Registry                                $registry      
     * @param \Magento\Framework\Data\FormFactory                        $formFactory   
     * @param \Magento\Config\Model\Config\Source\Yesno                  $yesno               
     * @param \Magento\Store\Model\System\Store                          $systemStore   
     * @param array                                                      $data          
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Config\Model\Config\Source\Yesno $yesno,
        \Magento\Store\Model\System\Store $systemStore,
        \Magiccart\Alothemes\Model\Import\Theme $theme,
        array $data = []
    ) {
        parent::__construct($context, $registry, $formFactory, $data);
        $this->_yesno = $yesno;

        $this->_systemStore = $systemStore;
        $this->_theme = $theme;
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
                [
                    'data' => [
                    'id' => 'edit_form',
                    'action' => $this->getData('action'),
                    'method' => 'post',
                    'enctype' => 'multipart/form-data'
                    ]
                ]
            );

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Select Theme')]);

        // $themesCollections = \Magento\Framework\App\ObjectManager::getInstance()->create('Magento\Theme\Model\Theme\Collection');
        // $themesCollections->addConstraint(Collection::CONSTRAINT_AREA, Area::AREA_FRONTEND);
        // $themes = [];
        // $themes[""] = __('Select Theme');
        // foreach ($themesCollections as $key => $value) {
        //     $themes[$value->getData('theme_path')] = $value->getData('theme_title');
        // }

        $field = $fieldset->addField(
            'theme_path',
            'select',
            [
                'name' => 'theme_path',
                'label' => __('Theme'),
                'title' => __('Theme'),
                'required' => true,
                'values' => $this->_theme->toOptionArray()
            ]
        );

        $scope    = $this->getRequest()->getParam('store');
        if($scope){
            $scopeId = $this->_storeManager->getStore($scope)->getId();
            $fieldset->addField('scope', 'hidden', array(
                'label'     => __('Scope'),
                'class'     => 'required-entry',
                'required'  => true,
                'name'      => 'scope',
                'value'     => 'stores',
            ));
            $fieldset->addField('scope_id', 'hidden', array(
                'label'     => __('Scope Id'),
                'class'     => 'required-entry',
                'required'  => true,
                'name'      => 'scope_id',
                'value'     => $scopeId,
            ));
        }else {
            $scope   = $this->getRequest()->getParam('website');
            if($scope){
                $scopeId = $this->_storeManager->getWebsite($scope)->getId();
                $fieldset->addField('scope', 'hidden', array(
                    'label'     => __('Scope'),
                    'class'     => 'required-entry',
                    'required'  => true,
                    'name'      => 'scope',
                    'value'     => 'websites',
                ));
                $fieldset->addField('scope_id', 'hidden', array(
                    'label'     => __('Scope Id'),
                    'class'     => 'required-entry',
                    'required'  => true,
                    'name'      => 'scope_id',
                    'value'     => $scopeId,
                ));             
            }

        }

        $fieldset->addField('config', 'checkbox',
            [
                'label' => __('Config'),
                'title' => __('Config'),
                'name' => 'config',
                // 'options' => $this->_yesno->toArray(),
                'value' => 1,
                'checked' => 'checked',
                'after_element_html' => '<small> STORES > Configuration</small>',
            ]
        );

        $fieldset->addField('usewebsite', 'checkbox',
            [
                'label' => __('Use Website'),
                'title' => __('Use Website'),
                'name' => 'usewebsite',
                // 'options' => $this->_yesno->toArray(),
                'value' => 1,
                'checked' => 'checked',
                'after_element_html' => '<small>Use config of websites if same value</small>',
            ]
        );

        $fieldset->addField('page', 'checkbox',
            [
                'label' => __('Pages'),
                'title' => __('Pages'),
                'name' => 'page',
                // 'options' => $this->_yesno->toArray(),
                'value' => 1,
                'checked' => 'checked',
                'after_element_html' => '<small> CONTENT > Pages</small>',
            ]
        );

        $overwrite_block = $fieldset->addField('overwrite_page', 'checkbox',
            [
                'label' => __('Overwrite Existing Pages'),
                'title' => __('Overwrite Existing Pages'),
                'name' => 'overwrite_page',
                // 'options' => $this->_yesno->toArray(),
                'value' => 1,
                'checked' => 'checked',
                'after_element_html' => '<small> Overwrite Existing Pages</small>',
            ]
        );

        $block = $fieldset->addField('block', 'checkbox',
            [
                'label' => __('Blocks'),
                'title' => __('Blocks'),
                'name' => 'block',
                // 'options' => $this->_yesno->toArray(),
                'value' => 1,
                'checked' => 'checked',
                'after_element_html' => '<small> CONTENT > Blocks</small>',
            ]
        );

        $overwrite_block = $fieldset->addField('overwrite_block', 'checkbox',
            [
                'label' => __('Overwrite Existing Blocks'),
                'title' => __('Overwrite Existing Blocks'),
                'name' => 'overwrite_block',
                // 'options' => $this->_yesno->toArray(),
                'value' => 1,
                'checked' => 'checked',
                'after_element_html' => '<small> Overwrite Existing Blocks</small>',
            ]
        );

        $magicmenu = $fieldset->addField('magicmenu', 'checkbox',
            [
                'label' => __('Magicmenu'),
                'title' => __('Magicmenu'),
                'name' => 'magicmenu',
                'value' => 1,
                'checked' => 'checked',
            ]
        );

        $magicmenu->setAfterElementHtml(
            '<small>Magiccart > Magicmenu > Extra Menu</small>
            <style> .field-' . $magicmenu->getHtmlId() . ' {display:none}</style>
            '
        );

        $magicslider = $fieldset->addField('magicslider', 'checkbox',
            [
                'label' => __('Magicslider'),
                'title' => __('Magicslider'),
                'name' => 'magicslider',
                'value' => 1,
                'checked' => 'checked',
            ]
        );

        $magicslider->setAfterElementHtml(
            '<small> Magiccart > Magic Slider</small>
            <style> .field-' . $magicslider->getHtmlId() . ' {display:none}</style>
            '
        );

        $magicproduct = $fieldset->addField('magicproduct', 'checkbox',
            [
                'label' => __('Magicproduct'),
                'title' => __('Magicproduct'),
                'name' => 'magicproduct',
                'value' => 1,
                'checked' => 'checked',
            ]
        );

        $magicproduct->setAfterElementHtml(
            '<small> Magiccart > Magic Product</small>
            <style> .field-' . $magicproduct->getHtmlId() . ' {display:none}</style>
            '
        );

        $magicproductConditions = $fieldset->addField('magicproduct_conditions', 'checkbox',
            [
                'label' => __('Magicproduct Conditions'),
                'title' => __('Magicproduct Conditions'),
                'name' => 'magicproduct_conditions',
                'value' => 1,
            ]
        );

        $magicproductConditions->setAfterElementHtml(
            '<small> Magiccart > Magic Product > Edit > Conditions</small>
            <style> .field-' . $magicproductConditions->getHtmlId() . ' {display:none}</style>
            '
        );

        $lookbook = $fieldset->addField('lookbook', 'checkbox',
            [
                'label' => __('Lookbook'),
                'title' => __('Lookbook'),
                'name' => 'lookbook',
                'value' => 1,
                'checked' => 'checked',
            ]
        );

        $lookbook->setAfterElementHtml(
            '<small> Magiccart > Lookbook</small>
            <style> .field-' . $lookbook->getHtmlId() . ' {display:none}</style>
            '
        );

        $block = $fieldset->addField('action', 'select',
            [
                'label' => __('Action'),
                'title' => __('Action'),
                'name' => 'action',
                'values' =>  array(
                    array('value' => 1, 'label' => __('Install')),
                    array('value' => 0, 'label' => __('Uninstall')),
                ),
                'value' => 1,
            ]
        );

        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}

