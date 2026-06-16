<?php

namespace Domain\Invoicing\Models;

use Laravel\LaravelDDD\Models\DomainModel;

class Invoice extends DomainModel
{
    protected static $secret = null;

    public static function setSecret($secret): void
    {
        self::$secret = $secret;
    }

    public static function getSecret(): ?string
    {
        return self::$secret;
    }
}
