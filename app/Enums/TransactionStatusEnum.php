<?php

namespace App\Enums;

enum TransactionStatusEnum: string
{
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case PENDING = 'pending';
}
