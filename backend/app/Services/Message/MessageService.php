<?php

namespace App\Services\Message;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\AttachEntityData;
use App\Services\Message\Interfaces\MessageServiceInterface;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use App\Repositories\Message\Interfaces\MessageRepositoryInterface;
use App\Models\Chat;
use App\Http\Resources\Message\MessageResource;
use App\Http\Requests\Message\CreateMesssageRequest;
use App\Helpers\StringHelper;
use App\DTO\Message\CreateMesssageDTO;

class MessageService implements MessageServiceInterface
{
    use AttachEntityData;

    protected $userRepository;
    protected $messageRepository;
    protected ?int $authorizedUserId;

    public function __construct(
        UserRepositoryInterface $userRepository,
        MessageRepositoryInterface $messageRepository,
    ) {
        $this->userRepository = $userRepository;
        $this->messageRepository = $messageRepository;
        $this->authorizedUserId = Auth::id();
    }

    public function chat(Chat $chat): JsonResource
    {
        $messages = $this->messageRepository->getByChatId($chat->id);
        $this->setCollectionEntityData($messages, 'senderId', 'senderData', $this->userRepository);

        return MessageResource::collection($messages);
    }

    public function search(string $query): JsonResource
    {
        $messages = $this->messageRepository->search($query);
        $this->setCollectionEntityData($messages, 'senderId', 'senderData', $this->userRepository);

        return MessageResource::collection($messages);
    }

    public function send(Chat $chat, CreateMesssageDTO $createMesssageDTO): void
    {
        Gate::authorize('sendMessage', [Chat::class, $chat, $createMesssageDTO]);

        $newMessageUuid = StringHelper::generateMessageUuid($chat->id, $createMesssageDTO->senderId);
        $this->messageRepository->create($chat->id, $newMessageUuid, $createMesssageDTO);
    }

    public function read(Chat $chat, string $messageUuid): void
    {
        Gate::authorize('readMessage', [Chat::class, $chat, $messageUuid]);

        $this->messageRepository->read($chat->id, $messageUuid);
    }

    public function delete(Chat $chat, string $messageUuid): void
    {
        Gate::authorize('deleteMessage', [Chat::class, $chat, $messageUuid]);

        $this->messageRepository->delete($chat->id, $messageUuid);
    }
}
