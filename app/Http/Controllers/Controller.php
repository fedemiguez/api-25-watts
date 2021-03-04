<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    /**
    * @OA\Info(title="API 25 Watts", version="1.0")
    *
    * @OA\Server(url="http://localhost:8000")
    */

    /**
     * @OA\SecurityScheme(
     *     type="http",
     *     description="Login con email y password, devuelve access_token",
     *     name="Passport Token",
     *     in="header",
     *     scheme="bearer",
     *     bearerFormat="JWT",
     *     securityScheme="passport",
     * )
    */
}
