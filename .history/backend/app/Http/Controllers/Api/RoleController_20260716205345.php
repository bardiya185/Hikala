<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use App\Http\Requests\UpdateRoleRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

class RoleController extends Controller
{
    // لیست اسلاگ‌های سیستمی که به هیچ وجه نباید حذف شوند
    private const PROTECTED_SLUGS = ['admin', 'super-admin', 'user'];

    #[OA\Get(
        path: '/api/roles',
        operationId: 'getRoles',
        summary: 'Get all roles',
        description: 'Returns a paginated list of all roles.',
        tags: ['Roles'],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', default: 15))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Roles retrieved successfully.',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/Role')
                )
            )
        ]
    )]
    public function index(Request $request): AnonymousResourceCollection
    {
        // استفاده از paginate به جای all() برای مدیریت مصرف حافظه سرور در مقیاس بالا
        $perPage = $request->integer('per_page', 15);
        $roles = Role::orderBy('id', 'desc')->paginate($perPage);

        return RoleResource::collection($roles);
    }

    #[OA\Post(
        path: '/api/roles',
        operationId: 'storeRole',
        summary: 'Create a new role',
        description: 'Creates a new role.',
        tags: ['Roles'],
        security: [
            ["bearerAuth" => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'slug'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Administrator'),
                    new OA\Property(property: 'slug', type: 'string', example: 'admin')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Role created successfully.', content: new OA\JsonContent(ref: '#/components/schemas/Role')),
            new OA\Response(response: 422, description: 'Validation error.')
        ]
    )]
    public function store(StoreRoleRequest $request): JsonResponse
    {
        $role = Role::create($request->validated());
    
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
        parameters: [
            new OA\Parameter(name: 'role', in: 'path', required: true, description: 'Role ID', schema: new OA\Schema(type: 'integer', example: 1))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Role retrieved successfully.', content: new OA\JsonContent(ref: '#/components/schemas/Role')),
            new OA\Response(response: 404, description: 'Role not found.')
        ]
    )]
    public function show(Role $role): RoleResource
    {
        // در صورت نیاز به لود کردن دسترسی‌ها، می‌توانید از این روش استفاده کنید:
        // $role->load('permissions');
        
        return new RoleResource($role);
    }

    #[OA\Put(
        path: '/api/roles/{role}',
        operationId: 'updateRole',
        summary: 'Update a role',
        description: 'Updates an existing role.',
        tags: ['Roles'],
        parameters: [
            new OA\Parameter(name: 'role', in: 'path', required: true, description: 'Role ID', schema: new OA\Schema(type: 'integer', example: 1))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'slug'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Super Administrator'),
                    new OA\Property(property: 'slug', type: 'string', example: 'admin')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Role updated successfully.', content: new OA\JsonContent(ref: '#/components/schemas/Role')),
            new OA\Response(response: 404, description: 'Role not found.'),
            new OA\Response(response: 422, description: 'Validation error.')
        ]
    )]
    public function update(UpdateRoleRequest $request, Role $role): RoleResource
    {
        // محافظت اختیاری: جلوگیری از تغییر مکرر اسلاگ‌های اصلی سیستم در صورت تمایل
        $role->update($request->validated());

        return new RoleResource($role);
    }

    #[OA\Delete(
        path: '/api/roles/{role}',
        operationId: 'deleteRole',
        summary: 'Delete a role',
        description: 'Deletes an existing role.',
        tags: ['Roles'],
        parameters: [
            new OA\Parameter(name: 'role', in: 'path', required: true, description: 'Role ID', schema: new OA\Schema(type: 'integer', example: 1))
        ],
        responses: [
            new OA\Response(response: 204, description: 'Role deleted successfully.'),
            new OA\Response(response: 400, description: 'Bad request / Protected role.'),
            new OA\Response(response: 404, description: 'Role not found.')
        ]
    )]
    public function destroy(Role $role): JsonResponse|Response
    {
        // ۱. مکانیزم امنیتی: محافظت از نقش‌های حیاتی سیستم
        if (in_array($role->slug, self::PROTECTED_SLUGS)) {
            return response()->json([
                'success' => false,
                'message' => 'Core system roles cannot be deleted.'
            ], 400);
        }

        // ۲. بررسی وابستگی: آیا کاربری به این نقش متصل است؟ (با فرض وجود ریلیشن users)
        if ($role->users()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete role. It is currently assigned to one or more users.'
            ], 400);
        }

        $role->delete();
    
        return response()->noContent();
    }
}