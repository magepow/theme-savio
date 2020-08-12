<?php

/**
 * @Author: nguyen
 * @Date:   2020-05-31 11:59:46
 * @Last Modified by:   nguyen
 * @Last Modified time: 2020-05-31 12:05:32
 */

namespace Magiccart\Testimonial\Block\Post;

class View extends \Magento\Framework\View\Element\Template
{
    protected $_testimonialFactory;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $_resource;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magiccart\Testimonial\Model\TestimonialFactory $testimonialFactory,
        \Magento\Framework\App\ResourceConnection $resource,
        array $data = []
        ) {
        $this->_testimonialFactory = $testimonialFactory;
        $this->_coreRegistry = $registry;
        $this->_resource = $resource;
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
