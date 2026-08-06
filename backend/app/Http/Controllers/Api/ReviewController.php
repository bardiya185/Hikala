<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Reviews",
    description: "Product review management"
)]
#[OA\Tag(
    name: "Admin Reviews",
    description: "Admin review moderation"
)]
#[OA\Schema(
    schema: "Review",
    title: "Review",
    description: "Product review model",
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
        new OA\Property(property: "body", type: "string", example: "This product is excellent quality and works perfectly."),
        new OA\Property(property: "rating", type: "integer", minimum: 1, maximum: 5, example: 5),
        new OA\Property(
            property: "advantages",
            type: "array",
            items: new OA\Items(type: "string"),
            example: ["High quality", "Nice design"],
            nullable: true
        ),
        new OA\Property(
            property: "disadvantages",
            type: "array",
            items: new OA\Items(type: "string"),
            example: ["Price is a little high"],
            nullable: true
        ),
        new OA\Property(property: "status", type: "string", enum: ["pending", "approved", "rejected"], example: "approved"),
        new OA\Property(property: "is_buyer", type: "boolean", example: true),
        new OA\Property(property: "created_at", type: "string", format: "date-time"),
        new OA\Property(property: "updated_at", type: "string", format: "date-time"),
        new OA\Property(
            property: "user",
            type: "object",
            properties: [
                new OA\Property(property: "id", type: "integer", example: 3),
                new OA\Property(property: "name", type: "string", example: "John Doe"),
            ]
        ),
    ]
)]
#[OA\Schema(
    schema: "RatingSummary",
    title: "Rating Summary",
    description: "Product rating statistics",
    properties: [
        new OA\Property(property: "average", type: "number", format: "float", example: 4.2),
        new OA\Property(property: "total", type: "integer", example: 15),
        new OA\Property(
            property: "breakdown",
            type: "object",
            properties: [
                new OA\Property(property: "5", type: "integer", example: 7),
                new OA\Property(property: "4", type: "integer", example: 4),
                new OA\Property(property: "3", type: "integer", example: 2),
                new OA\Property(property: "2", type: "integer", example: 1),
                new OA\Property(property: "1", type: "integer", example: 1),
            ]
        ),
    ]
)]
class ReviewController extends Controller
{
    // ================================================================
    // Get Product Reviews (Public)
    // ================================================================
    #[OA\Get(
        path: '/api/products/{product}/reviews',
        tags: ['Reviews'],
        summary: 'Get approved reviews for a product',
        description: 'Returns paginated list of approved reviews with rating summary and breakdown.',
        parameters: [
            new OA\Parameter(
                name: 'product',
                in: 'path',
                required: true,
                description: 'Product ID',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
            new OA\Parameter(
                name: 'rating',
                in: 'query',
                description: 'Filter by specific rating (1-5)',
                schema: new OA\Schema(type: 'integer', minimum: 1, maximum: 5, example: 5)
            ),
            new OA\Parameter(
                name: 'sort_by',
                in: 'query',
                description: 'Sort field',
                schema: new OA\Schema(
                    type: 'string',
                    default: 'created_at',
                    enum: ['created_at', 'rating']
                )
            ),
            new OA\Parameter(
                name: 'sort_order',
                in: 'query',
                description: 'Sort direction',
                schema: new OA\Schema(
                    type: 'string',
                    default: 'desc',
                    enum: ['asc', 'desc']
                )
            ),
            new OA\Parameter(
                name: 'per_page',
                in: 'query',
                description: 'Reviews per page',
                schema: new OA\Schema(type: 'integer', default: 10, minimum: 1, maximum: 50)
            ),
            new OA\Parameter(
                name: 'page',
                in: 'query',
                description: 'Page number',
                schema: new OA\Schema(type: 'integer', default: 1, minimum: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Reviews retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/Review')
                        ),
                        new OA\Property(
                            property: 'rating_summary',
                            ref: '#/components/schemas/RatingSummary'
                        ),
                        new OA\Property(
                            property: 'meta',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'current_page', type: 'integer', example: 1),
                                new OA\Property(property: 'last_page', type: 'integer', example: 2),
                                new OA\Property(property: 'per_page', type: 'integer', example: 10),
                                new OA\Property(property: 'total', type: 'integer', example: 15),
                                new OA\Property(property: 'has_more', type: 'boolean', example: true),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(response: 404, description: 'Product not found'),
        ]
    )]
    public function index(Request $request, Product $product)
    {
        $query = $product->reviews()
            ->approved()
            ->with('user')
            ->latest();

        if ($request->has('rating')) {
            $query->where('rating', $request->rating);
        }

        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $allowedSorts = ['created_at', 'rating'];

        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $perPage = min((int) $request->get('per_page', 10), 50);
        $reviews = $query->paginate($perPage);

        $ratingStats = $product->reviews()
            ->approved()
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating')
            ->toArray();

        $totalReviews = array_sum($ratingStats);

        $averageRating = $totalReviews > 0
            ? round(
                collect($ratingStats)->map(fn($count, $rating) => $rating * $count)->sum() / $totalReviews,
                1
            )
            : 0;

        return response()->json([
            'success' => true,
            'data' => ReviewResource::collection($reviews),
            'rating_summary' => [
                'average' => $averageRating,
                'total' => $totalReviews,
                'breakdown' => [
                    5 => $ratingStats[5] ?? 0,
                    4 => $ratingStats[4] ?? 0,
                    3 => $ratingStats[3] ?? 0,
                    2 => $ratingStats[2] ?? 0,
                    1 => $ratingStats[1] ?? 0,
                ],
            ],
            'meta' => [
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
                'per_page' => $reviews->perPage(),
                'total' => $reviews->total(),
                'has_more' => $reviews->hasMorePages(),
            ],
        ]);
    }

