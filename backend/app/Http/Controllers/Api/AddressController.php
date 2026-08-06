<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AddressResource;
use App\Services\Address\AddressService;
use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Models\Address;
use OpenApi\Attributes as OA;

class AddressController extends Controller
{
    public function __construct(
        private AddressService $service
    ) {}

    #[OA\Get(
        path: "/api/addresses",
        tags: ["Addresses"],
        summary: "Get user addresses",
        description: "Get all addresses of the authenticated user with pagination.",
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "page",
                in: "query",
                description: "Page number",
                schema: new OA\Schema(type: "integer", example: 1)
            ),
            new OA\Parameter(
                name: "per_page",
                in: "query",
                description: "Items per page",
                schema: new OA\Schema(type: "integer", example: 10)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Addresses retrieved successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: "data",
                            type: "array",
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: "id", type: "integer", example: 1),
                                    new OA\Property(property: "user_id", type: "integer", example: 5),
                                    new OA\Property(property: "title", type: "string", example: "Home"),
                                    new OA\Property(property: "receiver_name", type: "string", example: "John Doe"),
                                    new OA\Property(property: "receiver_mobile", type: "string", example: "09123456789"),
                                    new OA\Property(property: "province_id", type: "integer", example: 8),
                                    new OA\Property(property: "city_id", type: "integer", example: 120),
                                    new OA\Property(property: "address", type: "string", example: "Valiasr St, No. 123"),
                                    new OA\Property(property: "postal_code", type: "string", example: "1234567890"),
                                    new OA\Property(property: "latitude", type: "string", nullable: true, example: "35.6892"),
                                    new OA\Property(property: "longitude", type: "string", nullable: true, example: "51.3890"),
                                    new OA\Property(property: "unit", type: "string", nullable: true, example: "3"),
                                    new OA\Property(property: "plaque", type: "string", nullable: true, example: "12"),
                                    new OA\Property(property: "description", type: "string", nullable: true, example: "Near the park"),
                                    new OA\Property(property: "is_default", type: "boolean", example: true),
                                    new OA\Property(property: "is_active", type: "boolean", example: true),
                                    new OA\Property(property: "created_at", type: "string", format: "date-time"),
                                    new OA\Property(property: "updated_at", type: "string", format: "date-time"),
                                    new OA\Property(
                                        property: "province",
                                        type: "object",
                                        properties: [
                                            new OA\Property(property: "id", type: "integer", example: 8),
                                            new OA\Property(property: "name", type: "string", example: "Tehran"),
                                            new OA\Property(property: "slug", type: "string", example: "tehran"),
                                        ]
                                    ),
                                    new OA\Property(
                                        property: "city",
                                        type: "object",
                                        properties: [
                                            new OA\Property(property: "id", type: "integer", example: 120),
                                            new OA\Property(property: "name", type: "string", example: "Tehran"),
                                            new OA\Property(property: "slug", type: "string", example: "tehran"),
                                        ]
                                    )
                                ]
                            )
                        ),
                        new OA\Property(
                            property: "links",
                            type: "object",
                            properties: [
                                new OA\Property(property: "first", type: "string"),
                                new OA\Property(property: "last", type: "string"),
                                new OA\Property(property: "prev", type: "string", nullable: true),
                                new OA\Property(property: "next", type: "string", nullable: true),
                            ]
                        ),
                        new OA\Property(
                            property: "meta",
                            type: "object",
                            properties: [
                                new OA\Property(property: "current_page", type: "integer"),
                                new OA\Property(property: "from", type: "integer"),
                                new OA\Property(property: "last_page", type: "integer"),
                                new OA\Property(property: "per_page", type: "integer"),
                                new OA\Property(property: "to", type: "integer"),
                                new OA\Property(property: "total", type: "integer"),
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthenticated"
            )
        ]
    )]
    public function index()
    {
        return AddressResource::collection(
            auth()->user()
                ->addresses()
                ->with([
                    'province',
                    'city'
                ])
                ->latest()
                ->paginate(10)
        );
    }

    #[OA\Post(
        path: "/api/addresses",
        tags: ["Addresses"],
        summary: "Create a new address",
        description: "Create a new address for the authenticated user.",
        security: [
            ["bearerAuth" => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["recipient_name", "recipient_phone", "province_id", "city_id", "address"],
                properties: [
                    new OA\Property(
                        property: "title",
                        type: "string",
                        nullable: true,
                        example: "Home",
                        description: "Address title (e.g., Home, Work)"
                    ),
                    new OA\Property(
                        property: "receiver_name",
                        type: "string",
                        example: "John Doe",
                        description: "Full name of the recipient"
                    ),
                    new OA\Property(
                        property: "receiver_mobile",
                        type: "string",
                        example: "09123456789",
                        description: "Phone number of the recipient"
                    ),
                    new OA\Property(
                        property: "province_id",
                        type: "integer",
                        example: 8,
                        description: "ID of the province"
                    ),
                    new OA\Property(
                        property: "city_id",
                        type: "integer",
                        example: 120,
                        description: "ID of the city"
                    ),
                    new OA\Property(
                        property: "address",
                        type: "string",
                        example: "Valiasr St, No. 123",
                        description: "Full address details"
                    ),
                    new OA\Property(
                        property: "postal_code",
                        type: "string",
                        nullable: true,
                        example: "1234567890",
                        description: "Postal code"
                    ),
                    new OA\Property(
                        property: "latitude",
                        type: "string",
                        nullable: true,
                        example: "35.6892",
                        description: "Latitude coordinate"
                    ),
                    new OA\Property(
                        property: "longitude",
                        type: "string",
                        nullable: true,
                        example: "51.3890",
                        description: "Longitude coordinate"
                    ),
                    new OA\Property(
                        property: "unit",
                        type: "string",
                        nullable: true,
                        example: "3",
                        description: "Unit number"
                    ),
                    new OA\Property(
                        property: "plaque",
                        type: "string",
                        nullable: true,
                        example: "12",
                        description: "Plaque number"
                    ),
                    new OA\Property(
                        property: "description",
                        type: "string",
                        nullable: true,
                        example: "Near the park",
                        description: "Additional description"
                    ),
                    new OA\Property(
                        property: "is_default",
                        type: "boolean",
                        example: true,
                        description: "Set as default address"
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Address created successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: "data",
                            type: "object",
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 1),
                                new OA\Property(property: "title", type: "string", example: "Home"),
                                new OA\Property(property: "receiver_name", type: "string", example: "John Doe"),
                                new OA\Property(property: "receiver_mobile", type: "string", example: "09123456789"),
                                new OA\Property(property: "address", type: "string", example: "Valiasr St, No. 123"),
                                new OA\Property(property: "is_default", type: "boolean", example: true),
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: "Validation error",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "The recipient name field is required."),
                        new OA\Property(property: "errors", type: "object")
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthenticated"
            )
        ]
    )]
    public function store(StoreAddressRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        return new AddressResource(
            $this->service->create($data)
        );
    }

    #[OA\Get(
        path: "/api/addresses/{address}",
        tags: ["Addresses"],
        summary: "Get a specific address",
        description: "Get detailed information of a specific address.",
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "address",
                in: "path",
                required: true,
                description: "Address ID",
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Address retrieved successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: "data",
                            type: "object",
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 1),
                                new OA\Property(property: "title", type: "string", example: "Home"),
                                new OA\Property(property: "receiver_name", type: "string", example: "John Doe"),
                                new OA\Property(property: "receiver_mobile", type: "string", example: "09123456789"),
                                new OA\Property(property: "province_id", type: "integer", example: 8),
                                new OA\Property(property: "city_id", type: "integer", example: 120),
                                new OA\Property(property: "address", type: "string", example: "Valiasr St, No. 123"),
                                new OA\Property(property: "postal_code", type: "string", example: "1234567890"),
                                new OA\Property(property: "latitude", type: "string", nullable: true),
                                new OA\Property(property: "longitude", type: "string", nullable: true),
                                new OA\Property(property: "unit", type: "string", nullable: true),
                                new OA\Property(property: "plaque", type: "string", nullable: true),
                                new OA\Property(property: "description", type: "string", nullable: true),
                                new OA\Property(property: "is_default", type: "boolean", example: true),
                                new OA\Property(property: "is_active", type: "boolean", example: true),
                                new OA\Property(property: "created_at", type: "string", format: "date-time"),
                                new OA\Property(property: "updated_at", type: "string", format: "date-time"),
                                new OA\Property(
                                    property: "province",
                                    type: "object",
                                    properties: [
                                        new OA\Property(property: "id", type: "integer", example: 8),
                                        new OA\Property(property: "name", type: "string", example: "Tehran"),
                                    ]
                                ),
                                new OA\Property(
                                    property: "city",
                                    type: "object",
                                    properties: [
                                        new OA\Property(property: "id", type: "integer", example: 120),
                                        new OA\Property(property: "name", type: "string", example: "Tehran"),
                                    ]
                                )
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthenticated"
            ),
            new OA\Response(
                response: 403,
                description: "Forbidden - You don't own this address"
            ),
            new OA\Response(
                response: 404,
                description: "Address not found"
            )
        ]
    )]
    public function show(Address $address)
    {
        $this->authorize('view', $address);
        return new AddressResource(
            $address->load([
                'province',
                'city'
            ])
        );
    }

    #[OA\Put(
        path: "/api/addresses/{address}",
        tags: ["Addresses"],
        summary: "Update an address",
        description: "Update an existing address. All fields are optional.",
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "address",
                in: "path",
                required: true,
                description: "Address ID to update",
                schema: new OA\Schema(type: "integer")
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: "title",
                        type: "string",
                        nullable: true,
                        example: "Work",
                        description: "Address title"
                    ),
                    new OA\Property(
                        property: "receiver_name",
                        type: "string",
                        example: "Jane Doe",
                        description: "Recipient full name"
                    ),
                    new OA\Property(
                        property: "receiver_mobile",
                        type: "string",
                        example: "09129876543",
                        description: "Recipient phone number"
                    ),
                    new OA\Property(
                        property: "province_id",
                        type: "integer",
                        example: 8,
                        description: "Province ID"
                    ),
                    new OA\Property(
                        property: "city_id",
                        type: "integer",
                        example: 120,
                        description: "City ID"
                    ),
                    new OA\Property(
                        property: "address",
                        type: "string",
                        example: "Valiasr St, No. 456",
                        description: "Full address"
                    ),
                    new OA\Property(
                        property: "postal_code",
                        type: "string",
                        nullable: true,
                        example: "1234567890",
                        description: "Postal code"
                    ),
                    new OA\Property(
                        property: "latitude",
                        type: "string",
                        nullable: true,
                        description: "Latitude coordinate"
                    ),
                    new OA\Property(
                        property: "longitude",
                        type: "string",
                        nullable: true,
                        description: "Longitude coordinate"
                    ),
                    new OA\Property(
                        property: "unit",
                        type: "string",
                        nullable: true,
                        description: "Unit number"
                    ),
                    new OA\Property(
                        property: "plaque",
                        type: "string",
                        nullable: true,
                        description: "Plaque number"
                    ),
                    new OA\Property(
                        property: "description",
                        type: "string",
                        nullable: true,
                        description: "Additional description"
                    ),
                    new OA\Property(
                        property: "is_default",
                        type: "boolean",
                        example: true,
                        description: "Set as default address"
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Address updated successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: "data",
                            type: "object",
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 1),
                                new OA\Property(property: "title", type: "string", example: "Work"),
                                new OA\Property(property: "recipient_name", type: "string", example: "Jane Doe"),
                                new OA\Property(property: "address", type: "string", example: "Valiasr St, No. 456"),
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthenticated"
            ),
            new OA\Response(
                response: 403,
                description: "Forbidden - You don't own this address"
            ),
            new OA\Response(
                response: 404,
                description: "Address not found"
            ),
            new OA\Response(
                response: 422,
                description: "Validation error"
            )
        ]
    )]
    public function update(
        UpdateAddressRequest $request,
        Address $address
    ) {
        $this->authorize('update', $address);
        return new AddressResource(
            $this->service->update(
                $address,
                $request->validated()
            )
        );
    }

    #[OA\Delete(
        path: "/api/addresses/{address}",
        tags: ["Addresses"],
        summary: "Delete an address",
        description: "Delete an address. If it's the default address, another address will become default.",
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "address",
                in: "path",
                required: true,
                description: "Address ID to delete",
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Address deleted successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Address deleted successfully.")
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthenticated"
            ),
            new OA\Response(
                response: 403,
                description: "Forbidden - You don't own this address"
            ),
            new OA\Response(
                response: 404,
                description: "Address not found"
            )
        ]
    )]
    public function destroy(Address $address)
    {
        $this->authorize('delete', $address);
        $this->service->delete($address);

        return response()->json([
            'message' => 'Address deleted successfully.'
        ]);
    }
}