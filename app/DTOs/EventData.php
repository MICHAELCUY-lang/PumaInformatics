<?php

namespace App\DTOs;

class EventData
{
    public function __construct(
        public readonly string $title,
        public readonly string $startDate,
        public readonly ?string $endDate = null,
        public readonly string $timezone = 'Asia/Jakarta',
        public readonly ?int $categoryId = null,
        public readonly ?string $description = null,
        public readonly ?string $excerpt = null,
        public readonly string $status = 'draft',
        public readonly bool $isFeatured = false,
        public readonly ?string $locationName = null,
        public readonly ?string $locationAddress = null,
        public readonly ?array $locationCoordinates = null,
        public readonly ?string $externalRegistrationUrl = null,
        public readonly bool $internalRsvpEnabled = false,
        public readonly ?string $metaTitle = null,
        public readonly ?string $metaDescription = null,
        public readonly ?array $tags = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'],
            startDate: $data['start_date'],
            endDate: $data['end_date'] ?? null,
            timezone: $data['timezone'] ?? 'Asia/Jakarta',
            categoryId: $data['category_id'] ?? null,
            description: $data['description'] ?? null,
            excerpt: $data['excerpt'] ?? null,
            status: $data['status'] ?? 'draft',
            isFeatured: $data['is_featured'] ?? false,
            locationName: $data['location_name'] ?? null,
            locationAddress: $data['location_address'] ?? null,
            locationCoordinates: $data['location_coordinates'] ?? null,
            externalRegistrationUrl: $data['external_registration_url'] ?? null,
            internalRsvpEnabled: $data['internal_rsvp_enabled'] ?? false,
            metaTitle: $data['meta_title'] ?? null,
            metaDescription: $data['meta_description'] ?? null,
            tags: $data['tags'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'timezone' => $this->timezone,
            'category_id' => $this->categoryId,
            'description' => $this->description,
            'excerpt' => $this->excerpt,
            'status' => $this->status,
            'is_featured' => $this->isFeatured,
            'location_name' => $this->locationName,
            'location_address' => $this->locationAddress,
            'location_coordinates' => $this->locationCoordinates,
            'external_registration_url' => $this->externalRegistrationUrl,
            'internal_rsvp_enabled' => $this->internalRsvpEnabled,
            'meta_title' => $this->metaTitle,
            'meta_description' => $this->metaDescription,
        ];
    }
}
