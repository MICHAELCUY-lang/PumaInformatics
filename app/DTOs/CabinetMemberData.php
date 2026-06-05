<?php

namespace App\DTOs;

class CabinetMemberData
{
    public function __construct(
        public readonly string $name,
        public readonly string $roleTitle,
        public readonly string $termYear,
        public readonly ?int $departmentId = null,
        public readonly ?int $cabinetId = null,
        public readonly int $roleHierarchyLevel = 100,
        public readonly bool $isActive = true,
        public readonly ?string $biography = null,
        public readonly ?array $achievements = null,
        public readonly ?array $socialLinks = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            roleTitle: $data['role_title'],
            termYear: $data['term_year'],
            departmentId: $data['department_id'] ?? null,
            cabinetId: $data['cabinet_id'] ?? null,
            roleHierarchyLevel: (int) ($data['role_hierarchy_level'] ?? 100),
            isActive: (bool) ($data['is_active'] ?? true),
            biography: $data['biography'] ?? null,
            achievements: $data['achievements'] ?? null,
            socialLinks: $data['social_links'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'role_title' => $this->roleTitle,
            'term_year' => $this->termYear,
            'department_id' => $this->departmentId,
            'cabinet_id' => $this->cabinetId,
            'role_hierarchy_level' => $this->roleHierarchyLevel,
            'is_active' => $this->isActive,
            'biography' => $this->biography,
            'achievements' => $this->achievements,
            'social_links' => $this->socialLinks,
        ];
    }
}
