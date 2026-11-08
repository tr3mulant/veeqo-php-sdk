<?php

namespace TremulantTech\Veeqo\Responses;

readonly class PrintOption
{
    /**
     * @param  list<SupportedDocumentDetail>|null  $supportedDocumentDetails
     * @param  list<bool>|null  $supportedFileJoiningOptions
     * @param  list<string>|null  $supportedPageLayouts
     */
    public function __construct(
        public ?array $supportedDocumentDetails = null,
        public ?array $supportedFileJoiningOptions = null,
        public ?array $supportedPageLayouts = null,
    ) {}
}
