<?php

namespace App\Repositories\User;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\Updateable;
use App\Traits\Cacheable;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use App\Models\User;
use App\DTO\User\UpdateUserDTO;

class UserRepository implements UserRepositoryInterface
{
    use Updateable, Cacheable;

    protected function queryById($userId): Builder
    {
        return User::query()->where('id', '=', $userId);
    }

    public function getById(int $userId): ?User
    {
        Log::debug('Getting user by id', [
            'userId' => $userId
        ]);

        $cacheKey = $this->getUserCacheKey($userId);
        return $this->getCachedData($cacheKey, CACHE_TIME_USER_DATA, function () use ($userId) {
            return $this->queryById($userId)->first();
        });
    }

    public function getByIds(array $usersIds): Collection
    {
        Log::debug('Getting users by ids', [
            'usersIds' => $usersIds
        ]);

        return $this->getCachedCollection($usersIds, function ($userId) {
            return $this->getById($userId);
        });
    }

    public function getByEmail(string $email): ?User
    {
        Log::debug('Getting user by email', [
            'email' => $email
        ]);

        return User::where('email', '=', $email)->first();
    }

    function search(string $query): Collection
    {
        return User::search($query);
    }

    public function update(int $userId, UpdateUserDTO $dto): bool
    {
        Log::debug('Started update user data', [
            'userId' => $userId
        ]);

        $user = $this->queryById($userId)->first();

        $this->updateFromDto($user, $dto);

        Log::debug('User data succesfully updated', [
            'userId' => $userId
        ]);

        return true;
    }

    protected function getUserCacheKey(int $userId): string
    {
        return CACHE_KEY_USER_DATA . $userId;
    }
}
