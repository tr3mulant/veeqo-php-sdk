<?php

namespace TremulantTech\Veeqo\Requests\Notes;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;
use TremulantTech\Veeqo\Data\Notes\CreateNoteData;
use TremulantTech\Veeqo\Veeqo;

/**
 * @see https://developers.veeqo.com/api/operations/create-a-new-order-note/
 */
class CreateNote extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(protected int $orderId, protected CreateNoteData $data) {}

    public function resolveEndpoint(): string
    {
        return "/orders/{$this->orderId}/notes";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return ['note' => Veeqo::normalize($this->data)];
    }
}
