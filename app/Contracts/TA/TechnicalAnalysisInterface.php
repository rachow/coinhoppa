<?php
/**
 *  @author: $rachow
 *  @copyright: Coinhoppa
 *
 *  Interface for TA.
 */

namespace App\Contracts\TA;

interface TechnicalAnalysisInterface
{
    /**
     * run the indicator rules and algos.
     */
    public function run();
}
