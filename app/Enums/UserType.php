<?php
/**
 *  @author: $rachow
 *  @copyright: Coinhoppa
 *
 *  User Type enums
 */

namespace App\Enums;

enum UserType: string
{
    case ADMIN = 'admin';
    case TRADER = 'trader';
}
