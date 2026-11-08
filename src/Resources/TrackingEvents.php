<?php

namespace TremulantTech\Veeqo\Resources;

use TremulantTech\Veeqo\Requests\TrackingEvents\ListTrackingEvents;
use TremulantTech\Veeqo\Responses\TrackingEvent;
use TremulantTech\Veeqo\Veeqo;

class TrackingEvents
{
    public function __construct(private readonly Veeqo $veeqo) {}

    /**
     * Not paginated: Veeqo returns every event on the shipment at once.
     *
     * @return list<TrackingEvent>
     */
    public function list(int $shipmentId): array
    {
        return $this->veeqo->hydrateList(TrackingEvent::class, $this->veeqo->send(new ListTrackingEvents($shipmentId)));
    }
}
