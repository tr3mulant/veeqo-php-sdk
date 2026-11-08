<?php

namespace TremulantTech\Veeqo\Resources;

use Psr\Http\Message\StreamInterface;
use TremulantTech\Veeqo\Enums\LabelFormat;
use TremulantTech\Veeqo\Requests\Labels\GetLabel;
use TremulantTech\Veeqo\Veeqo;

class Labels
{
    public function __construct(private readonly Veeqo $veeqo) {}

    /**
     * @param  list<int>  $shipmentIds
     */
    public function get(array $shipmentIds, LabelFormat $format): StreamInterface
    {
        return $this->veeqo->send(new GetLabel($shipmentIds, $format))->stream();
    }
}
