<?php

/**
 * @Author: Alex Dong
 * @Date:   2020-07-09 22:24:37
 * @Last Modified by:   nguyen
 * @Last Modified time: 2020-07-10 13:51:02
 */

namespace Magepow\Productzoom\Block\System\Config\Form\Field;

class Position extends \Magento\Config\Block\System\Config\Form\Field
{

    private $assetRepository;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context, array $data = []
    ) {
        $this->assetRepository = $context->getAssetRepository();
        parent::__construct($context, $data);
    }

    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element) 
    {
        $html  = $element->getElementHtml();
        $value = $element->getData('value');
        
        $asset = $this->assetRepository->createAsset('Magepow_Productzoom::images/window-positions.png');
        $html .= '<div style="position: relative; margin-top:10px"><img id="window-positions" src="' . $asset->getUrl() . '" alt="" border="0"></div>';
        return $html;
    }

}
