<?php
/**
* 	@author:    $rachow
* 	@copyright: Coinhoppa
*
* 	Bollinger Bands (BB)
*
*/

namespace App\Services\TA;

use Log;
use App\Contracts\TA\TechnicalAnalysisInterface;
use App\Abstracts\TA\TechnicalAnalysis;

class BB extends TechnicalAnalysis implements TechnicalAnalysisInterface
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
	 * Run Bollinger Bands (BB) on the data.
	 * 
	 * @param  $pair
	 * @param  $data
	 * @param  $period
	 * @param  $devup
	 * @param  $devdn
	 * @return int
	 */
	public function run($pair = 'BTC/USD', $data = null, $period = 10, $devup = 2, $devdn = 2)
	{
		if (empty($data)) {
			$data = $this->getLatestTradeData($pair);
		}

		$this->data = $temp_data = $data;
		// $prev_close = array_pop($temp_data['close']); #[count($data['close']) - 2]; // prior close

		$current = array_pop($temp_data['close']); // [count($data['close']) - 1];    // we assume this is current

		// array $real [, integer $timePeriod [, float $nbDevUp [, float $nbDevDn [, integer $mAType ]]]]

		$bands = trader_bbands($data['close'], $period, $devup, $devdn, 0);
		$upper = $bands[0];
		
		// $middle = $bands[1]; // one day might be needed.

		$lower = $bands[2];

		// price below recent lower band = buy long
		if ($current <= array_pop($lower)) {
			return 1; 
		}

		// price is above recent upper band = sell or short
		if ($current >= array_pop($upper)) {
			return -1;
		}

		return 0;
	}
}