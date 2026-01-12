<?php

namespace LaPoste\Colissimo\Plugin;

use Magento\Catalog\Ui\DataProvider\Product\Form\ProductDataProvider;
use LaPoste\Colissimo\Model\AccountApi;

class ProductDataProviderPlugin
{
    private AccountApi $accountApi;

    public function __construct(AccountApi $accountApi)
    {
        $this->accountApi = $accountApi;
    }

    public function afterGetMeta(ProductDataProvider $subject, array $meta): array
    {
        if (!$this->accountApi->isHazmatOptionActive() && isset($meta['product-details']['children']['container_lpc_hazmat_code'])) {
            unset($meta['product-details']['children']['container_lpc_hazmat_code']);
        }

        return $meta;
    }
}
