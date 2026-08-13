<?php

namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Role',
    type: 'object',
    required: ['id', 'name', 'guard_name'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'admin'),
        new OA\Property(property: 'guard_name', type: 'string', example: 'sanctum'),
        new OA\Property(property: 'permissions_count', type: 'integer', example: 12),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-08-09T12:00:00Z'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-08-09T12:00:00Z'),
    ]
)]
class RoleSchema
{
}