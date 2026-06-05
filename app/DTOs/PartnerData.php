<?php

namespace App\DTOs;

class PartnerData
{
    public function __construct(
        public readonly string $name,
        public readonly ?int $categoryId = null,
        public readonly ?string $description = null,
        public readonly ?string $websiteUrl = null,
        public readonly ?string $contactEmail = null,
        public readonly int $order = 0,
        public readonly bool $isActive = true,
        public readonly bool $isFeatured = false,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            categoryId: $data['category_id'] ?? null,
            description: $data['description'] ?? null,
            websiteUrl: $data['website_url'] ?? null,
            contactEmail: $data['contact_email'] ?? null,
            order: (int) ($data['order'] ?? 0),
            isActive: (bool) ($data['is_active'] ?? true),
            isFeatured: (bool) ($data['is_featured'] ?? false),
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'category_id' => $this->categoryId,
            'description' => $this->description,
            'website_url' => $this->websiteUrl,
            'contact_email' => $this->contactEmail,
            'order' => $this->order,
            'is_active' => $this->isActive,
            'is_featured' => $this->isFeatured,
        ];
    }
}
