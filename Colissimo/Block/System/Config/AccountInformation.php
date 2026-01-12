<?php

namespace LaPoste\Colissimo\Block\System\Config;

use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use LaPoste\Colissimo\Model\AccountApi;

class AccountInformation extends Field
{
    private $accountApi;

    public function __construct(
        Context $context,
        AccountApi $accountApi,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->accountApi = $accountApi;
    }

    /**
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $accountInformation = $this->accountApi->getAccountInformation();
        if (empty($accountInformation['contractType'])) {
            return __('No account information available.');
        }

        $args = [
            'contractType'                   => ucfirst(strtolower($accountInformation['contractType'])),
            'outOfHomeContract'              => ucfirst(strtolower($accountInformation['statutHD'])),
            'pickupNeighborRelay'            => $accountInformation['statutPickme'] ? 'Activated' : 'Deactivated',
            'mimosa'                         => empty($accountInformation['mimosaSubscribed']) ? 'Deactivated' : 'Activated',
            'securedShipping'                => $accountInformation['statutCodeBloquant'] ? 'Activated' : 'Deactivated',
            'estimatedShippingDate'          => $accountInformation['statutTunnelCommande'] ? 'Activated' : 'Deactivated',
            'estimatedShippingDateDepotList' => empty($accountInformation['siteDepotList']) ? [] : $accountInformation['siteDepotList'],
            'securedReturn'                  => $accountInformation['optionRetourToken'] ? 'Activated' : 'Deactivated',
            'returnMailbox'                  => $accountInformation['optionRetourBAL'] ? 'Activated' : 'Deactivated',
            'returnPostOffice'               => $accountInformation['optionRetourBP'] ? 'Activated' : 'Deactivated',
            'hazmatCategories'               => $this->accountApi->getHazmatCategories(),
        ];

        $output = '<ul>
			<li>
				<span class="colissimo-account-information-label">' . $this->escapeHtml(__('Contract type:')) . '</span>
				<span class="colissimo-account-information-value">' . $this->escapeHtml($args['contractType']) . '</span>
			</li>
			<li>
				<span class="colissimo-account-information-label">' . $this->escapeHtml(__('Out-of-home contract type:')) . '</span>
				<span class="colissimo-account-information-value">' . $this->escapeHtml($args['outOfHomeContract']) . '</span>
			</li>
			<li>
				<span class="colissimo-account-information-label">' . $this->escapeHtml(__('Pickup neighbor-relay option:')) . '</span>
				<span class="colissimo-account-information-value">' . $this->escapeHtml(__($args['pickupNeighborRelay'])) . '</span>
			</li>
			<li>
				<span class="colissimo-account-information-label">' . $this->escapeHtml(__('Mimosa option:')) . '</span>
				<span class="colissimo-account-information-value">' . $this->escapeHtml(__($args['mimosa'])) . '</span>
			</li>
			<li>
				<span class="colissimo-account-information-label">' . $this->escapeHtml(__('Secured shipping option:')) . '</span>
				<span class="colissimo-account-information-value">' . $this->escapeHtml(__($args['securedShipping'])) . '</span>
			</li>
			<li>
				<span class="colissimo-account-information-label">' . $this->escapeHtml(__('Hazardous materials feature:')) . '</span>
				<span class="colissimo-account-information-value">' . $this->escapeHtml(__(empty($args['hazmatCategories']) ? 'Deactivated' : 'Activated')) . '</span>
			</li>';

        if (!empty($args['hazmatCategories'])) {
            $output .= '<li><ul class="lpc_hazmat_list">';
            foreach ($args['hazmatCategories'] as $category) {
                $output .= '<li>' . $this->escapeHtml(__($category['label'])) . ' : ' . $this->escapeHtml(__($category['active'] ? 'Activated' : 'Deactivated')) . '</li>';
            }
            $output .= '</ul></li>';
        }

        $output .= '<li>
				<span class="colissimo-account-information-label">' . $this->escapeHtml(__('Estimated shipping date option:')) . '</span>
				<span class="colissimo-account-information-value">' . $this->escapeHtml(__($args['estimatedShippingDate'])) . '</span>
			</li>';

        if ('Activated' === $args['estimatedShippingDate'] && !empty($args['estimatedShippingDateDepotList'])) {
            $output .= '<li>
                <span class="colissimo-account-information-label">' . $this->escapeHtml(__('Your Colissimo deposit places:')) . '</span>
                <ul class="lpc_depot_list">';

            foreach ($args['estimatedShippingDateDepotList'] as $depot) {
                $output .= '<li>' . $this->escapeHtml($depot['codeRegate'] . ' - ' . $depot['libellepfc']) . '</li>';
            }

            $output .= '</ul></li>';
        }

        $output .= '<li>
				<span class="colissimo-account-information-label">' . $this->escapeHtml(__('Secured return option:')) . '</span>
				<span class="colissimo-account-information-value">' . $this->escapeHtml(__($args['securedReturn'])) . '</span>
			</li>
			<li>
				<span class="colissimo-account-information-label">' . $this->escapeHtml(__('Return in mailbox option:')) . '</span>
				<span class="colissimo-account-information-value">' . $this->escapeHtml(__($args['returnMailbox'])) . '</span>
			</li>
			<li>
				<span class="colissimo-account-information-label">' . $this->escapeHtml(__('Return in post office option:')) . '</span>
				<span class="colissimo-account-information-value">' . $this->escapeHtml(__($args['returnPostOffice'])) . '</span>
			</li>
		</ul>';

        return $output;
    }

    /**
     * Remove scope label
     *
     * @param AbstractElement $element
     * @return string
     */
    public function render(AbstractElement $element)
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();

        return parent::render($element);
    }
}
