<?php

namespace App\DTOs;

class UserData
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly ?string $password,
        public readonly ?string $status,
        public readonly array $roles = []
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            password: $data['password'] ?? null,
            status: $data['status'] ?? 'active',
            roles: $data['roles'] ?? []
        );
    }
    
    public function toArray(): array
    {
        $arr = [
            'name' => $this->name,
            'email' => $this->email,
            'status' => $this->status,
        ];
        
        if ($this->password) {
            $arr['password'] = bcrypt($this->password);
        }
        
        return $arr;
    }
}
