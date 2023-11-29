<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\UserCollection;
use App\Http\Requests\GetListRequest;
use App\Http\Requests\User\UserCreateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Hash;
use OpenApi\Attributes as OA;

class UserController extends Controller
{
    public function getList(GetListRequest $request): ResourceCollection
    {
        $validated = $request->validated();

        $perPage = $validated['per_page'] ?? 10;
        $sort = $validated['sort'] ?? 'name-asc';

        $user = User::query();
        if ($sort === 'name-asc') { $user->orderBy('name'); }
        else if ($sort === 'name-dsc') { $user->orderByDesc('name'); }

        return new UserCollection($user->paginate($perPage));
    }

    public function get(User $user): UserResource
    {
        return UserResource::make($user);
    }

    #[OA\Post(
        path: "/api/users/",
        summary: "Создать пользователя",
        requestBody: new OA\RequestBody(content: new OA\JsonContent(
            required: ['name', 'email', 'password'],
            properties: [
                new OA\Property(property: 'name', type: 'string', example: 'test'),
                new OA\Property(property: 'email', type: 'string', example: 'test@test.test'),
                new OA\Property(property: 'password', type: 'string', example: 'qwe123'),
            ]
        )),
        tags: ["Users"],
        responses: [
            new OA\Response(response: 201, description: "OK", content: new OA\JsonContent(properties: [
                new OA\Property(property: "id", type: "integer", example: "1"),
                new OA\Property(property: "name", type: "string", example: "test"),
                new OA\Property(property: "email", type: "email", example: "test@test.test")
            ])),
            new OA\Response(response: 422, description: "Unprocessable Content", content: new OA\JsonContent(properties: [
                new OA\Property(property: "message", type: "string", example: "The email has already been taken.")
            ]))
        ],
    )]
    public function create(UserCreateRequest $request): UserResource
    {
        $validated = $request->validated();

        if (User::whereEmail($validated['email'])->exists()) {
            throw new \Exception('The user email exists');
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password']
        ]);

        return UserResource::make($user);
    }

    public function update(UserUpdateRequest $request, User $user): UserResource
    {
        $validated = $request->validated();

        if (key_exists('name', $validated) && $validated['name'] !== $user->name) {
            $user->name = $validated['name'];
        }
        if (key_exists('email', $validated) && $validated['email'] !== $user->email) {
            if (User::whereEmail($validated['email'])->exists()) {
                throw new \Exception('The user email exists');
            }

            $user->email = $validated['email'];
        }
        if (key_exists('password', $validated) && !Hash::check($validated['password'], $user->password)) {
            $user->password = $validated['password'];
        }

        if ($user->isDirty()) { $user->save(); }

        return UserResource::make($user);
    }

    public function delete(User $user): void
    {
        $user->delete();
    }
}
