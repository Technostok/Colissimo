<?php
declare(strict_types = 1);

namespace LaPoste\Colissimo\Ui\DataProvider\Bordereau;

use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider;

class GridDataProvider extends DataProvider
{
    /**
     * Returns the data for the UI grid
     *
     * @return array
     */
    public function getData(): array
    {
        $data = parent::getData();

        if (isset($data['items'])) {
            foreach ($data['items'] as &$item) {
                // Remove the BLOB column to prevent JSON serialization errors
                unset($item['delivery_slip']);
            }
        }

        return $data;
    }
}
