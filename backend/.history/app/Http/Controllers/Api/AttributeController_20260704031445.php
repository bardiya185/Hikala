<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAttributeRequest;
use App\Http\Requests\UpdateAttributeRequest;
use App\Http\Resources\AttributeResource;
use App\Models\Attribute;

class AttributeController extends Controller
{
    /**
     * Display a listing of attributes.
     */
    public function index()
    {
        $attributes = Attribute::with('values')
            ->orderBy('sort_order')
            ->get();

        return AttributeResource::collection($attributes);
    }

    /**
     * Store a newly created attribute.
     */
    public function store(StoreAttributeRequest $request)
    {
        $attribute = Attribute::create(
            $request->safe()->except('values')
        );
        
        if ($request->filled('values')) {

            foreach ($request->values as $index => $value) {
        
                $attribute->values()->create([
                    'value' => $value,
                    'sort_order' => $index,
                ]);
        
            }
        
        }

        return (new AttributeResource($attribute))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified attribute.
     */
    public function show(Attribute $attribute)
    {
        $attribute->load('values');

        return new AttributeResource($attribute);
    }

    /**
     * Update the specified attribute.
     */
    public function update(UpdateAttributeRequest $request, Attribute $attribute)
    {
        $attribute->update($request->validated());

        return new AttributeResource($attribute);
    }

    /**
     * Remove the specified attribute.
     */
    public function destroy(Attribute $attribute)
    {
        $attribute->delete();

        return response()->json([
            'message' => 'Attribute deleted successfully.',
        ]);
    }
}