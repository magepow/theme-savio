<?php
/**
 * Magiccart 
 * @category    Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license     http://www.magiccart.net/license-agreement.html
 * @Author: DOng NGuyen<nguyen@dvn.com>
 * @@Create Date: 2020-04-06 10:10:30
 * @@Modify Date: 2020-04-06 14:28:03
 * @@Function:
 */
namespace Magiccart\Alothemes\Model\Export;
use Magento\Theme\Model\Theme\Collection;
use Magento\Framework\App\Area;

class Theme implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        $themesCollections = \Magento\Framework\App\ObjectManager::getInstance()->create('Magento\Theme\Model\Theme\Collection');
        $themesCollections->addConstraint(Collection::CONSTRAINT_AREA, Area::AREA_FRONTEND);
        $themes = [];
        foreach ($themesCollections as $key => $value) {
            $themes[$value->getData('theme_path')] = $value->getData('theme_title');
        }
        natsort($themes);
        return $themes;
    }
}
