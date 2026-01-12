<?php

namespace LaPoste\Colissimo\Controller\Checkout;

use LaPoste\Colissimo\Model\CheckoutApi;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use LaPoste\Colissimo\Helper\Data;

class DeliveryDate extends Action
{
    protected $resultJsonFactory;
    protected $checkoutApi;
    protected $helperData;

    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        CheckoutApi $checkoutApi,
        Data $helperData
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->checkoutApi = $checkoutApi;
        $this->helperData = $helperData;

        parent::__construct($context);
    }

    public function execute()
    {
        $request = $this->getRequest();
        $postcode = $request->getParam('postcode');

        $resultJson = $this->resultJsonFactory->create();
        $deliveryDate = $this->checkoutApi->getDeliveryDate($postcode);

        return $resultJson->setData(['deliveryDate' => $deliveryDate]);
    }
}
