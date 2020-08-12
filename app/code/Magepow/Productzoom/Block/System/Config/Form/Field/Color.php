<?php

/**
 * @Author: Alex Dong
 * @Date:   2020-07-09 22:24:37
 * @Last Modified by:   Alex Dong
 * @Last Modified time: 2020-07-09 22:24:46
 */

namespace Magepow\Productzoom\Block\System\Config\Form\Field;

class Color extends \Magento\Config\Block\System\Config\Form\Field
{

    public function __construct(
        \Magento\Backend\Block\Template\Context $context, array $data = []
    ) {
        parent::__construct($context, $data);
    }

    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element) 
    {
        $html = $element->getElementHtml();
        $value = $element->getData('value');
    
        $html .= '<script type="text/javascript">
            require(["jquery", "jquery/colorpicker/js/colorpicker"], function ($) {
                $(document).ready(function (e) {
                    var $el = $("#' . $element->getHtmlId() . '");
                    $el.css("backgroundColor", "'. $value .'");

                    // Attach the color picker
                    $el.ColorPicker({
                        color: "'. $value .'",
                        onChange: function (hsb, hex, rgb) {
                            $el.css("backgroundColor", "#" + hex).val("#" + hex);
                        }
                    });
                });
            });
            </script>';

        return $html;
    }

}
