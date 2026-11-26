<?php

namespace TremulantTech\Veeqo\Requests\Tags;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;
use TremulantTech\Veeqo\Data\Tags\CreateTagData;
use TremulantTech\Veeqo\Veeqo;

/**
 * @see https://developers.veeqo.com/api/operations/create-a-new-tag/
 */
class CreateTag extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(protected CreateTagData $data) {}

    public function resolveEndpoint(): string
    {
        return '/tags';
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
