<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use App\Http\Requests\UpdateRoleRequest;

class RoleController extends Controller
{


    #[OA\Get(
        path: '/api/roles',
        operationId: 'getRoles',
        summary: 'Get all roles',
        description: 'Returns a list of all roles.',
        tags: ['Roles'],
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
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();

        return RoleResource::collection($roles);
    }

    #[OA\Post(
        path: '/api/roles',
        operationId: 'storeRole',
        summary: 'Create a new role',
        description: 'Creates a new role.',
        tags: ['Roles'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'slug'],
                properties: [
                    new OA\Property(
                        property: 'name',
                        type: 'string',
                        example: 'Administrator'
                    ),
                    new OA\Property(
                        property: 'slug',
                        type: 'string',
                        example: 'admin'
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Role created successfully.',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/Role'
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error.'
            )
        ]
    )]
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
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
            new OA\Parameter(
                name: 'role',
                in: 'path',
                required: true,
                description: 'Role ID',
                schema: new OA\Schema(
                    type: 'integer',
                    example: 1
                )
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Role retrieved successfully.',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/Role'
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Role not found.'
            )
        ]
    )]
    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        return new RoleResource($role);
    }
    /**
     * Update the specified resource in storage.
     */public function update(UpdateRoleRequest $request, Role $role)
{
    $role->update($request->validated());

    return new RoleResource($role);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $role->delete();
    
        return response()->noContent();
    }
}
