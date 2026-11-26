<?php

namespace TremulantTech\Veeqo\Enums;

/**
 * The `format` extension on GET /shipping/labels{format}.
 */
enum LabelFormat: string
{
    case Pdf = '.pdf';
    case Png = '.png';
    case Jpeg = '.jpeg';
    case Zpl = '.zpl';
}
