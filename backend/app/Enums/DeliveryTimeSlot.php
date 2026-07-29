<?php

namespace App\Enums;

enum DeliveryTimeSlot: string
{
    case MORNING   = 'morning';    // 09:00 - 12:00
    case AFTERNOON = 'afternoon';  // 12:00 - 17:00
    case EVENING   = 'evening';    // 17:00 - 21:00
    case ANYTIME   = 'anytime';    // 09:00 - 21:00


    public function label(): string
    {
        return match($this) {
            self::MORNING   => 'Morning',
            self::AFTERNOON => 'Afternoon',
            self::EVENING   => 'Evening',
            self::ANYTIME   => 'Anytime',
        };
    }

    public function timeRange(): string
    {
        return match($this) {
            self::MORNING   => '09:00 - 12:00',
            self::AFTERNOON => '12:00 - 17:00',
            self::EVENING   => '17:00 - 21:00',
            self::ANYTIME   => '09:00 - 21:00',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::MORNING   => '🌅',
            self::AFTERNOON => '☀️',
            self::EVENING   => '🌆',
            self::ANYTIME   => '🕐',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function options(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[] = [
                'value' => $case->value,
                'label' => $case->label(),
                'time_range' => $case->timeRange(),
                'icon' => $case->icon(),
            ];
        }
        return $options;
    }
}