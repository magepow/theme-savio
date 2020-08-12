<?php

/**
 * @Author: nguyen
 * @Date:   2020-05-31 12:02:38
 * @Last Modified by:   nguyen
 * @Last Modified time: 2020-05-31 12:06:28
 */

namespace Magiccart\Testimonial\Controller\Index;

class View extends \Magiccart\Testimonial\Controller\Index
{
    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }

}
