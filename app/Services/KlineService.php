<?php
/**
 *  @author: $rachow
 *  @copyright: Coinhoppa
 * 
 *  Kline Service is Candlesticks data => OHLCV
 *      Make Guzzle Http Service call
 *          todo: pass X-Trace-Id
 */

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Collection;

class KlineService
{

    // Illuminate\Support\Facades\Http
    protected $client;

    // Illuminate\Http\Client\Response (promise)
    protected $response;

    // I/O resource or boolean
    protected $debug;

    /**
     * Creates an instance.
     * 
     * @param Illuminate\Http\Request
     * @return void
     */
    public function __construct(Request $request)
    {
        $sslVerify = file_exists(base_path() . '/backend/cert/cacert.pem') ? 
            base_path() . '/backend/cert/cacert.pem' : false;
        
        // Hook Amazon Cloudwatch for stderr to console.
        $this->debug = false;
        if (App::environment(['local', 'development', 'staging'])) {
            $debug = fopen('php://stderr', 'w');           
        }
        $this->logwatch('Kline Service running.');
        $traceId = $request->header('X-Trace-Id') ?? Str::uuid()->toString(); 

        $this->client = Http::withOptions([
            'verify' => $sslVerify,
            'debug' => (bool) $debug
        ])->withHeaders([
            'X-Trace-Id' => $traceId,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . config('kline.api_token'),
        ])->baseUrl(config('kline.api_url'));
    }

    /**
     * Destroy the instance.
     * 
     * @param  none
     * @return void
     */
    public function __destruct()
    {
        // I/O free up.
        if (is_resource($this->debug)) {
            fclose($this->debug);
        }
    }
 
    /**
     * Log to stderr if in mode.
     * 
     * @param  $message
     * @return void
     */
    private function logwatch($message)
    {
        if (is_resource($this->debug)) {
            fwrite($this->debug, $message);
        }
    }

    /**
     * Collect the Candlestick data from service.
     * 
     * @param  $pair
     * @param  $intval
     * @param  $exchange
     * @return json
     */
    public function candle($pair = 'BTC/USD', $intval = '5m', $exchange = 'binance')
    {
        // get the endpoint
        $page = 1;
        $endpoint = config('kline.api_endpoint');
        $response = $this->client->get($endpoint . '/' . $page . '/?' . http_build_query([
            'pair' => $pair,
            'intval' => $intval,
            'exchange' => $exchange
        ]));
        // logging if enabled
        $response->onError(function(){
            $this->logwatch(implode("\r\n", func_get_args()));
            return [];
        });
        
        $responseBody = $response->body();

        //  if (!json_validate($responseBody)) {..}
 
        $responseJson = json_decode($response->body(), true);
 
        if (json_last_error() !== JSON_ERROR_NONE) {
            $error = json_last_error_msg();
            $this->logwatch($error);
            return [];
        }

        return [
            'open' => $responseJson['o'] ?? '',
            'high' => $responseJson['h'] ?? '',
            'low' => $responseJson['l'] ?? '',
            'close' => $responseJson['c'] ?? '',
            'volume' => $responseJson['v'] ?? '',
        ];
    }
}