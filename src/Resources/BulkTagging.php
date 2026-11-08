<?php

namespace TremulantTech\Veeqo\Resources;

use TremulantTech\Veeqo\Data\BulkTagging\CreateBulkTaggingData;
use TremulantTech\Veeqo\Data\BulkTagging\DeleteBulkTaggingData;
use TremulantTech\Veeqo\Requests\BulkTagging\CreateBulkTagging;
use TremulantTech\Veeqo\Requests\BulkTagging\DeleteBulkTagging;
use TremulantTech\Veeqo\Veeqo;

class BulkTagging
{
    public function __construct(private readonly Veeqo $veeqo) {}

    public function create(CreateBulkTaggingData $data): void
    {
        $this->veeqo->send(new CreateBulkTagging($data));
    }

    public function delete(DeleteBulkTaggingData $data): void
    {
        $this->veeqo->send(new DeleteBulkTagging($data));
    }
}