    // ================================================================
    // Submit Review (Authenticated)
    // ================================================================
    #[OA\Post(
        path: '/api/reviews',
        tags: ['Reviews'],
        summary: 'Submit a review for a product',
        description: 'Authenticated user can submit one review per product. The review will be pending until admin approves it. Buyer status is automatically detected.',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['product_id', 'body', 'rating'],
                properties: [
                    new OA\Property(property: 'product_id', type: 'integer', example: 1),
                    new OA\Property(
                        property: 'body',
                        type: 'string',
                        minLength: 10,
                        maxLength: 2000,
                        example: 'This product is excellent quality and works perfectly.'
                    ),
                    new OA\Property(
                        property: 'rating',
                        type: 'integer',
                        minimum: 1,
                        maximum: 5,
                        example: 5
                    ),
                    new OA\Property(
                        property: 'advantages',
                        type: 'array',
                        items: new OA\Items(type: 'string', maxLength: 255),
                        example: ['High quality', 'Nice design'],
                        nullable: true
                    ),
                    new OA\Property(
                        property: 'disadvantages',
                        type: 'array',
                        items: new OA\Items(type: 'string', maxLength: 255),
                        example: ['Price is a little high'],
                        nullable: true
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Review submitted successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                            example: 'Your review has been submitted and is awaiting approval.'
                        ),
                        new OA\Property(property: 'data', ref: '#/components/schemas/Review'),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error or duplicate review',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false),
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                            example: 'You have already submitted a review for this product.'
                        ),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function store(StoreReviewRequest $request)
    {
        $user = $request->user();
        $productId = $request->product_id;

        $exists = Review::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'You have already submitted a review for this product.',
            ], 422);
        }

        $isBuyer = OrderItem::whereHas('order', function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->where('status', 'delivered');
            })
            ->whereHas('variant', function ($q) use ($productId) {
                $q->where('product_id', $productId);
            })
            ->exists();

        $review = Review::create([
            'user_id' => $user->id,
            'product_id' => $productId,
            'body' => $request->body,
            'rating' => $request->rating,
            'advantages' => $request->advantages,
            'disadvantages' => $request->disadvantages,
            'status' => 'pending',
            'is_buyer' => $isBuyer,
        ]);

        $review->load('user');

        return response()->json([
            'success' => true,
            'message' => 'Your review has been submitted and is awaiting approval.',
            'data' => new ReviewResource($review),
        ], 201);
    }

    // ================================================================
    // Update Review (Owner Only)
    // ================================================================
    #[OA\Put(
        path: '/api/reviews/{review}',
        tags: ['Reviews'],
        summary: 'Update your review',
        description: 'Only the review owner can update it. After update, the review status resets to pending for admin approval.',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'review',
                in: 'path',
                required: true,
                description: 'Review ID',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'body',
                        type: 'string',
                        minLength: 10,
                        maxLength: 2000,
                        example: 'Updated review text with more details about the product.'
                    ),
                    new OA\Property(
                        property: 'rating',
                        type: 'integer',
                        minimum: 1,
                        maximum: 5,
                        example: 4
                    ),
                    new OA\Property(
                        property: 'advantages',
                        type: 'array',
                        items: new OA\Items(type: 'string'),
                        example: ['Updated advantage'],
                        nullable: true
                    ),
                    new OA\Property(
                        property: 'disadvantages',
                        type: 'array',
                        items: new OA\Items(type: 'string'),
                        example: ['Updated disadvantage'],
                        nullable: true
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Review updated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                            example: 'Your review has been updated and is awaiting approval again.'
                        ),
                        new OA\Property(property: 'data', ref: '#/components/schemas/Review'),
                    ]
                )
            ),
            new OA\Response(
                response: 403,
                description: 'Forbidden - Not the review owner',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false),
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                            example: 'You are not allowed to update this review.'
                        ),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Review not found'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function update(UpdateReviewRequest $request, Review $review)
{
    $user = $request->user();

    if ($review->user_id !== $user->id) {
        return response()->json([
            'success' => false,
            'message' => 'You are not allowed to update this review.',
        ], 403);
    }

    $wasApproved = $review->status === 'approved';

    $review->update([
        'body' => $request->input('body', $review->body),
        'rating' => $request->input('rating', $review->rating),
        'advantages' => $request->input('advantages', $review->advantages),
        'disadvantages' => $request->input('disadvantages', $review->disadvantages),
        'status' => 'pending',
    ]);

    if ($wasApproved) {
        $this->updateProductRating($review->product_id);
    }

    $review->load('user');

    return response()->json([
        'success' => true,
        'message' => 'Your review has been updated and is awaiting approval again.',
        'data' => new ReviewResource($review),
    ]);
}

    // ================================================================
    // Delete Review (Owner Only)
    // ================================================================
    #[OA\Delete(
        path: '/api/reviews/{review}',
        tags: ['Reviews'],
        summary: 'Delete your review',
        description: 'Only the review owner can delete it.',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'review',
                in: 'path',
                required: true,
                description: 'Review ID',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Review deleted successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                            example: 'Your review has been deleted successfully.'
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 403,
                description: 'Forbidden - Not the review owner',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false),
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                            example: 'You are not allowed to delete this review.'
                        ),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Review not found'),
        ]
    )]
    public function destroy(Request $request, Review $review)
{
    $user = $request->user();

    if ($review->user_id !== $user->id) {
        return response()->json([
            'success' => false,
            'message' => 'You are not allowed to delete this review.',
        ], 403);
    }

    $productId = $review->product_id;
    $wasApproved = $review->status === 'approved';

    $review->delete();

    if ($wasApproved) {
        $this->updateProductRating($productId);
    }

    return response()->json([
        'success' => true,
        'message' => 'Your review has been deleted successfully.',
    ]);
}

    // ================================================================
    // My Review for a Product (Authenticated)
    // ================================================================
    #[OA\Get(
        path: '/api/products/{product}/my-review',
        tags: ['Reviews'],
        summary: 'Get your review for a product',
        description: 'Returns the authenticated user\'s review for a specific product. If no review exists, returns null with can_review: true.',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'product',
                in: 'path',
                required: true,
                description: 'Product ID',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'User review retrieved',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(
                            property: 'data',
                            ref: '#/components/schemas/Review',
                            nullable: true
                        ),
                        new OA\Property(property: 'can_review', type: 'boolean', example: false),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Product not found'),
        ]
    )]
    public function myReview(Request $request, Product $product)
    {
        $user = $request->user();

        $review = Review::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if (!$review) {
            return response()->json([
                'success' => true,
                'data' => null,
                'can_review' => true,
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => new ReviewResource($review),
            'can_review' => false,
        ]);
    }

    // ================================================================
    // Admin: List All Reviews
    // ================================================================
    #[OA\Get(
        path: '/api/admin/reviews',
        tags: ['Admin Reviews'],
        summary: 'List all reviews (Admin)',
        description: 'Returns all reviews with filtering by status and product. Ordered by latest first.',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'status',
                in: 'query',
                description: 'Filter by review status',
                schema: new OA\Schema(
                    type: 'string',
                    enum: ['pending', 'approved', 'rejected']
                )
            ),
            new OA\Parameter(
                name: 'product_id',
                in: 'query',
                description: 'Filter by product ID',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
            new OA\Parameter(
                name: 'per_page',
                in: 'query',
                description: 'Reviews per page',
                schema: new OA\Schema(type: 'integer', default: 20, minimum: 1, maximum: 100)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Reviews list retrieved',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/Review')
                        ),
                        new OA\Property(
                            property: 'meta',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'current_page', type: 'integer'),
                                new OA\Property(property: 'last_page', type: 'integer'),
                                new OA\Property(property: 'per_page', type: 'integer'),
                                new OA\Property(property: 'total', type: 'integer'),
                                new OA\Property(property: 'has_more', type: 'boolean'),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function adminIndex(Request $request)
    {
        $query = Review::with(['user', 'product'])->latest();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        $perPage = min((int) $request->get('per_page', 20), 100);
        $reviews = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => ReviewResource::collection($reviews),
            'meta' => [
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
                'per_page' => $reviews->perPage(),
                'total' => $reviews->total(),
                'has_more' => $reviews->hasMorePages(),
            ],
        ]);
    }

    // ================================================================
    // Admin: Approve Review
    // ================================================================
    #[OA\Post(
        path: '/api/admin/reviews/{review}/approve',
        tags: ['Admin Reviews'],
        summary: 'Approve a review (Admin)',
        description: 'Approves a pending or rejected review. Product rating is recalculated automatically.',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'review',
                in: 'path',
                required: true,
                description: 'Review ID',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Review approved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Review approved successfully.'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/Review'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Review not found'),
        ]
    )]
    public function approve(Review $review)
    {
        $review->update(['status' => 'approved']);

        $this->updateProductRating($review->product_id);

        return response()->json([
            'success' => true,
            'message' => 'Review approved successfully.',
            'data' => new ReviewResource($review->load('user')),
        ]);
    }

    // ================================================================
    // Admin: Reject Review
    // ================================================================
    #[OA\Post(
        path: '/api/admin/reviews/{review}/reject',
        tags: ['Admin Reviews'],
        summary: 'Reject a review (Admin)',
        description: 'Rejects a pending or approved review. Product rating is recalculated automatically.',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'review',
                in: 'path',
                required: true,
                description: 'Review ID',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Review rejected successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Review rejected successfully.'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/Review'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Review not found'),
        ]
    )]
    public function reject(Review $review)
    {
        $review->update(['status' => 'rejected']);

        $this->updateProductRating($review->product_id);

        return response()->json([
            'success' => true,
            'message' => 'Review rejected successfully.',
            'data' => new ReviewResource($review->load('user')),
        ]);
    }

    // ================================================================
    // Helper: Update Product Rating
    // ================================================================
    private function updateProductRating(int $productId): void
    {
        $average = Review::where('product_id', $productId)
            ->approved()
            ->avg('rating');

        Product::where('id', $productId)->update([
            'rating' => $average ? round($average, 2) : 0,
        ]);
    }
}