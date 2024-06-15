<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Database\Eloquent\Collection;
use App\Traits\SetAdditionalData;
use App\Services\Interfaces\LikeServiceInterface;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use App\Repositories\Post\Interfaces\PostRepositoryInterface;
use App\Repositories\Post\Interfaces\PostCommentRepositoryInterface;
use App\Repositories\Interfaces\LikeRepositoryInterface;
use App\Models\User;
use App\Models\Post;
use App\Models\Like;
use App\Models\Interfaces\Likeable;
use App\Exceptions\NotFoundException;
use App\DTO\LikeDTO;

class LikeService implements LikeServiceInterface
{
    use SetAdditionalData;

    protected $userRepository;
    protected $likeRepository;
    protected $postRepository;
    protected $postCommentRepository;
    protected ?int $authorizedUserId;

    public function __construct(
        UserRepositoryInterface $userRepository,
        LikeRepositoryInterface $likeRepository,
        PostRepositoryInterface $postRepository,
        PostCommentRepositoryInterface $postCommentRepository,
    ) {
        $this->userRepository = $userRepository;
        $this->likeRepository = $likeRepository;
        $this->postRepository = $postRepository;
        $this->postCommentRepository = $postCommentRepository;
        $this->authorizedUserId = Auth::id();
    }

    public function user(User $user): JsonResource
    {
        $likes = $this->likeRepository->getByUser($user);
        $likes = $this->assembleLikesData($likes);

        return JsonResource::collection($likes);
    }

    public function post(Post $post): JsonResource
    {
        $likes = $this->likeRepository->getByPost($post);
        $likes = $this->assembleLikesData($likes);

        return JsonResource::collection($likes);
    }

    public function create(LikeDTO $likeDTO): void
    {
        $likeableModel = $this->likeRepository->getLikeableByDTO($likeDTO);
        if (empty($likeableModel)) {
            throw new NotFoundException('Model to like');
        }

        Gate::authorize(
            'create',
            [
                Like::class,
                $likeDTO->likeableType,
                $likeDTO->likeableId
            ]
        );

        $this->likeRepository->create($this->authorizedUserId, $likeableModel);
    }

    public function delete(LikeDTO $likeDTO): void
    {
        $like = $this->likeRepository->getByDTO($likeDTO);
        if (empty($like)) {
            throw new NotFoundException('Like');
        }

        Gate::authorize('delete', [Like::class, $like]);

        $this->likeRepository->delete($like);
    }

    protected function assembleLikesData(Collection $likes): Collection
    {
        $this->setCollectionEntityData($likes, 'user_id', 'userData', $this->userRepository);

        return $likes;
    }
}