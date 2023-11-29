<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\TestResponse;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    private ?string $userAccessToken = null;

    public function postJsonAuth(string $uri, array $data = [], array $headers = [], int $options = 0): TestResponse
    {
        return $this->postJson($uri, $data, array_merge(['Authorization' => $this->getUserBearer()], $headers), $options);
    }

    public function putJsonAuth(string $uri, array $data = [], array $headers = [], int $options = 0): TestResponse
    {
        return $this->putJson($uri, $data, array_merge(['Authorization' => $this->getUserBearer()], $headers), $options);
    }

    public function deleteJsonAuth(string $uri, array $data = [], array $headers = [], int $options = 0): TestResponse
    {
        return $this->deleteJson($uri, $data, array_merge(['Authorization' => $this->getUserBearer()], $headers), $options);
    }

    private function getUserBearer(): string
    {
        if ($this->userAccessToken === null) {
            $user = [
                'name' => 'test',
                'email' => 'test@test.test',
                'password' => 'test'
            ];
            User::create($user);

            $this->userAccessToken = $this->postJson("/api/auth/login", [
                'email' => $user['email'],
                'password' => $user['password'],
            ])->json('access_token');
        }

        return 'Bearer ' . $this->userAccessToken;
    }
}
