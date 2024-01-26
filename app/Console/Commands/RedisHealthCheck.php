<?php
/**
 *  @author: $rachow
 *  @copyright: Coinhoppa
 *
 *  Command for checking redis server(s)
 */
namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use Predis\Connection\ConnectionException;
use Predis\Client;

class RedisHealthCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redis:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command simply checks that redis is running smoothly.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // We could use facade...?
        // Redis::command('x', 'x');
        
        $redisConfig = config('database.redis.default');

        try {
            $redis = new Client([
                'scheme' => 'tcp',
                'host' => $redisConfig['host'] ?? '127.0.0.1',
                'port' => $redisConfig['port'] ?? '6379',
                'password' => $redisConfig['password'] ?? '',
            ]);
            
            $redis->set('redis-status', 'OK');
            $status = $redis->get('redis-status');

            $this->info('[OK] - Redis is running.');

        } catch (ConnectionException $e) {
            $this->error($e->getMessage());
        } catch (Exception $ee) {
            $this->error($ee->getMessage());
        }
    }
}
