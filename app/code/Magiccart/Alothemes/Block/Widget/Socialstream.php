<?php
/**
 * Magiccart 
 * @category 	Magiccart 
 * @copyright 	Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license 	http://www.magiccart.net/license-agreement.html
 * @Author: DOng NGuyen<nguyen@dvn.com>
 * @@Create Date: 2016-01-05 10:40:51
 * @@Modify Date: 2016-06-02 11:55:11
 * @@Function:
 */

namespace Magiccart\Alothemes\Block\Widget;
// use Magento\Framework\App\Filesystem\DirectoryList;

class Socialstream extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{

    public $_sysCfg;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    ) {

        $this->_sysCfg= (object) $context->getScopeConfig()->getValue(
            'alosocial',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        parent::__construct($context, $data);
    }

}
