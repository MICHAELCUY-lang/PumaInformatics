<?php

namespace App\DTOs;

class ArticleData
{
    public function __construct(
        public readonly string $title,
        public readonly string $status,
        public readonly bool $isFeatured,
        public readonly ?string $content = null,
        public readonly ?string $excerpt = null,
        public readonly ?string $publishedAt = null,
        public readonly ?string $metaTitle = null,
        public readonly ?string $metaDescription = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'],
            status: $data['status'] ?? 'draft',
            isFeatured: $data['is_featured'] ?? false,
            content: $data['content'] ?? null,
            excerpt: $data['excerpt'] ?? null,
            publishedAt: $data['published_at'] ?? null,
            metaTitle: $data['meta_title'] ?? null,
            metaDescription: $data['meta_description'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'content' => $this->content,
            'excerpt' => $this->excerpt,
            'status' => $this->status,
            'is_featured' => $this->isFeatured,
            'published_at' => $this->publishedAt,
            'meta_title' => $this->metaTitle,
            'meta_description' => $this->metaDescription,
        ];
    }
}
