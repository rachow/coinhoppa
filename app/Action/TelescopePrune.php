<?php
/**
 *  @author: $rachow
 *  @copyright: \2023
 *  
 *  Telescope pruning for dev/QA environments.
 *
 *      - crontab on QA: /etc/cron
 */

namespace App\Action;

use Illuminate\Console\Scheduling\Schedule;

final class TelescopePrune 
{
    public function __invoke(Schedule $schedule)
    {
        $schedule->command('telescope:prune --hours=48')->daily();       
    }
}
