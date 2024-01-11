<?php
/**
* 	@author:    $rachow
* 	@copyright: Coinhoppa
*
* 	Commodity Channel Index (CCI)
*
*/

namespace App\Services\TA;

use Log;
use App\Contracts\TA\TechnicalAnalysisInterface;
use App\Abstracts\TA\TechnicalAnalysis;

class CCI extends TechnicalAnalysis implements TechnicalAnalysisInterface
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

		// array $high , array $low , array $close [, integer $timePeriod ]
        $cci = trader_cci($data['high'], $data['low'], $data['close'], $period);
        $cci = array_pop($cci); # [count($cci) - 1];

		if ($cci > 100) {
			return -1;  // overbought
		}

		if($cci < -100) {
			return 1;  // underbought
		}

		return 0;
	}
}