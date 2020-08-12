<?php
/**
 * Magiccart 
 * @category 	Magiccart 
 * @copyright 	Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license 	http://www.magiccart.net/license-agreement.html
 * @Author: DOng NGuyen<nguyen@dvn.com>
 * @@Create Date: 2016-03-04 11:44:03
 * @@Modify Date: 2016-03-29 13:50:19
 * @@Function:
 */
namespace Magiccart\Testimonial\Block\Adminhtml\Helper\Renderer\Grid; 

class Summary extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{

    /**
     * Store manager.
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Testimonial factory.
     *
     * @var \Magestore\Testimonial\Model\TestimonialFactory
     */
    protected $_testimonialFactory;

    /**
     * [__construct description].
     *
     * @param \Magento\Backend\Block\Context              $context
     * @param \Magento\Store\Model\StoreManagerInterface  $storeManager
     * @param \Magento\Cms\Model\BlockFactory $blockFactory
     * @param array                                       $data
     */
    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magiccart\Testimonial\Model\TestimonialFactory $testimonialFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_storeManager = $storeManager;
        $this->_testimonialFactory  = $testimonialFactory;
    }

    public function render(\Magento\Framework\DataObject $row)
    {
        $html = '<div class="field-summary_rating"><span class="rating-box" style="display:block;">';
        $html .= '<span style="display:block; width:'. $row->getData($this->getColumn()->getIndex()) * 20 .'%;" class="rating"></span>';
        $html .= '</span></div>';
        return $html;
    }
}
