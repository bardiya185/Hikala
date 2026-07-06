<?php

namespace App\Services;

use App\Models\Brand;

class SkuGeneratorService
{
    public function generate(array $item, array $variant): string
    {
        $brandSlug = Brand::where('slug', $item['brand'])
            ->value('slug');

        $brandCode = strtoupper(substr($brandSlug, 0, 3));

        $modelCode = strtoupper(substr($item['slug'], 0, 6));

        $storage = $variant['attributes']['storage'] ?? '';

        $color = $variant['attributes']['color'] ?? '';

        return implode('-', [
            $brandCode,
            $modelCode,
            strtoupper($storage),
            strtoupper(substr($color, 0, 3)),
        ]);
    }
}