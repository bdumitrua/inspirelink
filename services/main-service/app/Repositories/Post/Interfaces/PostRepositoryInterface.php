<?php

namespace App\Repositories\Post\Interfaces;

use App\DTO\Post\CreatePostDTO;
use App\DTO\Team\UpdateTeamDTO;
use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;

interface PostRepositoryInterface
{
    /**
     * @return Collection
     */
    public function getAll(): Collection;

    /**
     * @param int $userId
     * 
     * @return Collection
     */
    public function feed(int $userId): Collection;

    /**
     * @param int $postId
     * 
     * @return Post|null
     */
    public function getById(int $postId): ?Post;

    /**
     * @param array $postIds
     * 
     * @return Collection
     */
    public function getByIds(array $postIds): Collection;

    /**
     * @param int $userId
     * 
     * @return Collection
     */
    public function getByUserId(int $userId): Collection;

    /**
     * @param int $teamId
     * 
     * @return Collection
     */
    public function getByTeamId(int $teamId): Collection;

    /**
     * @param CreatePostDTO $dto
     * 
     * @return Post
     */
    public function create(CreatePostDTO $dto): Post;

    /**
     * @param Post $post
     * @param UpdateTeamDTO $dto
     * 
     * @return void
     */
    public function update(Post $post, UpdateTeamDTO $dto): void;

    /**
     * @param Post $post
     * 
     * @return void
     */
    public function delete(Post $post): void;
}
