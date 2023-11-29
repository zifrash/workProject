<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }


    #[OA\Post(
        path: "/api/auth/login",
        summary: "Авторизоваться",
        requestBody: new OA\RequestBody(content: new OA\JsonContent(
            required: ['email', 'password'],
            properties: [
                new OA\Property(property: "email", type: "string", example: "test@test.test"),
                new OA\Property(property: "password", type: "string", example: "qwe123")
            ]
        )),
        tags: ["Auth"],
        responses: [
            new OA\Response(response: 200, description: "OK", content: new OA\JsonContent(properties: [
                new OA\Property(property: "access_token", type: "string", example: "qwe.qwe.qwe"),
                new OA\Property(property: "token_type", type: "string", example: "bearer"),
                new OA\Property(property: "expires_in", type: "integer", example: 3600)
            ]))
        ],
    )]
    public function login(): JsonResponse
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(): JsonResponse
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
