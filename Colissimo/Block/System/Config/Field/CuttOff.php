<?php

namespace LaPoste\Colissimo\Block\System\Config\Field;

use LaPoste\Colissimo\Helper\Data;
use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Data\Form\Element\AbstractElement;

class CuttOff extends Field
{
    /**
     * @var string
     */
    protected $_template = 'LaPoste_Colissimo::system/config/field/cuttoff.phtml';
    /**
     * @var Data
     */
    protected $helperData;

    /**
     * @param Context $context
     * @param Data    $helperData
     * @param array   $data
     */
    public function __construct(
        Context $context,
        Data $helperData,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->helperData = $helperData;
    }

    /**
     * Return element html
     *
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element): string
    {
        return $this->_toHtml();
    }

    public function getHours(): array
    {
        $hours = [
            'none' => __('No shipment this day'),
        ];

        for ($i = 1; $i < 24; $i ++) {
            $hour = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
            $hours[$i] = $hour;
        }

        return $hours;
    }

    public function getDays(): array
    {
        return Data::DAYS;
    }

    public function getValues(): string
    {
        $cuttOffDates = $this->helperData->getAdvancedConfigValue('lpc_checkout/deliveryDateCuttoffTimes');

        return empty($cuttOffDates) ? '' : $cuttOffDates;
    }
}
