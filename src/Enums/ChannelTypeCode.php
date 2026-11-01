<?php

namespace TremulantTech\Veeqo\Enums;

/**
 * The channel types `channels()->create()` accepts; `Channel::$typeCode` stays a string (ADR 0007).
 */
enum ChannelTypeCode: string
{
    case Direct = 'direct';
    case Retail = 'retail';
}
