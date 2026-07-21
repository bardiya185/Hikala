<?php

namespace App\Services\Discount;

use App\Models\Discount;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class DiscountManagementService
{
    public function all(): LengthAwarePaginator
    {
        return Discount::query()
            ->with([
                'products',
                'variants',
                'categories',
                'brands',
            ])
            ->latest()
            ->paginate(15);
    }

    public function create(array $data): Discount
    {
        return DB::transaction(function () use ($data) {

            $discount = Discount::create([
                'name' => $data['name'],
                'type' => $data['type'],
                'value' => $data['value'],

                'stackable' => $data['stackable'] ?? false,

                'starts_at' => $data['starts_at'] ?? null,
                'ends_at' => $data['ends_at'] ?? null,

                'quantity_limit' => $data['quantity_limit'] ?? null,

                'priority' => $data['priority'] ?? 0,

                'is_flash_sale' => $data['is_flash_sale'] ?? false,

                'is_active' => $data['is_active'] ?? true,
            ]);


            $relation = match ($data['discountable_type']) {

                'product' => 'products',

                'variant' => 'variants',

                'category' => 'categories',

                'brand' => 'brands',

            };


            $discount->{$relation}()
                ->sync($data['discountable_ids']);


            return $discount->load([
                'products',
                'variants',
                'categories',
                'brands',
            ]);

        });
    }

    public function find(Discount $discount): Discount
    {
        return $discount->load([
            'products',
            'variants',
            'categories',
            'brands',
        ]);
    }

    public function update(
        Discount $discount,
        array $data
    ): Discount {

        return DB::transaction(function () use ($discount, $data) {


            $discount->update([
                'name' => $data['name'],
                'type' => $data['type'],
                'value' => $data['value'],

                'stackable' => $data['stackable'] ?? false,

                'starts_at' => $data['starts_at'] ?? null,
                'ends_at' => $data['ends_at'] ?? null,

                'quantity_limit' => $data['quantity_limit'] ?? null,

                'priority' => $data['priority'] ?? 0,

                'is_flash_sale' => $data['is_flash_sale'] ?? false,

                'is_active' => $data['is_active'] ?? true,
            ]);


            $relation = match ($data['discountable_type']) {

                'product' => 'products',

                'variant' => 'variants',

                'category' => 'categories',

                'brand' => 'brands',

            };


            $discount->{$relation}()
                ->sync($data['discountable_ids']);


            return $discount->load([
                'products',
                'variants',
                'categories',
                'brands',
            ]);

        });
    }

    public function delete(Discount $discount): void
    {
        DB::transaction(function () use ($discount) {

            $discount->products()->detach();
            $discount->variants()->detach();
            $discount->categories()->detach();
            $discount->brands()->detach();

            $discount->delete();

        });
    }
}
