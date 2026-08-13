<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignUserRoleRequest;
use App\Models\Role;
use App\Models\User;
use App\Services\User\UserRoleService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "User Roles", description: "Manage user roles (admin only)")]
class UserRoleController extends Controller
{
    public function __construct(
        private UserRoleService $userRoleService
    ) {}

    // ================================================================
    // Get User Roles
    // ================================================================
    #[OA\Get(
        path: '/api/admin/users/{user}/roles',
        tags: ['User Roles'],
        summary: 'Get roles and permissions of a user',
        description: 'Returns all roles and permissions assigned to a user.',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'user',
                in: 'path',
                required: true,
                description: 'User ID',
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'User roles retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'user_id', type: 'integer', example: 1),
                                new OA\Property(property: 'name', type: 'string', example: 'Ali'),
                                new OA\Property(
                                    property: 'roles',
                                    type: 'array',
                                    items: new OA\Items(type: 'string'),
                                    example: ['admin']
                                ),
                                new OA\Property(
                                    property: 'permissions',
                                    type: 'array',
                                    items: new OA\Items(type: 'string'),
                                    example: ['view-products', 'create-products']
                                ),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'User not found'),
        ]
    )]
    public function index(User $user): JsonResponse
    {
        $roleData = $this->userRoleService->getRoles($user);

        return response()->json([
            'success' => true,
            'data' => [
                'user_id' => $user->id,
                'name' => $user->name,
                ...$roleData,
            ],
        ]);
    }

    // ================================================================
    // Set Primary Role (replace all)
    // ================================================================
    #[OA\Put(
        path: '/api/admin/users/{user}/role',
        tags: ['User Roles'],
        summary: 'Set primary role for a user',
        description: 'Replaces all existing roles with the specified role. Use this to change a user\'s main role.',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'user',
                in: 'path',
                required: true,
                description: 'User ID',
                schema: new OA\Schema(type: 'integer', example: 5)
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['role'],
                properties: [
                    new OA\Property(
                        property: 'role',
                        type: 'string',
                        example: 'admin',
                        description: 'Role name (admin, seller, user, super-admin)'
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Primary role updated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Primary role updated successfully.'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'user_id', type: 'integer', example: 5),
                                new OA\Property(
                                    property: 'roles',
                                    type: 'array',
                                    items: new OA\Items(type: 'string'),
                                    example: ['admin']
                                ),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function sync(AssignUserRoleRequest $request, User $user): JsonResponse
    {
        $updatedUser = $this->userRoleService->syncPrimaryRole(
            $user,
            $request->role,
            $request->user()
        );

        return response()->json([
            'success' => true,
            'message' => 'Primary role updated successfully.',
            'data' => [
                'user_id' => $updatedUser->id,
                'roles' => $updatedUser->getRoleNames()->values(),
            ],
        ]);
    }

    // ================================================================
    // Attach Additional Role
    // ================================================================
    #[OA\Post(
        path: '/api/admin/users/{user}/roles',
        tags: ['User Roles'],
        summary: 'Attach an additional role to a user',
        description: 'Adds a role without removing existing roles.',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'user',
                in: 'path',
                required: true,
                description: 'User ID',
                schema: new OA\Schema(type: 'integer', example: 5)
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['role'],
                properties: [
                    new OA\Property(property: 'role', type: 'string', example: 'seller')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Role attached successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Role attached successfully.'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'user_id', type: 'integer', example: 5),
                                new OA\Property(
                                    property: 'roles',
                                    type: 'array',
                                    items: new OA\Items(type: 'string'),
                                    example: ['admin', 'seller']
                                ),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function attach(AssignUserRoleRequest $request, User $user): JsonResponse
    {
        $updatedUser = $this->userRoleService->attachRole(
            $user,
            $request->role,
            $request->user()
        );

        return response()->json([
            'success' => true,
            'message' => 'Role attached successfully.',
            'data' => [
                'user_id' => $updatedUser->id,
                'roles' => $updatedUser->getRoleNames()->values(),
            ],
        ]);
    }

    // ================================================================
    // Remove Role
    // ================================================================
    #[OA\Delete(
        path: '/api/admin/users/{user}/roles/{role}',
        tags: ['User Roles'],
        summary: 'Remove a role from a user',
        description: 'Removes a specific role from a user. Cannot remove the last super-admin.',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'user',
                in: 'path',
                required: true,
                description: 'User ID',
                schema: new OA\Schema(type: 'integer', example: 5)
            ),
            new OA\Parameter(
                name: 'role',
                in: 'path',
                required: true,
                description: 'Role ID',
                schema: new OA\Schema(type: 'integer', example: 2)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Role removed successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Role removed successfully.'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'user_id', type: 'integer', example: 5),
                                new OA\Property(
                                    property: 'roles',
                                    type: 'array',
                                    items: new OA\Items(type: 'string'),
                                    example: ['user']
                                ),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(response: 400, description: 'Cannot remove last super-admin'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
        ]
    )]
    public function destroy(User $user, Role $role): JsonResponse
    {
        $actor = auth()->user();

        $updatedUser = $this->userRoleService->removeRole($user, $role, $actor);

        return response()->json([
            'success' => true,
            'message' => 'Role removed successfully.',
            'data' => [
                'user_id' => $updatedUser->id,
                'roles' => $updatedUser->getRoleNames()->values(),
            ],
        ]);
    }
}