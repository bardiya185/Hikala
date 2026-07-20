<?php

namespace App\Enums;

enum ShippingFeatureType: string
{
    case FAST = 'fast';

    case SAME_DAY = 'same_day';

    case NEXT_DAY = 'next_day';

    case PICKUP = 'pickup';
}
