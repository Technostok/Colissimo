<?php

namespace LaPoste\Colissimo\Block\System\Config\Field;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Data\Form\Element\AbstractElement;
use LaPoste\Colissimo\Model\AccountApi;

class Hazmat extends Field
{
    protected $_template = 'LaPoste_Colissimo::system/config/field/hazmat.phtml';
    private AccountApi $accountApi;

    /**
     * @param Context    $context
     * @param AccountApi $accountApi
     * @param array      $data
     */
    public function __construct(Context $context, AccountApi $accountApi, array $data = [])
    {
        parent::__construct($context, $data);

        $this->accountApi = $accountApi;
    }

    /**
     * Return element html
     *
     * @param AbstractElement $element
     *
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element): string
    {
        return $this->_toHtml();
    }

    /**
     * Remove scope label
     */
    public function render(AbstractElement $element): string
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();

        return parent::render($element);
    }

    public function getHazmatData(): array
    {
        return [
            'hazmatCategories' => $this->accountApi->getHazmatCategories(),
        ];
    }
}
