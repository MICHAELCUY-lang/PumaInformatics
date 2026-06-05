<?php

namespace App\DTOs;

class VotingSessionData
{
    public function __construct(
        public readonly string $title,
        public readonly ?string $description,
        public readonly string $status,
        public readonly string $results_visibility,
        public readonly ?string $start_date,
        public readonly ?string $end_date
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'],
            description: $data['description'] ?? null,
            status: $data['status'] ?? 'draft',
            results_visibility: $data['results_visibility'] ?? 'private',
            start_date: $data['start_date'] ?? null,
            end_date: $data['end_date'] ?? null
        );
    }
    
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'results_visibility' => $this->results_visibility,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ];
    }
}
