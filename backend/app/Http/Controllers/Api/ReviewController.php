<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewReactionRequest;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Product;
use App\Models\Review;
use App\Services\Review\ReviewReactionService;
use App\Services\Review\ReviewService;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Reviews", description: "Product review management")]
#[OA\Tag(name: "Admin Reviews", description: "Admin review moderation")]
class ReviewController extends Controller
{
    public function __construct(
        private ReviewService $reviewService,
        private ReviewReactionService $reactionService,
    ) {}
    #[OA\Get(
        path: '/api/products/{product}/reviews',
        tags: ['Reviews'],
        summary: 'Get approved reviews for a product',
        description: 'Returns paginated approved reviews with rating summary.',
        parameters: [
            new OA\Parameter(name: 'product', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'rating', in: 'query', schema: new OA\Schema(type: 'integer', minimum: 1, maximum: 5)),
            new OA\Parameter(name: 'sort_by', in: 'query', schema: new OA\Schema(type: 'string', enum: ['created_at', 'rating', 'likes_count'])),
            new OA\Parameter(name: 'sort_order', in: 'query', schema: new OA\Schema(type: 'string', enum: ['asc', 'desc'])),
            new OA\Parameter(name: 'per_page', in: 'query', schema: new OA\Schema(type: 'integer', default: 10, maximum: 50)),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Reviews retrieved successfully'),
            new OA\Response(response: 404, description: 'Product not found'),
        ]
    )]
    public function index(Request $request, Product $product)
    {
        $result = $this->reviewService->getProductReviews($product, $request);

        return response()->json([
            'success' => true,
            'data' => ReviewResource::collection($result['reviews']),
            'rating_summary' => $result['rating_summary'],
            'meta' => [
                'current_page' => $result['reviews']->currentPage(),
                'last_page' => $result['reviews']->lastPage(),
                'per_page' => $result['reviews']->perPage(),
                'total' => $result['reviews']->total(),
                'has_more' => $result['reviews']->hasMorePages(),
            ],
        ]);
    }
    #[OA\Post(
        path: '/api/reviews',
        tags: ['Reviews'],
        summary: 'Submit a review',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['product_id', 'body', 'rating'],
                properties: [
                    new OA\Property(property: 'product_id', type: 'integer', example: 1),
                    new OA\Property(property: 'body', type: 'string', example: 'Excellent product!'),
                    new OA\Property(property: 'rating', type: 'integer', minimum: 1, maximum: 5, example: 5),
                    new OA\Property(property: 'advantages', type: 'array', items: new OA\Items(type: 'string'), nullable: true),
                    new OA\Property(property: 'disadvantages', type: 'array', items: new OA\Items(type: 'string'), nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Review submitted'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 422, description: 'Validation error or duplicate'),
        ]
    )]
    public function store(StoreReviewRequest $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $review = $this->reviewService->store($user, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Your review has been submitted and is awaiting approval.',
            'data' => new ReviewResource($review),
        ], 201);
    }
    #[OA\Put(
        path: '/api/reviews/{review}',
        tags: ['Reviews'],
        summary: 'Update your review',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'review', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'body', type: 'string'),
                    new OA\Property(property: 'rating', type: 'integer', minimum: 1, maximum: 5),
                    new OA\Property(property: 'advantages', type: 'array', items: new OA\Items(type: 'string'), nullable: true),
                    new OA\Property(property: 'disadvantages', type: 'array', items: new OA\Items(type: 'string'), nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Review updated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function update(UpdateReviewRequest $request, Review $review)
    {
        $review = $this->reviewService->update($review, $request->user(), $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Your review has been updated and is awaiting approval again.',
            'data' => new ReviewResource($review),
        ]);
    }
    #[OA\Delete(
        path: '/api/reviews/{review}',
        tags: ['Reviews'],
        summary: 'Delete your review',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'review', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Review deleted'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function destroy(Request $request, Review $review)
    {
        $this->reviewService->delete($review, $request->user());

        return response()->json([
            'success' => true,
            'message' => 'Your review has been deleted successfully.',
        ]);
    }
    #[OA\Get(
        path: '/api/products/{product}/my-review',
        tags: ['Reviews'],
        summary: 'Get your review for a product',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'product', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Review retrieved'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function myReview(Request $request, Product $product)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $review = $this->reviewService->getUserReview($user, $product);

        return response()->json([
            'success' => true,
            'data' => $review ? new ReviewResource($review) : null,
            'can_review' => is_null($review),
        ]);
    }
    #[OA\Post(
        path: '/api/reviews/{review}/react',
        tags: ['Reviews'],
        summary: 'Like or dislike a review',
        description: 'Toggle like/dislike. Same type = remove. Different type = change.',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'review', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['type'],
                properties: [
                    new OA\Property(property: 'type', type: 'string', enum: ['like', 'dislike'], example: 'like'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Reaction updated'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function react(ReviewReactionRequest $request, Review $review)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $result = $this->reactionService->toggle($review, $user, $request->type);

        return response()->json([
            'success' => true,
            ...$result,
        ]);
    }
    #[OA\Get(
        path: '/api/admin/reviews',
        tags: ['Admin Reviews'],
        summary: 'List all reviews (Admin)',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'status', in: 'query', schema: new OA\Schema(type: 'string', enum: ['pending', 'approved', 'rejected'])),
            new OA\Parameter(name: 'product_id', in: 'query', schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'per_page', in: 'query', schema: new OA\Schema(type: 'integer', default: 20)),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Reviews list'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function adminIndex(Request $request)
    {
        $reviews = $this->reviewService->getAdminReviews($request);

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
    #[OA\Post(
        path: '/api/admin/reviews/{review}/approve',
        tags: ['Admin Reviews'],
        summary: 'Approve a review',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'review', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Review approved'),
        ]
    )]
    public function approve(Review $review)
    {
        $review = $this->reviewService->approve($review);

        return response()->json([
            'success' => true,
            'message' => 'Review approved successfully.',
            'data' => new ReviewResource($review),
        ]);
    }
    #[OA\Post(
        path: '/api/admin/reviews/{review}/reject',
        tags: ['Admin Reviews'],
        summary: 'Reject a review',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'review', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Review rejected'),
        ]
    )]
    public function reject(Review $review)
    {
        $review = $this->reviewService->reject($review);

        return response()->json([
            'success' => true,
            'message' => 'Review rejected successfully.',
            'data' => new ReviewResource($review),
        ]);
    }
}