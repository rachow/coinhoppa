<?php
/**
 *  @author: $rachow
 *  @copyright: Coinhoppa
 *
 *  Administrator permission sets.
*/

namespace App\Acl;

class AdminPermission
{
    const EDIT_TRADES = 'edit trades';
    const EDIT_TRADERS = 'edit traders';
    const DELETE_TRADERS = 'delete traders';
    const UPDATE_TRADERS = 'update traders';
    const BULK_ADD_TRADES = 'bulk add trades';

    const ADD_EXCHANGE = 'add exchange';
    const EDIT_EXCHANGE = 'edit exchange';
    const DELETE_EXCHANGE = 'delete exchange';
}
