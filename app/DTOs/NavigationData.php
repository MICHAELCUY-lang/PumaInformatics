<?php

namespace App\DTOs;

class NavigationData
{
    public function __construct(
        public readonly string $name,
        public readonly string $url,
        public readonly bool $isExternal,
        public readonly bool $isActive,
        public readonly ?int $parentId = null,
        public readonly ?array $visibilityRoles = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            url: $data['url'],
            isExternal: $data['is_external'] ?? false,
            isActive: $data['is_active'] ?? true,
            parentId: $data['parent_id'] ?? null,
            visibilityRoles: $data['visibility_roles'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'url' => $this->url,
            'is_external' => $this->isExternal,
            'is_active' => $this->isActive,
            'parent_id' => $this->parentId,
            'visibility_roles' => $this->visibilityRoles,
        ];
    }
}
