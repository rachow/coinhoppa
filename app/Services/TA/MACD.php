<?php
/**
* 	@author:    $rachow
* 	@copyright: Coinhoppa
*
* 	Moving Average Convergence Divergence (MACD)
*
*/

namespace App\Services\TA;

use App\Contracts\TA\TechnicalAnalysisInterface;
use App\Abstracts\TA\TechnicalAnalysis;

class MACD extends TechnicalAnalysis implements TechnicalAnalysisInterface
{
	/**
	* Creates and instance.
	* @return void
	*/
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Run moving average convergence divergence
	 * on data.
	 *
	 * @param  $pair
	 * @param  $data
	 * @param  $period_1
	 * @param  $period_2
	 * @param  $period_3
	 * @return int
	 */
	public function run($pair = 'BTC/USD', $data = null, $period_1 = 12, $period_2 = 26, $period_3 = 9)
	{
		if (empty($data)) {
			$data = $this->getLatestTradeData($pair);
		}
		$this->data = $data;
		
		/**
		* Create the MACD signal and pass the three params [fast period, slow period, signal] 
		* We will want to tweak these periods later to see the results.
		* data, fast period, slow period, signal period (2-100000)
		*/

		// array $real [, integer $fastPeriod [, integer $slowPeriod [, integer $signalPeriod ]]]
		$macd = trader_macd($data['close'], $period_1, $period_2, $period_3);
		$raw  = $macd[0]; // raw MACD
		$sgnl = $macd[1]; // signal
		$hist = $macd[2];

		if (!$macd || !$raw) { // not enough data
			return 0;
		}
		// $macd = $macd_raw[count($macd_raw)-1] - $signal[count($signal)-1];
		$macd = (array_pop($raw) - array_pop($sgnl));

		// close position for pair when the MACD signal is negative.
		if ($macd < 0) {
			return -1;
		}

		// enter the position for pair when the MACD signal is positive.
		if ($macd > 0) {
			return 1;
		}

		return 0;
	}
}