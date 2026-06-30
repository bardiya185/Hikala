<?php

namespace App\OpenApi;

use OpenApi\Annotations as OA;

/**
 * @OA\PathItem(
 *     path="/api/test-manual"
 * )
 *
 * @OA\Get(
 *     path="/api/test-manual",
 *     summary="Manual test",
 *     @OA\Response(
 *         response=200,
 *         description="OK"
 *     )
 * )
 */
class TestPath {}