<?php
/**
* 	@author:    $rachow
* 	@copyright: Coinhoppa
*
* 	Moving Averages (MA)
*
*/

namespace App\Services\TA;

use App\Contracts\TA\TechnicalAnalysisInterface;
use App\Abstracts\TA\TechnicalAnalysis;

class MA extends TechnicalAnalysis implements TechnicalAnalysisInterface
{
	/**
	 * Creates and instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Run moving averages on data.
	 *
	 * @param  $pair
	 * @param  $data
	 * @return int 
	 * 
	*/
	public function run($pair = 'BTC/USD', $data = null)
	{
		// todo
		return 0;
	}
}