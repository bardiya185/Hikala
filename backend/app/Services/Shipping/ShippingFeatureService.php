<?php

namespace App\Services\Shipping;

use App\Models\ProductVariant;
use App\Models\ProductVariantShippingFeature;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ShippingFeatureService
{

    public function getByVariant(
        ProductVariant $variant
    ): Collection {

        return $variant
            ->shippingFeatures()
            ->latest()
            ->get();

    }



    public function create(
        ProductVariant $variant,
        array $data
    ): ProductVariantShippingFeature {

        return DB::transaction(function () use ($variant, $data) {

            return $variant
                ->shippingFeatures()
                ->create($data);

        });

    }



    public function update(
        ProductVariantShippingFeature $feature,
        array $data
    ): ProductVariantShippingFeature {

        return DB::transaction(function () use ($feature, $data) {

            $feature->update($data);

            return $feature->refresh();

        });

    }



    public function delete(
        ProductVariantShippingFeature $feature
    ): void {

        DB::transaction(function () use ($feature) {

            $feature->delete();

        });

    }

}
