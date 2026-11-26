<?php

namespace TremulantTech\Veeqo\Requests\KitContents;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;
use TremulantTech\Veeqo\Data\KitContents\CreateKitContentData;
use TremulantTech\Veeqo\Veeqo;

/**
 * @see https://developers.veeqo.com/api/operations/create-bundle-content/
 */
class CreateKitContent extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(protected int $kitId, protected CreateKitContentData $data) {}

    public function resolveEndpoint(): string
    {
        return "/kits/{$this->kitId}/kit_contents";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return ['kit_content' => Veeqo::normalize($this->data)];
    }
}
