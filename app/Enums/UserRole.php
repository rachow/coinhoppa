<?php
/**
 *  @author: $rachow
 *  @copyright: Coinhoppa
 *
 *  User Role enums
 */

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case TRADER = 'trader';
}
