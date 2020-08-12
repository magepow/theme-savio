<?php
/**
 * Magiccart 
 * @category    Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license     http://www.magiccart.net/license-agreement.html
 * @Author: DOng NGuyen<nguyen@dvn.com>
 * @@Create Date: 2016-01-11 23:15:05
 * @@Modify Date: 2020-05-31 15:52:06
 * @@Function:
 */

namespace Magiccart\Testimonial\Model\ResourceModel\Testimonial;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    protected function _construct()
    {
        $this->_init('Magiccart\Testimonial\Model\Testimonial', 'Magiccart\Testimonial\Model\ResourceModel\Testimonial');
    }

    /**
     * set order by order and testimonial_id
     *
     * @return $this
     */
    public function setOrderByTestimonial()
    {
        $this->getSelect()->order(array('order asc', 'testimonial_id desc'));
        return $this;
    }

}
