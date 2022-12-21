<?php

/**
 * @Author: nguyen
 * @Date:   2021-06-17 09:57:16
 * @Last Modified by:   nguyen
 * @Last Modified time: 2021-06-17 09:57:48
 */

namespace Magiccart\Testimonial\Block;

class Testimonial extends \Magento\Framework\View\Element\Template {

	protected $testimonials;

	protected $testimonialFactory;

	public function __construct(
		\Magento\Framework\View\Element\Template\Context $context,
		\Magiccart\Testimonial\Model\TestimonialFactory $testimonialFactory,
		array $data = []
	) {
		parent::__construct($context, $data);

		$this->testimonialFactory = $testimonialFactory;
	}

	/**
	 * Add elements in layout
	 *
	 * @return
	 */
	protected function _prepareLayout()
	{
		return parent::_prepareLayout();
	}
	
    public function getTestimonials()
    {
        if(!$this->testimonials){
            $store = $this->_storeManager->getStore()->getStoreId();
            $testimonials = $this->testimonialFactory->create()->getCollection()
                        ->addFieldToFilter('stores',array( array('finset' => 0), array('finset' => $store)))
                        ->addFieldToFilter('status', 1);
            $this->testimonials = $testimonials;
        }

        return $this->testimonials;
    }
	
}