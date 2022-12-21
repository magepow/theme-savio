<?php

/**
 * @Author: nguyen
 * @Date:   2020-05-31 11:59:46
 * @Last Modified by:   nguyen
 * @Last Modified time: 2021-06-17 10:01:02
 */

namespace Magiccart\Testimonial\Block\Post;

class View extends \Magento\Framework\View\Element\Template
{
    protected $testimonialFactory;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magiccart\Testimonial\Model\TestimonialFactory $testimonialFactory,
        array $data = []
        ) {
        $this->testimonialFactory = $testimonialFactory;
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data); 
    }

    public function getTestimonial()
    {
        return $this->_coreRegistry->registry('current_testimonial');
    }

    public function getImage($object)
    {
        $resizedURL = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $object->getImage();
        return $resizedURL;
    }

}
