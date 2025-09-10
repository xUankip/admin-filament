<?php

namespace App\Enums;

enum EventStatus: string
{
    case Draft = 'draft';
    case Pending = 'pending';
    case Approved = 'approved';
    case Published = 'published';
    case Completed = 'completed';
    case Canceled = 'canceled';
}



