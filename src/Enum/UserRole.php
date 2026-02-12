<?php

namespace App\Enum;

enum UserRole: string
{
    case CUSTOMER = 'ROLE_CUSTOMER';
    case WORKER   = 'ROLE_WORKER';
}