<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDiscountRequest;
use App\Http\Requests\UpdateDiscountRequest;
use App\Models\Discount;
use App\Services\Discount\DiscountManagementService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class DiscountController extends Controller
{
    public function __construct(
        private readonly DiscountManagementService $service
    ) {
    }

    #[OA\Get(
        path: "/api/discounts",
        summary: "Get all discounts",
        tags: ["Discounts"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Discount list returned successfully"
            ),
            new OA\Response(
                response: 401,
                description: "Unauthenticated"
            )
        ]
    )]
    public function index(): JsonResponse
    {
        return response()->json(
            $this->service->all()
        );
    }

    #[OA\Post(
        path: "/api/discounts",
        summary: "Create new discount",
        tags: ["Discounts"],
        security: [
            ["bearerAuth" => []]
        ],

        requestBody: new OA\RequestBody(
            required: true,

            content: new OA\JsonContent(
                required: [
                    "name",
                    "type",
                    "value",
                    "discountable_type",
                    "discountable_ids"
                ],

                properties: [

                    new OA\Property(
                        property: "name",
                        type: "string",
                        example: "Summer Sale"
                    ),

                    new OA\Property(
                        property: "type",
                        type: "string",
                        enum: ["percent", "fixed"],
                        example: "percent"
                    ),

                    new OA\Property(
                        property: "value",
                        type: "number",
                        example: 20
                    ),

                    new OA\Property(
                        property: "stackable",
                        type: "boolean",
                        example: false
                    ),

                    new OA\Property(
                        property: "starts_at",
                        type: "string",
                        example: "2026-07-20 00:00:00"
                    ),

                    new OA\Property(
                        property: "ends_at",
                        type: "string",
                        example: "2026-08-01 00:00:00"
                    ),

                    new OA\Property(
                        property: "discountable_type",
                        type: "string",
                        enum: [
                            "product",
                            "variant",
                            "category",
                            "brand"
                        ],
                        example: "product"
                    ),

                    new OA\Property(
                        property: "discountable_ids",
                        type: "array",
                        example: [1,2,3],
                        items: new OA\Items(
                            type: "integer"
                        )
                    )
                ]
            )
        ),

        responses: [

            new OA\Response(
                response: 201,
                description: "Discount created successfully"
            ),

            new OA\Response(
                response: 422,
                description: "Validation error"
            ),

            new OA\Response(
                response: 401,
                description: "Unauthenticated"
            )
        ]
    )]
    public function store(
        StoreDiscountRequest $request
    ): JsonResponse {

        $discount = $this->service->create(
            $request->validated()
        );


        return response()->json([
            'message' => 'Discount created successfully.',
            'data' => $discount,
        ], 201);
    }

    #[OA\Get(
        path: "/api/discounts/{discount}",
        summary: "Get single discount",
        tags: ["Discounts"],
        parameters: [

            new OA\Parameter(
                name: "discount",
                in: "path",
                required: true,
                description: "Discount ID",
                schema: new OA\Schema(
                    type: "integer"
                )
            )

        ],

        responses: [

            new OA\Response(
                response: 200,
                description: "Discount returned successfully"
            ),

            new OA\Response(
                response: 404,
                description: "Discount not found"
            )

        ]
    )]
    public function show(
        Discount $discount
    ): JsonResponse {

        return response()->json([
            'data' => $this->service->find($discount),
        ]);
    }

    #[OA\Put(
        path: "/api/discounts/{discount}",
        summary: "Update discount",
        tags: ["Discounts"],
        security: [
            ["bearerAuth" => []]
        ],

        parameters: [

            new OA\Parameter(
                name: "discount",
                in: "path",
                required: true,
                schema: new OA\Schema(
                    type: "integer"
                )
            )

        ],

        requestBody: new OA\RequestBody(
            required: true,

            content: new OA\JsonContent(
                properties: [

                    new OA\Property(
                        property: "name",
                        type: "string",
                        example: "Updated Discount"
                    ),

                    new OA\Property(
                        property: "value",
                        type: "number",
                        example: 30
                    ),

                    new OA\Property(
                        property: "is_active",
                        type: "boolean",
                        example: true
                    )

                ]
            )
        ),

        responses: [

            new OA\Response(
                response: 200,
                description: "Discount updated successfully"
            ),

            new OA\Response(
                response: 422,
                description: "Validation error"
            )

        ]
    )]
    public function update(
        UpdateDiscountRequest $request,
        Discount $discount
    ): JsonResponse {

        $discount = $this->service->update(
            $discount,
            $request->validated()
        );


        return response()->json([
            'message' => 'Discount updated successfully.',
            'data' => $discount,
        ]);
    }

    #[OA\Delete(
        path: "/api/discounts/{discount}",
        summary: "Delete discount",
        tags: ["Discounts"],
        security: [
            ["bearerAuth" => []]
        ],

        parameters: [

            new OA\Parameter(
                name: "discount",
                in: "path",
                required: true,
                schema: new OA\Schema(
                    type: "integer"
                )
            )

        ],

        responses: [

            new OA\Response(
                response: 200,
                description: "Discount deleted successfully"
            ),

            new OA\Response(
                response: 404,
                description: "Discount not found"
            )

        ]
    )]
    public function destroy(
        Discount $discount
    ): JsonResponse {

        $this->service->delete($discount);


        return response()->json([
            'message' => 'Discount deleted successfully.',
        ]);
    }
}
