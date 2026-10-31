<?php

namespace TremulantTech\Veeqo\Requests\AllocationPackages;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;
use TremulantTech\Veeqo\Data\AllocationPackages\UpdateAllocationPackageData;
use TremulantTech\Veeqo\Veeqo;

/**
 * @see https://developers.veeqo.com/api/operations/update-allocation-package/
 */
class UpdateAllocationPackage extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    public function __construct(protected int $allocationId, protected UpdateAllocationPackageData $data) {}

    public function resolveEndpoint(): string
    {
        return "/allocations/{$this->allocationId}/allocation_package";
    }

    /**
     * `save_for_similar_shipments` sits beside the envelope, as the docs show (ADR 0010).
     *
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        $package = Veeqo::normalize($this->data);
        $save = $package['save_for_similar_shipments'] ?? null;
        unset($package['save_for_similar_shipments']);

        return ['allocation_package' => $package, ...($save === null ? [] : ['save_for_similar_shipments' => $save])];
    }
}
