<?php

namespace Magiccart\Testimonial\Block\Post;

class Testimonial extends \Magento\Framework\View\Element\Template {

	protected $_scopeConfig;
	protected $_testimonialFactory;
	protected $_customerSession;

	/**
	 * @param \Magento\Framework\View\Element\Template\Context $context 
	 * @param \Magiccart\Testimonial\Model\testimonialFactory $testimonialFactory
	 * @param \Magento\Customer\Model\SessionFactory $customerSession [description]
	 * @param array $data
	 */
	public function __construct(
		\Magento\Framework\View\Element\Template\Context $context,
		\Magiccart\Testimonial\Model\TestimonialFactory $testimonialFactory,
		\Magento\Customer\Model\SessionFactory $customerSession,
		array $data = []
	) {
		$this->_customerSession = $customerSession->create();
		parent::__construct($context, $data);
		
		$this->_testimonialFactory = $testimonialFactory;
	
		$this->_scopeConfig = $context->getScopeConfig();

		$this->pageConfig->getTitle()->set(__('Submit Your Testimonial'));
	}

	public function getCustomerSession(){
		return $this->_customerSession;
	}
	
	public function getStoreId()
	{
		return $this->_storeManager->getStore()->getId();
	}
	
	/**
	 * @return
	 */
	public function getTestimonial() {
		$store = $this->_storeManager->getStore()->getId();
		$collection = $this->_testimonialFactory->create()->getCollection()
			->addFieldToFilter('status', 1)
			->addFieldToFilter('stores',array( array('finset' => 0), array('finset' => $store)));
		$collection->setOrderByTestimonial();
		return $collection;
	}
	
	public function getConfig($config)
	{
		return $this->_scopeConfig->getValue('testimonial/general/'.$config, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
	}
	
	public function getIdStore()
	{
		return $this->_storeManager->getStore()->getId();
	}
	
	/**
	 * @return
	 */
	public function getMediaFolder() {
		$media_folder = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
		return $media_folder;
	}

	/**
	 * @return
	 */
	protected function _toHtml() {
		$store = $this->_storeManager->getStore()->getId();
		if ($this->_scopeConfig->getValue('testimonial/general/enabled', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store)) {
			return parent::_toHtml();
		}
		return '';
	}

	/**
	 * Add elements in layout
	 *
	 * @return
	 */
	protected function _prepareLayout() {
		return parent::_prepareLayout();
	}
	
	
	public function getFormAction()
    {
        return $this->getUrl('testimonial/index/post', ['_secure' => true]);
    }
}