<?php

namespace LaPoste\Colissimo\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class DateFormat implements ArrayInterface
{
    protected $helperData;

    public function __construct(
        \LaPoste\Colissimo\Helper\Data $helperData,
    ) {
        $this->helperData = $helperData;
    }

    public function toOptionArray(): array
    {
        return [
            ['value' => 'full', 'label' => $this->helperData->translateDate(date(__('l, F j')))],
            ['value' => 'simple', 'label' => $this->helperData->translateDate(date(__('F j')))],
            ['value' => 'short', 'label' => $this->helperData->translateDate(date(__('M j')))],
            ['value' => 'm/d/Y', 'label' => date('m/d/Y')],
            ['value' => 'd/m/Y', 'label' => date('d/m/Y')],
            ['value' => 'Y-m-d', 'label' => date('Y-m-d')],
        ];
    }
}
