<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Get(
    path: '/api/test',
    description: 'مسیر آزمایشی برای رفع خطای Swagger',
    responses: [
        new OA\Response(response: 200, description: 'Successful response')
    ]
)]
class Paths
{
}