<?php
/**
* 	@author:    $rachow
* 	@copyright: Coinhoppa
* 
*	Average True Run (ATR)
*
*/

namespace App\Services\TA;

use Log;
use App\Contracts\TA\TechnicalAnalysisInterface;
use App\Abstracts\TA\TechnicalAnalysis;

class ATR extends TechnicalAnalysis implements TechnicalAnalysisInterface
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
	 * Run average true run on data.
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
		if ($period > count($data['close'])) {
			$period = round(count($data['close']) / 2);
		}
		$temp_data = $data;
		$curr = array_pop($temp_data['close']); // [count($data['close']) -1];
		$prev = array_pop($temp_data['close']); // [count($data['close']) -2];

		$atr = trader_atr($data['high'], $data['low'], $data['close'], $period);
		$atr = array_pop($atr); // [count($atr) -1];

		// upside breakout occurs when the price goes 1 ATR above previous price
		$upside = ($curr - ($prev + $atr));

		// downside breakout occurs when the previous close is 1 ATR above the price
		$downside = ($prev - ($curr + $atr));

		if ($upside > 0) {
			return 1; // signal to buy.
		}

		if ($downside > 0) {
			return -1; // signal to sell.
		}

		return 0;
	}
}