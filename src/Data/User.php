<?php

namespace TremulantTech\Veeqo\Data;

final readonly class User
{
    public function __construct(
        public int $id,
        public string $login,
        public string $email,
    ) {}
}
