<?php

namespace LaPoste\Colissimo\Observer;

use LaPoste\Colissimo\Helper\Data;
use LaPoste\Colissimo\Helper\Shipment;
use LaPoste\Colissimo\Logger\Colissimo as Logger;
use LaPoste\Colissimo\Model\Carrier\Colissimo;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Registry;
use Magento\Shipping\Model\Shipping\LabelGenerator;
use Magento\Backend\Model\Auth\Session;

class GenerateLabelOnOrderStateChange implements ObserverInterface
{
    /**
     * @var Data
     */
    protected $helperData;
    /**
     * @var Logger
     */
    protected $logger;
    /**
     * @var RequestInterface
     */
    protected $request;
    /**
     * @var LabelGenerator
     */
    protected $labelGenerator;
    /**
     * @var Shipment
     */
    protected $shipmentHelper;
    /**
     * @var Session
     */
    protected $authSession;

    protected Registry $registry;

    /**
     * GenerateLabelOnOrderStateChange constructor.
     *
     * @param Data             $helperData
     * @param Logger           $logger
     * @param RequestInterface $request
     * @param LabelGenerator   $labelGenerator
     * @param Shipment         $shipmentHelper
     * @param Session          $authSession
     * @param Registry         $registry
     */
    public function __construct(
        Data $helperData,
        Logger $logger,
        RequestInterface $request,
        LabelGenerator $labelGenerator,
        Shipment $shipmentHelper,
        Session $authSession,
        Registry $registry
    ) {
        $this->helperData = $helperData;
        $this->logger = $logger;
        $this->request = $request;
        $this->labelGenerator = $labelGenerator;
        $this->shipmentHelper = $shipmentHelper;
        $this->authSession = $authSession;
        $this->registry = $registry;
    }

    public function execute(Observer $observer)
    {
        try {
            if ($this->helperData->isUsingColiShip()) {
                return $this;
            }

            $order = $observer->getEvent()->getOrder();

            $orderStatusOption = $this->helperData->getAdvancedConfigValue(
                'lpc_labels/orderStatusForGeneration',
                $order->getStoreId()
            );
            if (empty($orderStatusOption)) {
                return $this;
            }

            $orderStatusesForGeneration = explode(',', $orderStatusOption);

            $orderStatusesForGeneration = array_filter($orderStatusesForGeneration, function ($v) {
                return !empty($v);
            });

            if (
                empty($orderStatusesForGeneration)
                || !($order instanceof AbstractModel)
                || $order->getIsVirtual()
                || Colissimo::CODE !== $order->getShippingMethod(true)->getCarrierCode()
                || !in_array($order->getStatus(), $orderStatusesForGeneration)
            ) {
                return $this;
            }

            // the label should automatically be generated
            $this->logger->info(
                'Automatically generating label',
                [
                    'order_id'           => $order->getId(),
                    'order_increment_id' => $order->getIncrementId(),
                    'status'             => $order->getStatus(),
                ]
            );

            if ($order->canShip()) {
                // We ensure that this user exists because Magento uses it without checking in createShipment
                $admin = $this->authSession->getUser();
                if (empty($admin)) {
                    return $this;
                }

                // generate the whole shipment
                $shipment = $this->shipmentHelper->createShipment($order);

                // trigger a label generation for this shipment
                $this->generateLabel($shipment);

                $this->logger->info(
                    'Label automatically generated',
                    [
                        'order_id'           => $order->getId(),
                        'order_increment_id' => $order->getIncrementId(),
                    ]
                );
            } else {
                $this->logger->warning(
                    'Label not automatically generated because not order canShip',
                    [
                        'order_id'           => $order->getId(),
                        'order_increment_id' => $order->getIncrementId(),
                    ]
                );
            }
        } catch (\LocalizedException|\Exception $e) {
            $this->logger->error('An error occurred!', ['e' => $e]);
        }

        return $this;
    }

    protected function generateLabel(\Magento\Sales\Model\Order\Shipment $shipment): void
    {
        $packages = $this->shipmentHelper->shipmentToPackages($shipment);
        $this->request->setParam('packages', $packages);

        try {
            $this->registry->register(Colissimo::AUTOMATIC_LABEL_GENERATION, true);
            $this->labelGenerator->create($shipment, $this->request);
            $this->registry->unregister(Colissimo::AUTOMATIC_LABEL_GENERATION);
        } catch (\LocalizedException $e) {
            $this->logger->error('An error occurred while generating label!', ['e' => $e]);
        }

        $shipment->save();
    }
}
