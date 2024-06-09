<?php

declare(strict_types=1);

namespace App\Enums;

enum MediaContentType: string
{
    case EXTERNAL_VIDEO = 'EXTERNAL_VIDEO';
    case IMAGE = 'IMAGE';
    case MODEL_3D = 'MODEL_3D';
    case VIDEO = 'VIDEO';
}
