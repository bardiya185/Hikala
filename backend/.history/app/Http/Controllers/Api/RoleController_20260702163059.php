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
                description: 'List of roles',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'id', type: 'integer', example: 1),
                            new OA\Property(property: 'name', type: 'string', example: 'Administrator'),
                            new OA\Property(property: 'slug', type: 'string', example: 'admin'),
                            new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
                        ]
                    )
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        $role = Role::create($request->validated());
    
        return new RoleResource($role);
    }
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
