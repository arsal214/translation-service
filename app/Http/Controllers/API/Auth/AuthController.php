<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
        /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Authentication"},
     *     summary="User login",
     *     description="Logs in a user and returns a Sanctum token",
     *     operationId="loginUser",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="superadmin@gmail.com"),
     *             @OA\Property(property="password", type="string", format="password", example="123456789")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful login",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function login(Request $request)
    {
        try {
            if (!Auth::attempt($request->only('email', 'password'))) {
                return $this->sendException('Invalid login details', 400);
            }

            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return $this->sendException('Unauthorized: Please Contact Support', 400);
            }
            $token = $user->createToken('auth_token')->plainTextToken;
            $data['access_token'] = $token;
            $data['token_type'] = 'Bearer';
            return $this->sendResponse($data, 'Login Successfully');
        } catch (QueryException $e) {
            return $this->sendError(null, [$e->getMessage()], 500);
        } catch (\Exception $e) {
            return $this->sendError(null, [$e->getMessage()], 500);
        }
    }
}




