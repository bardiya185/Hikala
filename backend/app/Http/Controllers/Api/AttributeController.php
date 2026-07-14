<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAttributeRequest;
use App\Http\Requests\UpdateAttributeRequest;
use App\Http\Resources\AttributeResource;
use App\Models\Attribute;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

class AttributeController extends Controller
{
    #[OA\Get(
        path: "/api/attributes",
        tags: ["Attributes"],
        summary: "List Attributes",
        description: "Get all attributes with their values.",
        responses: [
            new OA\Response(response: 200, description: "Attributes retrieved successfully.")
        ]
    )]
    public function index()
    {
        $attributes = Attribute::with('values')
            ->orderBy('sort_order')
            ->get();

        return AttributeResource::collection($attributes);
    }

    #[OA\Post(
        path: "/api/attributes",
        tags: ["Attributes"],
        summary: "Create Attribute",
        description: "Create an attribute and its values synchronously.",
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name", "type"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Color"),
                    new OA\Property(property: "type", type: "string", example: "color"),
                    new OA\Property(property: "sort_order", type: "integer", example: 1),
                    new OA\Property(property: "is_active", type: "boolean", example: true),
                    new OA\Property(
                        property: "values",
                        type: "array",
                        items: new OA\Items(
                            properties: [
                                new OA\Property(property: "value", type: "string", example: "Red"),
                                new OA\Property(property: "color_code", type: "string", example: "#FF0000"),
                                new OA\Property(property: "sort_order", type: "integer", example: 1)
                            ]
                        )
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Attribute created successfully."),
            new OA\Response(response: 422, description: "Validation Error")
        ]
    )]
    public function store(StoreAttributeRequest $request)
    {

        $attribute = DB::transaction(function () use ($request) {
            $attr = Attribute::create($request->safe()->except('values'));

            if ($request->filled('values')) {
           
                $valuesData = [];
                foreach ($request->values as $item) {
                    $valuesData[] = [
                        'value'       => $item['value'],
                        'code'        => $item['code'] ?? null,
                        'color_code'  => $item['color_code'] ?? null,
                        'image'       => $item['image'] ?? null,
                        'sort_order'  => $item['sort_order'] ?? 0,
                        'is_active'   => $item['is_active'] ?? true,
                        'created_at'  => now(),
                        'updated_at'  => now(),
                    ];
                }
                $attr->values()->createMany($valuesData);
            }

            return $attr;
        });

        $attribute->load('values');

        return (new AttributeResource($attribute))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    #[OA\Get(
        path: "/api/attributes/{attribute}",
        tags: ["Attributes"],
        summary: "Show Attribute",
        description: "Get single attribute details with values.",
        parameters: [
            new OA\Parameter(name: "attribute", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Attribute details retrieved."),
            new OA\Response(response: 404, description: "Attribute not found.")
        ]
    )]
    public function show(Attribute $attribute)
    {
        $attribute->load('values');

        return new AttributeResource($attribute);
    }

    #[OA\Put(
        path: "/api/attributes/{attribute}",
        tags: ["Attributes"],
        summary: "Update Attribute",
        description: "Update attribute and sync its values completely.",
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(name: "attribute", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Color")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Attribute updated successfully."),
            new OA\Response(response: 422, description: "Validation Error")
        ]
    )]
    public function update(UpdateAttributeRequest $request, Attribute $attribute)
    {
        DB::transaction(function () use ($request, $attribute) {
            $attribute->update($request->safe()->except('values'));
        
            $submittedIds = [];
            
            // بهینه‌سازی کُشنده: گرفتن تمام مقادیر فعلی در یک کوئری واحد و ذخیره در کالکشن جهت دسترسی سریع سرور
            $existingValues = $attribute->values()->get()->keyBy('id');
        
            foreach ($request->input('values', []) as $item) {
                if (!empty($item['id']) && $existingValues->has($item['id'])) {
                    // بدون کوئری دیتابیس، آبجکت را از رم سرور می‌خوانیم و آپدیت میکنیم
                    $value = $existingValues->get($item['id']);
                    $value->update([
                        'value'       => $item['value'],
                        'code'        => $item['code'] ?? null,
                        'color_code'  => $item['color_code'] ?? null,
                        'image'       => $item['image'] ?? null,
                        'sort_order'  => $item['sort_order'] ?? 0,
                        'is_active'   => $item['is_active'] ?? true,
                    ]);
                    $submittedIds[] = $value->id;
                } else {
                    // مقدار جدید ایجاد می‌شود
                    $newValue = $attribute->values()->create([
                        'value'       => $item['value'],
                        'code'        => $item['code'] ?? null,
                        'color_code'  => $item['color_code'] ?? null,
                        'image'       => $item['image'] ?? null,
                        'sort_order'  => $item['sort_order'] ?? 0,
                        'is_active'   => $item['is_active'] ?? true,
                    ]);
                    $submittedIds[] = $newValue->id;
                }
            }
        
            // گارد امنیتی: قبل از حذف مقادیر تیک نخورده، مطمئن شویم به محصولی وصل نباشند
            $valuesToDelete = $attribute->values()->whereNotIn('id', $submittedIds)->get();
            foreach ($valuesToDelete as $valueToDelete) {
                // فرض بر این است که در مدل AttributeValue رابطه products یا productItems تعریف شده است
                if (method_exists($valueToDelete, 'products') && $valueToDelete->products()->exists()) {
                    continue; // اگر متصل به محصول است، حذف نشود تا فروشگاه کرش نکند
                }
                $valueToDelete->delete();
            }
        });
    
        $attribute->load('values');
    
        return new AttributeResource($attribute);
    }

    #[OA\Delete(
        path: "/api/attributes/{attribute}",
        tags: ["Attributes"],
        summary: "Delete Attribute",
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(name: "attribute", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Attribute deleted."),
            new OA\Response(response: 400, description: "Cannot delete used attributes.")
        ]
    )]
    public function destroy(Attribute $attribute)
    {
        // گارد امنیتی هنگام حذف کل ویژگی
        if ($attribute->values()->whereHas('products')->exists()) { // فرض وجود رابطه محصولات در مقادیر
            return response()->json([
                'success' => false,
                'message' => 'این ویژگی در محصولات استفاده شده است و حذف آن ساختار فروشگاه را به هم می‌ریزد.'
            ], Response::HTTP_BAD_REQUEST);
        }

        $attribute->delete();

        return response()->json([
            'success' => true,
            'message' => 'Attribute deleted successfully.',
        ]);
    }
}