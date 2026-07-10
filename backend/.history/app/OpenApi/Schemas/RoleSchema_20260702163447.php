<?php

namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Role',
    type: 'object',
    required: ['id', 'name', 'slug'],
    properties: [
        new OA\Property(
            property: 'id',
            type: 'integer',
            example: 1
        ),

        new OA\Property(
            property: 'name',
            type: 'string',
            example: 'Administrator'
        ),

        new OA\Property(
            property: 'slug',
            type: 'string',
            example: 'admin'
        ),

        new OA\Property(
            property: 'created_at',
            type: 'string',
            format: 'date-time',
            example: '2026-07-02T12:08:14Z'
        ),

        new OA\Property(
            property: 'updated_at',
            type: 'string',
            format: 'date-time',
            example: '2026-07-02T12:08:14Z'
        ),
    ]
)]
class RoleSchema
{
}