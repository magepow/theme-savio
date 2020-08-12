<?php

namespace Magiccart\Testimonial\Block;

class Testimonial extends \Magento\Framework\View\Element\Template {

	protected $_testimonialFactory;

	public function __construct(
		\Magento\Framework\View\Element\Template\Context $context,
		\Magiccart\Testimonial\Model\TestimonialFactory $testimonialFactory,
		array $data = []
	) {
		parent::__construct($context, $data);
		
		$this->_testimonialFactory = $testimonialFactory;
	
	}

	/**
	 * Add elements in layout
	 *
	 * @return
	 */
	protected function _prepareLayout() {
		return parent::_prepareLayout();
	}
	

    public function getTestimonials()
    {
        if(!$this->_testimonials){
            $store = $this->_storeManager->getStore()->getStoreId();
            $testimonials = $this->_testimonialCollectionFactory->create()
                        ->addFieldToFilter('stores',array( array('finset' => 0), array('finset' => $store)))
                        ->addFieldToFilter('status', 1);
            $this->_testimonials = $testimonials;
        }
        return $this->_testimonials;
    }
	
}