<?php

namespace TremulantTech\Veeqo\Resources;

use Psr\Http\Message\StreamInterface;
use TremulantTech\Veeqo\Enums\LabelFormat;
use TremulantTech\Veeqo\Exceptions\UnexpectedResponse;
use TremulantTech\Veeqo\Requests\Labels\GetLabel;
use TremulantTech\Veeqo\Veeqo;

class Labels
{
    public function __construct(private readonly Veeqo $veeqo) {}

    /**
     * @param  non-empty-list<int>  $shipmentIds
     *
     * @throws UnexpectedResponse on any 2xx but a 200.
     */
    public function get(array $shipmentIds, LabelFormat $format): StreamInterface
    {
        return $this->veeqo->download(new GetLabel($shipmentIds, $format));
    }
}
