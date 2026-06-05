<?php

namespace App\DTOs;

class ProjectData
{
    public function __construct(
        public readonly string $title,
        public readonly ?int $categoryId = null,
        public readonly string $status = 'draft',
        public readonly bool $isFeatured = false,
        public readonly ?string $excerpt = null,
        public readonly ?string $description = null,
        public readonly ?string $startDate = null,
        public readonly ?string $completionDate = null,
        public readonly ?string $githubUrl = null,
        public readonly ?string $demoUrl = null,
        public readonly ?string $documentationUrl = null,
        public readonly array $technologies = [],
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'],
            categoryId: $data['category_id'] ?? null,
            status: $data['status'] ?? 'draft',
            isFeatured: (bool) ($data['is_featured'] ?? false),
            excerpt: $data['excerpt'] ?? null,
            description: $data['description'] ?? null,
            startDate: $data['start_date'] ?? null,
            completionDate: $data['completion_date'] ?? null,
            githubUrl: $data['github_url'] ?? null,
            demoUrl: $data['demo_url'] ?? null,
            documentationUrl: $data['documentation_url'] ?? null,
            technologies: $data['technologies'] ?? [],
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'category_id' => $this->categoryId,
            'status' => $this->status,
            'is_featured' => $this->isFeatured,
            'excerpt' => $this->excerpt,
            'description' => $this->description,
            'start_date' => $this->startDate,
            'completion_date' => $this->completionDate,
            'github_url' => $this->githubUrl,
            'demo_url' => $this->demoUrl,
            'documentation_url' => $this->documentationUrl,
        ];
    }
}
