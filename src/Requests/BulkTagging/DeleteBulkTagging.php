<?php

namespace TremulantTech\Veeqo\Requests\BulkTagging;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;
use TremulantTech\Veeqo\Data\BulkTagging\DeleteBulkTaggingData;
use TremulantTech\Veeqo\Veeqo;

/**
 * @see https://developers.veeqo.com/api/operations/bulk-untagging/
 */
class DeleteBulkTagging extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::DELETE;

    public function __construct(protected DeleteBulkTaggingData $data) {}

    public function resolveEndpoint(): string
    {
        return '/bulk_tagging';
    }

    /**
     * Unenveloped, as the spec documents.
     *
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return Veeqo::normalize($this->data);
    }
}
