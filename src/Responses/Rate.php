<?php

namespace TremulantTech\Veeqo\Responses;

readonly class Rate
{
    /**
     * @param  list<Available>|null  $available
     * @param  list<Unavailable>|null  $unavailable  Empty unless `formatWithUnavailableQuotes: true` is sent.
     * @param  list<LinkedAccount>|null  $linkedAccounts
     */
    public function __construct(
        public ?array $available = null,
        public ?array $unavailable = null,
        public ?array $linkedAccounts = null,
    ) {}
}
