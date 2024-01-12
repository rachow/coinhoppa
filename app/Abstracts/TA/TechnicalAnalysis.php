<?php
/**
*   @author: $rachow
*   @copyright: Coinhoppa
*
*   Abstract base class for TA indicators.
*
*/

namespace App\Abstracts\TA;

use Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

abstract class TechnicalAnalysis
{
    protected $data;

	protected $movingAverageTypes = [
		'sma'   => TRADER_MA_TYPE_SMA,   // simple moving average
		'ema'   => TRADER_MA_TYPE_EMA,   // exponential moving average
		'wma'   => TRADER_MA_TYPE_WMA,   // weighted moving average
		'dema'  => TRADER_MA_TYPE_DEMA,  // double exponential moving average
		'tema'  => TRADER_MA_TYPE_TEMA,  // triple exponential moving average
		'trima' => TRADER_MA_TYPE_TRIMA, // triangular moving average
		'kama'  => TRADER_MA_TYPE_KAMA,  // kaufman's adaptive moving average
		'mama'  => TRADER_MA_TYPE_MAMA,  // the mother of adaptive moving average
		't3'	=> TRADER_MA_TYPE_T3,	 // the triple exponential moving average 
	];

	protected $techicalIndicators = []; // TA indicators

	/**
     * Creates an instance.
     *
	 * @param none 
	 */
	public function __construct()
	{
        //
	}

	/**
     * Get the constant value for MA (Moving Average).
     *
	 * @param  $ma
	 * @return int
	*/
	public function getMovingAverageTypes($ma = null)
	{
		if (!in_array($ma, $this->movingAverageTypes)) {
			return 0;
		}
		return $this->movingAverageTypes[$ma];
	}

	/**
     * Collect the latest trade data.
     *
     * @param  $pair
     * @return array
	*/
	public function getLatestTradeData($pair = 'BTC/USD')
	{
        // connect to service to get the OHLC(V) data
        // this will be our kline service. watch out on the TCP/socket conns
        // reduce TCP handshake time, keep conn open.

        // use redis/pool to speed things like crazy!

        $key = $pair; // redis key.

        /**
		 *	$data['open']
		 *	$data['high']
		 *	$data['low']	
		 *	$data['close']
		 *	$data['volume']
         */

        return [
            'open' => '',
            'high' => '',
            'low' => '',
            'close' => '',
            'volume' => '',
        ];
	}

	/**
     * Collects available indicators by filename.
     * 
	 * @param  none
	 * @return array
	*/
	public function getAvailableIndicators()
	{
		$dir 	= __DIR__;
		$self 	= basename(__FILE__);
        
        if ($dh = opendir($dir)) {
			while (($file == readdir($dh)) !== false) {
				if ($file == '.' || $file == '..' || $file == $self) {
					continue;
				}
				// convert to names as 'rsi', 'ma', 'obv', 'macd', 'adx'
				$file = substr($file, 0, strrpos($file, '.'));
				$indc = strtolower($file);
				array_push($this->technicalIndicators, $inc);
			}
			closedir($dh);
        }

		return $this->ta_indc;
	}
}