<?php
/**
 * Magiccart 
 * @category 	Magiccart 
 * @copyright 	Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license 	http://www.magiccart.net/license-agreement.html
 * @Author: DOng NGuyen<nguyen@dvn.com>
 * @@Create Date: 2016-01-05 10:40:51
 * @@Modify Date: 2016-03-29 09:13:34
 * @@Function:
 */

namespace Magiccart\Testimonial\Controller\Adminhtml;

abstract class Index extends \Magiccart\Testimonial\Controller\Adminhtml\Testimonial
{
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magiccart_Testimonial::testimonial_index');
    }
}
