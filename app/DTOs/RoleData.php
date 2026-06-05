<?php

namespace App\DTOs;

class RoleData
{
    public function __construct(
        public readonly string $name,
        public readonly array $permissions = []
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            permissions: $data['permissions'] ?? []
        );
    }
    
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'guard_name' => 'web',
        ];
    }
}
