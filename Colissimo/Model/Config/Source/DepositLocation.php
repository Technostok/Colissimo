<?php

namespace LaPoste\Colissimo\Model\Config\Source;

use LaPoste\Colissimo\Model\AccountApi;
use Magento\Framework\Option\ArrayInterface;

class DepositLocation implements ArrayInterface
{
    private AccountApi $accountApi;

    public function __construct(
        AccountApi $accountApi,
    ) {
        $this->accountApi = $accountApi;
    }

    public function toOptionArray(): array
    {
        $accountInformation = $this->accountApi->getAccountInformation();

        if (empty($accountInformation['siteDepotList'])) {
            return [];
        }

        $depositLocations = [];
        foreach ($accountInformation['siteDepotList'] as $siteDepot) {
            $depositLocations[] = ['value' => $siteDepot['codeRegate'], 'label' => $siteDepot['libellepfc']];
        }

        return $depositLocations;
    }
}
