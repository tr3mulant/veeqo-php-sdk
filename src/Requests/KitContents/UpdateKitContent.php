<?php

namespace TremulantTech\Veeqo\Requests\KitContents;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;
use TremulantTech\Veeqo\Data\KitContents\UpdateKitContentData;
use TremulantTech\Veeqo\Veeqo;

/**
 * @see https://developers.veeqo.com/api/operations/update-bundle-content/
 */
class UpdateKitContent extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    public function __construct(protected int $kitId, protected int $id, protected UpdateKitContentData $data) {}

    public function resolveEndpoint(): string
    {
        return "/kits/{$this->kitId}/kit_contents/{$this->id}";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return ['kit_content' => Veeqo::normalize($this->data)];
    }
}
