<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCouponRequest;
use App\Http\Requests\UpdateCouponRequest;
use App\Http\Resources\CouponResource;
use App\Models\Coupon;
use App\Services\Coupon\CouponService;
use App\Services\Coupon\CouponValidator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Coupons",
    description: "Coupon code management"
)]
#[OA\Schema(
    schema: "Coupon",
    title: "Coupon",
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
        new OA\Property(property: "code", type: "string", example: "SUMMER20"),
        new OA\Property(property: "usage_limit", type: "integer", nullable: true, example: 100),
        new OA\Property(property: "used_count", type: "integer", example: 25),
        new OA\Property(property: "remaining", type: "integer", nullable: true, example: 75),
        new OA\Property(property: "is_active", type: "boolean", example: true),
        new OA\Property(
            property: "discount",
            type: "object",
            properties: [
                new OA\Property(property: "id", type: "integer"),
                new OA\Property(property: "name", type: "string"),
                new OA\Property(property: "type", type: "string", enum: ["percent", "fixed"]),
                new OA\Property(property: "value", type: "number"),
            ]
        ),
    ]
)]
class CouponController extends Controller
{
    public function __construct(
        private CouponService $couponService,
        private CouponValidator $validator
    ) {}

    // ================================================================
    // 🌐 PUBLIC: Validate Coupon
    // ================================================================
    #[OA\Post(
        path: '/api/coupons/validate',
        tags: ['Coupons'],
        summary: 'Validate coupon code',
        description: 'Check if a coupon code is valid and returns its details',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['code'],
                properties: [
                    new OA\Property(property: 'code', type: 'string', example: 'SUMMER20'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Coupon is valid',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Coupon is valid'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/Coupon'),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Invalid or expired coupon'
            )
        ]
    )]
    public function validate(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50',
        ]);

        try {
            $coupon = $this->couponService->find($request->code);

            if (!$coupon) {
                return response()->json([
                    'success' => false,
                    'message' => 'Coupon not found',
                ], Response::HTTP_NOT_FOUND);
            }

            // بارگذاری discount
            $coupon->load('discount');

            // ولیدیت
            $this->validator->validate($coupon);

            // ولیدیت برای کاربر (اگه لاگین‌کرده باشه)
            if ($request->user()) {
                $this->validator->validateForUser($coupon, $request->user());
            }

            return response()->json([
                'success' => true,
                'message' => 'Coupon is valid',
                'data' => new CouponResource($coupon),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    // ================================================================
    // 🔒 ADMIN: List Coupons
    // ================================================================
    #[OA\Get(
        path: '/api/admin/coupons',
        tags: ['Coupons'],
        summary: 'List all coupons (Admin)',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'is_active',
                in: 'query',
                schema: new OA\Schema(type: 'boolean')
            ),
            new OA\Parameter(
                name: 'search',
                in: 'query',
                description: 'Search by code',
                schema: new OA\Schema(type: 'string')
            ),
            new OA\Parameter(
                name: 'per_page',
                in: 'query',
                schema: new OA\Schema(type: 'integer', default: 20)
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Coupons list'),
        ]
    )]
    public function index(Request $request)
    {
        $query = Coupon::with('discount');

        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        if ($request->has('search')) {
            $query->where('code', 'LIKE', "%{$request->search}%");
        }

        $perPage = min((int) $request->get('per_page', 20), 100);
        $coupons = $query->latest()->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => CouponResource::collection($coupons),
            'meta' => [
                'current_page' => $coupons->currentPage(),
                'last_page' => $coupons->lastPage(),
                'per_page' => $coupons->perPage(),
                'total' => $coupons->total(),
            ],
        ]);
    }

    // ================================================================
    // 🔒 ADMIN: Create Coupon
    // ================================================================
    #[OA\Post(
        path: '/api/admin/coupons',
        tags: ['Coupons'],
        summary: 'Create new coupon',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['code', 'discount_id'],
                properties: [
                    new OA\Property(property: 'code', type: 'string', example: 'SUMMER20'),
                    new OA\Property(property: 'discount_id', type: 'integer', example: 1),
                    new OA\Property(property: 'usage_limit', type: 'integer', nullable: true, example: 100),
                    new OA\Property(property: 'is_active', type: 'boolean', example: true),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Coupon created'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function store(StoreCouponRequest $request)
    {
        $coupon = Coupon::create($request->validated());
        $coupon->load('discount');

        return (new CouponResource($coupon))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    // ================================================================
    // 🔒 ADMIN: Show Coupon
    // ================================================================
    #[OA\Get(
        path: '/api/admin/coupons/{coupon}',
        tags: ['Coupons'],
        summary: 'Show coupon details',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'coupon',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Coupon details'),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    public function show(Coupon $coupon)
    {
        $coupon->load(['discount', 'usages.user']);

        return new CouponResource($coupon);
    }

    // ================================================================
    // 🔒 ADMIN: Update Coupon
    // ================================================================
    #[OA\Put(
        path: '/api/admin/coupons/{coupon}',
        tags: ['Coupons'],
        summary: 'Update coupon',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'coupon',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'code', type: 'string'),
                    new OA\Property(property: 'discount_id', type: 'integer'),
                    new OA\Property(property: 'usage_limit', type: 'integer', nullable: true),
                    new OA\Property(property: 'is_active', type: 'boolean'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Coupon updated'),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    public function update(UpdateCouponRequest $request, Coupon $coupon)
    {
        $coupon->update($request->validated());
        $coupon->load('discount');

        return new CouponResource($coupon);
    }

    // ================================================================
    // 🔒 ADMIN: Delete Coupon
    // ================================================================
    #[OA\Delete(
        path: '/api/admin/coupons/{coupon}',
        tags: ['Coupons'],
        summary: 'Delete coupon',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'coupon',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Coupon deleted'),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return response()->json([
            'success' => true,
            'message' => 'Coupon deleted successfully',
        ]);
    }
}