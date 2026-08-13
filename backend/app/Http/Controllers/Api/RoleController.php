<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Roles", description: "Role management")]
class RoleController extends Controller
{
    private const PROTECTED_ROLES = ['admin', 'super-admin', 'user'];

    #[OA\Get(
        path: '/api/roles',
        operationId: 'getRoles',
        summary: 'Get all roles',
        description: 'Returns a paginated list of all roles.',
        tags: ['Roles'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'per_page',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer', default: 15)
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Roles retrieved successfully.'),
            new OA\Response(response: 401, description: 'Unauthenticated.'),
            new OA\Response(response: 403, description: 'Forbidden.'),
        ]
    )]
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = $request->integer('per_page', 15);

        $roles = Role::withCount('permissions')
            ->orderBy('id', 'desc')
            ->paginate($perPage);

        return RoleResource::collection($roles);
    }

    #[OA\Post(
        path: '/api/roles',
        operationId: 'storeRole',
        summary: 'Create a new role',
        description: 'Creates a new role.',
        tags: ['Roles'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'editor')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Role created successfully.'),
            new OA\Response(response: 422, description: 'Validation error.')
        ]
    )]
    public function store(StoreRoleRequest $request): JsonResponse
    {
        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'sanctum',
        ]);

        return (new RoleResource($role))
            ->response()
            ->setStatusCode(201);
    }

    #[OA\Get(
        path: '/api/roles/{role}',
        operationId: 'showRole',
        summary: 'Get role by id',
        description: 'Returns a single role.',
        tags: ['Roles'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'role',
                in: 'path',
                required: true,
                description: 'Role ID',
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Role retrieved successfully.'),
            new OA\Response(response: 404, description: 'Role not found.')
        ]
    )]
    public function show(Role $role): RoleResource
    {
        $role->loadCount('permissions');

        return new RoleResource($role);
    }

    #[OA\Put(
        path: '/api/roles/{role}',
        operationId: 'updateRole',
        summary: 'Update a role',
        description: 'Updates an existing role.',
        tags: ['Roles'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'role',
                in: 'path',
                required: true,
                description: 'Role ID',
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'editor-manager')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Role updated successfully.'),
            new OA\Response(response: 403, description: 'Protected role cannot be modified.'),
            new OA\Response(response: 404, description: 'Role not found.'),
            new OA\Response(response: 422, description: 'Validation error.')
        ]
    )]
    public function update(UpdateRoleRequest $request, Role $role): JsonResponse|RoleResource
    {
        if (in_array($role->name, self::PROTECTED_ROLES, true)) {
            return response()->json([
                'success' => false,
                'message' => 'Core system roles cannot be modified.',
            ], 403);
        }

        $role->update([
            'name' => $request->name,
        ]);

        return new RoleResource($role);
    }

    #[OA\Delete(
        path: '/api/roles/{role}',
        operationId: 'deleteRole',
        summary: 'Delete a role',
        description: 'Deletes an existing role.',
        tags: ['Roles'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'role',
                in: 'path',
                required: true,
                description: 'Role ID',
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(response: 204, description: 'Role deleted successfully.'),
            new OA\Response(response: 400, description: 'Bad request / Protected role / Role is assigned.'),
            new OA\Response(response: 404, description: 'Role not found.')
        ]
    )]
    public function destroy(Role $role): JsonResponse|Response
    {
        if (in_array($role->name, self::PROTECTED_ROLES, true)) {
            return response()->json([
                'success' => false,
                'message' => 'Core system roles cannot be deleted.',
            ], 400);
        }

        if ($role->users()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete role. It is currently assigned to one or more users.',
            ], 400);
        }

        $role->delete();

        return response()->noContent();
    }
}