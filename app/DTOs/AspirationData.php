<?php

namespace App\DTOs;

class AspirationData
{
    public function __construct(
        public readonly string $subject,
        public readonly string $payload,
        public readonly ?int $categoryId = null,
        public readonly ?int $userId = null,
        public readonly bool $isAnonymous = false,
        public readonly string $status = 'pending',
        public readonly string $visibility = 'private',
        public readonly ?string $ipHash = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            subject: strip_tags($data['subject']), // Basic sanitization
            payload: strip_tags($data['payload']),
            categoryId: $data['category_id'] ?? null,
            userId: !empty($data['is_anonymous']) ? null : ($data['user_id'] ?? null), // Strip user if anonymous
            isAnonymous: (bool) ($data['is_anonymous'] ?? false),
            status: $data['status'] ?? 'pending',
            visibility: $data['visibility'] ?? 'private',
            ipHash: $data['ip_hash'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'subject' => $this->subject,
            'payload' => $this->payload,
            'category_id' => $this->categoryId,
            'user_id' => $this->userId,
            'is_anonymous' => $this->isAnonymous,
            'status' => $this->status,
            'visibility' => $this->visibility,
            'ip_hash' => $this->ipHash,
        ];
    }
}
