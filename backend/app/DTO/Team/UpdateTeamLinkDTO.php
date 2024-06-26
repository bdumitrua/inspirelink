<?php

namespace App\DTO\Team;

class UpdateTeamLinkDTO
{
    public string $url;
    public bool $isPrivate;

    public ?string $text = null;
    public ?string $iconType = null;

    public function toArray(): array
    {
        return [
            'is_private' => $this->isPrivate,
            'url' => $this->url,
            'text' => $this->text,
            'icon_type' => $this->iconType,
        ];
    }
}
