<?php
/**
 *  @author: $rachow
 *  @copyright: Coinhoppa
 *
 *  Heavy API Request logger, to disable or send TCP (Fire+Forget)
 *  to logging hub.
 */

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Illuminate\Support\Facades\App;

class RequestLogger
{
    // RequestLoggerContext class runs before.
    
    private $logger;

    public function __construct(Request $request)
    {
        $this->logger = $this->getLoggerInstance($request);
    }

    /**
     * Handle an incoming request.
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!App::environment(['locale', 'development', 'staging'])) {
            return $next($request); // skip to next middleware
        }

        $this->logger->info('Incoming:');
		$url = $request->url();
		$query = $request->getQueryString();
		$method = $request->method();
		$ip	= $request->ip();
		$header = $this->getHeadersFromRequest();
		$body	= $request->getContent();

        $url_str = "$ip $method $url";

        if ($query) {
            $url_str .= "?$query";
        }

		if (array_key_exists('Authorization', $header)) {
		    // [$rachow] - hide credentials from logs always. but add other contexts like trader id, etc.
            $header['Authorization'] = 'XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX';
		}

        $this->logger->info($url_str);
        $this->logger->info(json_encode($header, JSON_PRETTY_PRINT));
        $this->logger->info($body);

		return $next($request);
    }


	/**
     * Grab the logging stream handler instance.
     * 
     * @param  \Illuminate\Http\Request
	 * @return resource
	 */
	private function getLoggerInstance(Request $request)
	{
		$date_str = now()->format('m_d_Y');
		$file_path = 'api.log';
		
		$date_format = "d/m/Y H:i:s";

		$output = "[%datetime%] %channel%.%level_name%: %message%\n";
		$formatter = new LineFormatter($output, $date_format);

		$stream = new StreamHandler(storage_path('logs/' . $file_path), Logger::DEBUG);
		$stream->setFormatter($formatter);

		$request_id = $request->header('X-Trace-Id') ?? Str::uuid()->toString();
		$logger = new Logger($request_id);
		$logger->pushHandler($stream);

		return $logger;
	}

	/**
     * Handle any post termination of request.
     * 
	 * @param  \Illuminate\Http\Request
	 * @param  \Illumninate\Http\JsonResponse
	 * @return void
	 */
	public function terminate(Request $request, JsonResponse $response)
    {
        if (!App::environment(['locale', 'development', 'staging'])) {
            return;
        }

	    $this->logger->info('Outgoing:');
		$this->logger->info($response);
	}

	/**
     * Grab all HTTP request headers for logger.
     *
	 * @return array
	 */
    private function getHeadersFromRequest()
    {
        $headers = [];
		foreach ($_SERVER as $key => $value) {
			if (substr($key, 0, 5) <> 'HTTP_') {
				continue;
			}
			$header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
			$headers[$header] = $value;
        }

		return $headers;
	}
}
