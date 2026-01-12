<?php

namespace LaPoste\Colissimo\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class HazmatCategories extends AbstractSource
{
    public const HAZMAT_CATEGORIES = [
        'lpc-cata' => [
            'label'      => 'Category A - CLP hazardous product',
            'max_weight' => 5000,
            'extra_cost' => 0.5,
            'code'       => 'A',
            'id'         => 1,
        ],
        'lpc-catb' => [
            'label'      => 'Category B - ADR/GPE 2 hazardous product',
            'max_weight' => 1000,
            'extra_cost' => 1,
            'code'       => 'B',
            'id'         => 2,
        ],
        'lpc-catc' => [
            'label'      => 'Category C - ADR/GPE 3 hazardous product',
            'max_weight' => 500,
            'extra_cost' => 2,
            'code'       => 'C',
            'id'         => 3,
        ],
        'lpc-catd' => [
            'label'      => 'Category D - Cosmetic hazardous product',
            'max_weight' => 0,
            'extra_cost' => 2,
            'code'       => 'D',
            'id'         => 4,
        ],
        'lpc-cate' => [
            'label'      => 'Category E - Other derogated sensitive product',
            'max_weight' => 0,
            'extra_cost' => 5,
            'code'       => 'E',
            'id'         => 5,
        ],
    ];

    public function getAllOptions(): array
    {
        if ($this->_options === null) {
            $this->_options = [
                [
                    'value' => 0,
                    'label' => __('None'),
                ],
            ];

            foreach (self::HAZMAT_CATEGORIES as $category) {
                $this->_options[] = [
                    'value' => $category['id'],
                    'label' => __($category['label']),
                ];
            }
        }

        return $this->_options;
    }

    public static function getCategorySlugFromId(int $categoryId): ?string
    {
        foreach (self::HAZMAT_CATEGORIES as $slug => $category) {
            if ($category['id'] === $categoryId) {
                return $slug;
            }
        }

        return null;
    }
}
