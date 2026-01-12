<?php

namespace LaPoste\Colissimo\Model\System\Message;

use Magento\Framework\Notification\MessageInterface;
use LaPoste\Colissimo\Helper\Data;

class DeprecatedLoginNotification implements MessageInterface
{
    const MESSAGE_IDENTITY = 'lpc_deprecated_login';

    private Data $helperData;

    public function __construct(
        Data $helperData,
    ) {
        $this->helperData = $helperData;
    }

    public function isDisplayed(): bool
    {
        return 'login' === $this->helperData->getAdvancedConfigValue('lpc_general/connectionMode');
    }

    public function getText(): string
    {
        return sprintf(
            __('The login/password connexion type will be removed during 2026 in favor of application key authentication, to increase the security of your account. To avoid any interruption in your deliveries, make sure to generate an application key from the edit page of your %s account then enter it in the "General configuration" section of the advanced Colissimo settings.'),
            '<a target="_blank" href="https://www.colissimo.entreprise.laposte.fr/">Colissimo Box</a>'
        );
    }

    public function getIdentity(): string
    {
        return self::MESSAGE_IDENTITY;
    }

    public function getSeverity(): int
    {
        return self::SEVERITY_MAJOR;
    }
}
