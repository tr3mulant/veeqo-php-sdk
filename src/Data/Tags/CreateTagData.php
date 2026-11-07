<?php

namespace TremulantTech\Veeqo\Data\Tags;

readonly class CreateTagData
{
    /**
     * @param  string  $colour  A hex colour code, such as `#009aed`.
     */
    public function __construct(
        public string $colour,
        public string $name,
    ) {}
}
