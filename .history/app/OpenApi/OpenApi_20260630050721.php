<?php

namespace App\OpenApi;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="HiKala API",
 *     version="1.0.0",
 *     description="E-commerce API"
 * )
 *
 * @OA\Server(
 *     url="http://127.0.0.1:8000"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer"
 * )
 */
class OpenApi {}