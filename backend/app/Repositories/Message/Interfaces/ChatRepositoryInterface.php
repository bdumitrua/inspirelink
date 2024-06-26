<?php

namespace App\Repositories\Message\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Models\GroupChat;
use App\Models\DialogChat;
use App\Models\Chat;
use App\DTO\Message\UpdateChatDTO;
use App\DTO\Message\CreateChatDTO;

interface ChatRepositoryInterface
{
    /**
     * @param Chat $chat
     * 
     * @return GroupChat|DialogChat
     */
    public function getChatData(Chat $chat);

    /**
     * @param array $chatIds
     * 
     * @return Collection
     */
    public function getDataByIds(array $chatIds): Collection;

    /**
     * @param int $teamId
     * 
     * @return Chat|null
     */
    public function getChatByTeamId(int $teamId): ?Chat;

    /**
     * @param string $query
     * 
     * @return Collection
     */
    public function search(string $query): Collection;

    /**
     * @param CreateChatDTO $createChatDTO
     * 
     * @return Chat
     */
    public function create(CreateChatDTO $createChatDTO): Chat;

    /**
     * @param Chat $chat
     * @param UpdateChatDTO $updateChatDTO
     * 
     * @return void
     */
    public function update(Chat $chat, UpdateChatDTO $updateChatDTO): void;
}
