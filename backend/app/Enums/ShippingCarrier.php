<?php

namespace App\Enums;

enum ShippingCarrier: string
{
    case POST        = 'post';         // Post office
    case TIPAX       = 'tipax';        // Tipax
    case SNAPP_BOX   = 'snapp_box';    // Snapp Box
    case ALOPEYK     = 'alopeyk';      // Alopeyk
    case IN_PERSON   = 'in_person';    // Personal delivery


    public function label(): string
    {
        return match($this) {
            self::POST        => 'Post Office',
            self::TIPAX       => 'Tipax',
            self::SNAPP_BOX   => 'Snapp Box',
            self::ALOPEYK     => 'Alopeyk',
            self::IN_PERSON   => 'In-Person Delivery',
        };
    }

    public function trackingUrl(): ?string
    {
        return match($this) {
            self::POST  => 'https://tracking.post.ir/?id=',
            self::TIPAX => 'https://tipaxco.com/tracking/',
            default     => null,
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}