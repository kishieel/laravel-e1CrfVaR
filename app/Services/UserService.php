<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Repositories\UsersRepository;

use function config;

use Hash;
use Http;

use function str_replace;

use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

class UserService
{
    public function __construct(private readonly UsersRepository $usersRepository)
    {
    }

    public function importUser(array $data): User
    {
        $uri = str_replace(':id', $data['external_id'], config('app.users_provider.uri'));
        $appId = config('app.users_provider.app_id');

        $response = Http::timeout(30)->withHeader('app-id', $appId)->get($uri);

        if (! $response->successful()) {
            throw new ServiceUnavailableHttpException();
        }

        $data = [
            'external_id' => $response['id'],
            'email' => $response['email'],
            'title' => $response['title'],
            'first_name' => $response['firstName'],
            'last_name' => $response['lastName'],
            'picture' => $response['picture'],
            'password' => Hash::make($data['password']),
        ];

        return $this->usersRepository->createUser($data);
    }

    public function findUniqueByEmail(string $email): User
    {
        return $this->usersRepository->getUniqueByEmail($email);
    }

    public function createUser(array $data): User
    {
        $data['password'] = Hash::make($data['password']);

        return $this->usersRepository->createUser($data);
    }
}
