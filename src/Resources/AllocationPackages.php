<?php

namespace TremulantTech\Veeqo\Resources;

use TremulantTech\Veeqo\Data\AllocationPackages\UpdateAllocationPackageData;
use TremulantTech\Veeqo\Requests\AllocationPackages\UpdateAllocationPackage;
use TremulantTech\Veeqo\Responses\AllocationPackage;
use TremulantTech\Veeqo\Veeqo;

class AllocationPackages
{
    public function __construct(private readonly Veeqo $veeqo) {}

    public function update(int $allocationId, UpdateAllocationPackageData $data): AllocationPackage
    {
        return $this->veeqo->hydrateJsonApi(AllocationPackage::class, $this->veeqo->send(new UpdateAllocationPackage($allocationId, $data)));
    }
}
