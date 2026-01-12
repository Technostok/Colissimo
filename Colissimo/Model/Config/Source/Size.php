<?php

namespace LaPoste\Colissimo\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class Size implements ArrayInterface
{
    public function toOptionArray(): array
    {
        return [
            ['value' => 'default', 'label' => __('Default')],
            ['value' => '10px', 'label' => '10px'],
            ['value' => '12px', 'label' => '12px'],
            ['value' => '14px', 'label' => '14px'],
            ['value' => '16px', 'label' => '16px'],
            ['value' => '18px', 'label' => '18px'],
            ['value' => '20px', 'label' => '20px'],
            ['value' => '22px', 'label' => '22px'],
        ];
    }
}
