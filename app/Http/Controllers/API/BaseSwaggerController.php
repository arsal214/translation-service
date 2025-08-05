<?php

namespace App\Http\Controllers\API;

use Illuminate\Routing\Controller;

/**
 * @OA\Info(
 *     title="Translation Management API",
 *     version="1.0.0",
 *     description="This API uses Laravel Sanctum for authentication. All requests require a Bearer token in the Authorization header and must send application/json as Content-Type.",
 *     @OA\Contact(email="arsalkamoka786@gmail.com")
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Local API server"
 * )
 *
 * @OA\SecurityScheme(
 *     type="http",
 *     description="Enter bearer token",
 *     name="Authorization",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="sanctum"
 * )
 *
 * @OA\Parameter(
 *     parameter="ContentTypeHeader",
 *     name="Content-Type",
 *     in="header",
 *     required=true,
 *     @OA\Schema(type="string", default="application/json")
 * )
 */

class BaseSwaggerController extends Controller {}

