<?php

namespace LaPoste\Colissimo\Block\System\Config\Field;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class DateText extends Field
{
    protected function _getElementHtml(AbstractElement $element)
    {
        $element->setData('placeholder', __('Delivery expected on {date}'));

        return parent::_getElementHtml($element);
    }
}
