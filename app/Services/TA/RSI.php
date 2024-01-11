<?php
/**
* 	@author:    $rachow
* 	@copyright: Coinhoppa
* 
*	Relative Strength Index (RSI)
*
* 	RSI > 70 = meaning market is overbought, should look to sell.
* 	RSI < 30 = meaning market is oversold, should look to buy.
* 
* 	RSI can be used to confirm trend formations.
* 	If you think trend is formin, wait for the RSI to go above or
* 	below 50 (depends looking up/down trend) before entering a trade.
*
*/

namespace App\Services\TA;

use App\Contracts\TA\TechnicalAnalysisInterface;
use App\Abstracts\TA\TechnicalAnalysis;

class RSI extends TechnicalAnalysis implements TechnicalAnalysisInterface
{
	// RSI low threshold
	protected $RSI_LOW  = 30;
	
	// RSI high threshold
	protected $RSI_HIGH = 70;

	protected $data;

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
	 * Run relative strength index on data.
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
		// $temp_data = $data;
		// $current = array_pop($temp_data['close']); // $data['close'][count($data['close']) -1];
		// $prev_close = array_pop($temp_data['close']); // $data['close'][count($data['close']) - 2];

		$rsi = trader_rsi($data['close'], $period);
		$rsi = array_pop($rsi);

		// sell the asset indication
		if ($rsi > $this->RSI_HIGH) {
			return -1;
		}

		// look to buy the asset indication
		if ($rsi < $this->RSI_LOW) {
			return 1;
		}

		return 0;
	}
}