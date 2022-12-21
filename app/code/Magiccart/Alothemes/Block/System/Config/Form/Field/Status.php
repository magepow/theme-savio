<?php

/**
 * @Author: nguyen
 * @Date:   2020-09-22 11:14:44
 * @Last Modified by:   nguyen
 * @Last Modified time: 2020-09-26 09:37:11
 */

namespace Magiccart\Alothemes\Block\System\Config\Form\Field;

class Status extends \Magento\Framework\View\Element\Html\Select
{
    /**
     * @var \Magento\Config\Model\Config\Source\Yesno
     */
    private $source;

    public function __construct(
        \Magento\Framework\View\Element\Context $context,
        \Magento\Config\Model\Config\Source\Yesno $source,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->source = $source;
    }

    /**
     * @param $value
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function setInputName($value)
    {
        return $this->setName($value);
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        if (!$this->getOptions()) {
            $this->setOptions($this->source->toOptionArray());
        }

        if (!$this->_beforeToHtml()) {
            return '';
        }               

        $html = '<div name="' . $this->getName() . '" class="admin__actions-switch ' . $this->getClass() . '">';
        $value = $this->getValue();
        $isEnabled = '<%= option_extra_attrs.option_' . self::calcOptionHash($value) . ' == "'.$value.'" %>';
        $checked = ' data-bind="attr:{\'checked\':(' .$isEnabled. ') ? \'checked\' : false}" checked';
        $html .= ' <input id="' . $this->getName() . '" type="checkbox" value="' . $value . '" class="admin__actions-switch-checkbox input-' . $this->getColumnName() . '" '
            . $checked . ' name="' . $this->getName() . '"/>' .
            '<label class="admin__actions-switch-label ' . $this->getColumnName()  . '" for="' . $this->getName() . '" style="width: 70px;font-size:1.4rem;">' .
            '<span class="admin__actions-switch-text" data-text-on="'. __('Enabled').  '" data-text-off="' . __('Disable') . '"></span></label>';
        $html .= '</div>';

        return $html;
    }
}
