<?php

namespace App\Enums;

enum TransferReasonType: string
{
    case Disciplinary = 'disciplinary';
    case Medical = 'medical';
    case Security = 'security';
    case Request = 'request';
    case Administration = 'administration';
    case Other = 'other';
}
