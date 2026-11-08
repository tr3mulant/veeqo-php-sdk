<?php

namespace TremulantTech\Veeqo\Requests\TrackingEvents;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * @see https://developers.veeqo.com/api/operations/view-tracking-events/
 */
class ListTrackingEvents extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected int $shipmentId) {}

    public function resolveEndpoint(): string
    {
        return "/shipping/tracking_events/{$this->shipmentId}";
    }
}
