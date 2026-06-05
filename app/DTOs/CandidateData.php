<?php

namespace App\DTOs;

class CandidateData
{
    public function __construct(
        public readonly int $voting_session_id,
        public readonly string $name,
        public readonly ?string $vision,
        public readonly ?string $mission,
        public readonly ?string $biography,
        public readonly ?string $achievements,
        public readonly ?array $social_links,
        public readonly int $order,
        public readonly bool $is_featured
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            voting_session_id: $data['voting_session_id'],
            name: $data['name'],
            vision: $data['vision'] ?? null,
            mission: $data['mission'] ?? null,
            biography: $data['biography'] ?? null,
            achievements: $data['achievements'] ?? null,
            social_links: $data['social_links'] ?? null,
            order: $data['order'] ?? 0,
            is_featured: $data['is_featured'] ?? false
        );
    }
    
    public function toArray(): array
    {
        return [
            'voting_session_id' => $this->voting_session_id,
            'name' => $this->name,
            'vision' => $this->vision,
            'mission' => $this->mission,
            'biography' => $this->biography,
            'achievements' => $this->achievements,
            'social_links' => $this->social_links,
            'order' => $this->order,
            'is_featured' => $this->is_featured,
        ];
    }
}
