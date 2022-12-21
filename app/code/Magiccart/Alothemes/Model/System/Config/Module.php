<?php
/**
 * Magiccart 
 * @category    Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license     http://www.magiccart.net/license-agreement.html
 * @Author: DOng NGuyen<nguyen@dvn.com>
 * @@Create Date: 2020-09-14 18:57:54
 * @@Modify Date: 2020-09-14 19:02:17
 * @@Function:
 */

namespace Magiccart\Alothemes\Model\System\Config;

class Module implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var ModuleList
     */
    private $moduleList;

    /**
     * ConfigOption constructor.
     * @param ModuleList $moduleList
     */
    public function __construct(
        \Magento\Framework\Module\ModuleList $moduleList
    )
    {
        $this->moduleList = $moduleList;
    }

    public function toOptionArray()
    {
        $modules = $this->getAllModules();
        $options = [];
        foreach($modules as $module){
            $options[] = ['value' => $module, 'label' => $module];
        }
        return $options;
    }

    public function getAllModules()
    {
        return $this->moduleList->getNames();
    }
}