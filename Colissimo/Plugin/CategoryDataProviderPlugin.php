<?php

namespace LaPoste\Colissimo\Plugin;

use Magento\Catalog\Model\Category\DataProvider;
use LaPoste\Colissimo\Model\AccountApi;

class CategoryDataProviderPlugin
{
    private AccountApi $accountApi;

    public function __construct(AccountApi $accountApi)
    {
        $this->accountApi = $accountApi;
    }

    public function afterGetMeta(DataProvider $subject, array $meta): array
    {
        if (!$this->accountApi->isHazmatOptionActive() && isset($meta['content']['children']['lpc_hazmat_code_category'])) {
            $meta['content']['children']['lpc_hazmat_code_category']['arguments']['data']['config']['visible'] = false;
        }

        return $meta;
    }
}
