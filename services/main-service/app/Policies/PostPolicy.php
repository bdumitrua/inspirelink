<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\Team;
use App\Models\User;
use App\Repositories\Team\Interfaces\TeamMemberRepositoryInterface;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;

class PostPolicy
{
    protected $teamMemberRepository;

    public function __construct(
        TeamMemberRepositoryInterface $teamMemberRepository,
    ) {
        $this->teamMemberRepository = $teamMemberRepository;
    }

    /**
     * Determine whether the user can create the post.
     * 
     * @see CREATE_POST_GATE
     */
    public function createPost(User $user, string $entityType, int $entityId): bool
    {
        return $this->getRights($user, $entityType, $entityId);
    }

    /**
     * Determine whether the user can update the post.
     * 
     * @see UPDATE_POST_GATE
     */
    public function updatePost(User $user, Post $post): bool
    {
        return $this->getRights($user, $post->entity_type, $post->entity_id);
    }

    /**
     * Determine whether the user can delete the post.
     * 
     * @see DELETE_POST_GATE
     */
    public function deletePost(User $user, Post $post): bool
    {
        return $this->getRights($user, $post->entity_type, $post->entity_id);
    }

    private function getRights(User $user, string $entityType, int $entityId): bool
    {
        if ($entityType === config('entities.user')) {
            return $user->id === $entityId;
        }

        if ($entityType === config('entities.team')) {
            return Gate::allows(TOUCH_TEAM_POSTS_GATE, [Team::class, $entityId]);
        }

        return false;
    }
}