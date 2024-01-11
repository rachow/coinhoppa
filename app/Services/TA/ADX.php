<?php
/**
*   @author:    $rachow
*   @copyright: Coinhoppa
*
*   Average Directional Index (ADX)
*
*   ADX calculates the potential strength of a trend.
*   Fluctuates from 0 to 100, with readings below 20 indicating a weak trend, and readings above 50 signaling a 
*   strong trend.
*
*   ADX => can be used to confirm whether pair could continue in current trend or not.
*   ADX => can be used to determine when one should close trade early. For instance when ADX starts to slide below 50.
*
*/

namespace App\Services\TA;

use Log;
use App\Contracts\TA\TechnicalAnalysisInterface;
use App\Abstracts\TA\TechnicalAnalysis;

class ADX extends TechnicalAnalysis implements TechnicalAnalysisInterface
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
     * Run average directional index (ADX) on data.
     *
	 * @param  $pair
	 * @param  $data
	 * @param  $period
	 * @return int
	*/
	public function run($pair = 'BTC/USD', $data = null, $period = 14)
	{
		if (empty($data)) {
			$data = $this->getLatestTradeData($pair);
		}
		$this->data = $data;
		$adx = trader_adx($data['high'], $data['low'], $data['close'], $period);

		if (empty($adx)) {
			return -9;
		}

		$adx = array_pop($adx); // [count($adx) -1];

		if ($adx > 50) {
			return -1; # overbought
		}

		if ($adx < 20) {
			return 1; # underbought
		}
		return 0;
	}
}
