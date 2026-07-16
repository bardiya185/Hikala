<?php

namespace App\Services\Address;

use App\Models\Address;
use Illuminate\Support\Facades\DB;

class AddressService
{
    public function create(array $data): Address
    {
        return DB::transaction(function () use ($data) {

            if (!empty($data['is_default'])) {

                Address::where('user_id', $data['user_id'])
                    ->update([
                        'is_default' => false
                    ]);

            }

            return Address::create($data);

        });
    }

    public function update(Address $address, array $data): Address
    {
        return DB::transaction(function () use ($address, $data) {

            if (!empty($data['is_default'])) {

                Address::where('user_id', $address->user_id)
                    ->update([
                        'is_default' => false
                    ]);

            }

            $address->update($data);

            return $address->refresh();

        });
    }

    public function delete(Address $address): void
    {
        $address->delete();
    }
}
