<?php

namespace LaPoste\Colissimo\Api;

interface CheckoutApi
{
    public function getDeliveryDate(string $postCode): ?string;
}
